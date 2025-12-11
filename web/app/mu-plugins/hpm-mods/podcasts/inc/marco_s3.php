<?php
/*
A simple PHP class to perform basic operations against Amazon S3 and compatible
services. Requires modern PHP (7+, probably) with curl, dom, and iconv modules.

Copyright 2022 Marco Arment. Released under the MIT license:

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
*/
if ( ! defined( 'ABSPATH' ) ) exit;
class S3
{
    public static $connect_timeout = 10;
    public static $timeout = 60;
    public static $auto_retry_delays = [0, 1, 2, 5, 10]; // set to empty array to disable auto-retrying

    public $automatic_decompression_on_get = true;

    private $access_key_id;
    private $secret_key;
    private $custom_endpoint = '';
    private $service = 's3';
    private $region;
    private $bucket_name;
    private $supports_https = false;

    public function __construct($access_key_id, $secret_key, $bucket_name, $region = 'us-east-1', $custom_endpoint = '')
    {
        $this->access_key_id = $access_key_id;
        $this->secret_key = $secret_key;
        $this->bucket_name = $bucket_name;
        $this->region = $region;
        $this->custom_endpoint = $custom_endpoint;

        $this->supports_https = (false === strpos($this->bucket_name, '.'));
    }

    public function head($key, &$headers_out = NULL)
    {
        list($status, $headers_out, $response) = $this->_action('HEAD', $key);
        return $status;
    }

    public function get($key, &$headers_out = NULL)
    {
        list($status, $headers_out, $response) = $this->_action('GET', $key);
        if ($status != 200) throw new Exception("S3::get returned $status");

        if ($this->automatic_decompression_on_get) {
            foreach ($headers_out as $k => $v) {
                if (strtolower($k) == 'content-encoding' && $v == 'deflate' && false !== ($decompressed = @gzuncompress($response)) ) {
                    $response = $decompressed;
                    break;
                }
            }
        }
        return $response;
    }

    public function put($key, $mime_type, $acl, $content, $additional_headers = NULL)
    {
        $additional_headers = $additional_headers ?: [];
        $additional_headers['x-amz-acl'] = $acl;
        $additional_headers['Content-Type'] = $mime_type;

        list($status, $response_headers, $response) = $this->_action('PUT', $key, [], $additional_headers, $content);
        if ($status != 200) throw new Exception("S3::put returned $status");
    }

    public function put_compressed($key, $mime_type, $acl, $content, $additional_headers = NULL, $compression_level = 9)
    {
        $headers = $additional_headers ?: [];
        $compressed = @gzcompress($content, $compression_level);
        if ($compressed !== false && strlen($compressed) < strlen($content) && false !== ($decompressed = @gzuncompress($compressed)) && $decompressed == $content) {
            $headers['Content-Encoding'] = 'deflate';
            $content = $compressed;
        }
        $this->put($key, $mime_type, $acl, $content, $headers);
    }

    public function delete($key)
    {
        list($status, $response_headers, $response) = $this->_action('DELETE', $key);
        if ($status != 204) throw new Exception("S3::delete returned $status");
    }

    public function list($prefix = '', &$out_total_byte_size = NULL)
    {
        list($status, $response_headers, $response) = $this->_action('GET', '', strlen($prefix) ? ['prefix' => $prefix] : []);
        if ($status != 200) throw new Exception("S3::list returned $status");
        $dom = new DOMDocument();
        if (! $dom->loadXML($response) || $dom->documentElement->nodeName != 'ListBucketResult') throw new Exception("S3::list returned unrecognized format");

        $keys = [];
        $out_total_byte_size = 0;
        foreach ($dom->getElementsByTagName('Contents') as $content) {
            $keys[] = $content->getElementsByTagName('Key')->item(0)->nodeValue;
            $out_total_byte_size += intval($content->getElementsByTagName('Size')->item(0)->nodeValue);
        }
        return $keys;
    }

    public function signed_download_url_for_key($key, $ttl = 0, $force_https = false)
    {
        $host = $this->hostname($force_https);
        $url = $this->public_url_for_key($key, $force_https);
        $unix_timestamp = time();
        $date_str = gmdate('Ymd', $unix_timestamp);
        $timestamp_str = gmdate('Ymd\THis\Z', $unix_timestamp);
        $scope = "$date_str/{$this->region}/{$this->service}/aws4_request";
        $canonical_header_str = 'host:' . $host;
        $signed_headers_str = 'host';

        $query_params = [
            'X-Amz-Algorithm' => 'AWS4-HMAC-SHA256',
            'X-Amz-Credential' => $this->access_key_id . '/' . $scope,
            'X-Amz-Date' => $timestamp_str,
            'X-Amz-SignedHeaders' => $signed_headers_str,
        ];
        if ($ttl > 0) $query_params['X-Amz-Expires'] = $ttl;

        $sorted_query_str_parts = [];
        ksort($query_params, SORT_STRING);
        foreach ($query_params as $n => $v) $sorted_query_str_parts[] = rawurlencode($n) . '=' . rawurlencode($v);
        $query_string = implode('&', $sorted_query_str_parts);

        $canonical_request = "GET\n" . ($force_https ? '/' . $this->bucket_name : '') . $this->normalize_key($key) . "\n" . $query_string . "\n" . $canonical_header_str . "\n\n" . $signed_headers_str . "\nUNSIGNED-PAYLOAD";
        $string_to_sign = "AWS4-HMAC-SHA256\n$timestamp_str\n$scope\n" . hash('sha256', $canonical_request);
        $signing_key = hash_hmac('sha256', 'aws4_request', hash_hmac('sha256', $this->service, hash_hmac('sha256', $this->region, hash_hmac('sha256', $date_str, 'AWS4' . $this->secret_key, true), true), true), true);
        $signature = hash_hmac('sha256', $string_to_sign, $signing_key);
        return $url . "?{$query_string}&X-Amz-Signature=$signature";
    }

    // returns a tuple: list($form_action, $form_hidden_values) = $s3->signed_upload_form(...)
    //
    // $key can include a $filename placeholder, e.g. "/user/123/${filename}"
    //
    // conditions can be e.g.
    //     ['starts-with', '$key', '/user/123/'],
    //     ['starts-with', '$Content-Type', 'audio/'],
    //     ['content-length-range', 0, 1048576],
    // documentation:
    //     https://docs.aws.amazon.com/AmazonS3/latest/API/sigv4-HTTPPOSTConstructPolicy.html#sigv4-ConditionMatching
    //
    public function signed_upload_form($key, $acl, $ttl = 0, array $conditions = [])
    {
        $key = ltrim($this->normalize_key($key), '/');
        $unix_timestamp = time();
        $date_str = gmdate('Ymd', $unix_timestamp);
        $timestamp_str = gmdate('Ymd\THis\Z', $unix_timestamp);
        $scope = "$date_str/{$this->region}/{$this->service}/aws4_request";

        $upload_policy = [
            'expiration' => date('Y-m-d\TH:i:s', time() + $ttl) . 'Z',
            'conditions' => array_merge($conditions, [
                [ 'bucket' => $this->bucket_name ],
                [ 'key' => $key ],
                [ 'acl' => $acl ],
                [ 'x-amz-credential' => $this->access_key_id . '/' . $scope ],
                [ 'x-amz-algorithm' => 'AWS4-HMAC-SHA256' ],
                [ 'x-amz-date' => $timestamp_str ],
            ])
        ];
        $enc_upload_policy = base64_encode(json_encode($upload_policy));
        $signing_key = hash_hmac('sha256', 'aws4_request', hash_hmac('sha256', $this->service, hash_hmac('sha256', $this->region, hash_hmac('sha256', $date_str, 'AWS4' . $this->secret_key, true), true), true), true);

        return [
            // form action

            // this one requires unencrypted HTTP with dot-named buckets, which can't be POSTed to from HTTPS pages without browser warnings
            //   ($this->supports_https ? 'https' : 'http') . '://' . $this->hostname(),

            // this one is always HTTPS, but requires path-style bucket addressing, which is deprecated by Amazon but seems to stick around indefinitely
            'https://' . $this->hostname(true) . '/' . $this->bucket_name,

            // form values
            [
                'key' => $key,
                'acl' => $acl,
                'x-amz-credential' => $this->access_key_id . '/' . $scope,
                'x-amz-algorithm' => 'AWS4-HMAC-SHA256',
                'x-amz-signature' => hash_hmac('sha256', $enc_upload_policy, $signing_key),
                'x-amz-date' => $timestamp_str,
                'policy' => $enc_upload_policy,
            ]
        ];
    }

    public function public_url_for_key($key, $force_https = false)
    {
        if ($force_https) return 'https://' . $this->hostname(true) . '/' . $this->bucket_name . $this->normalize_key($key);
        else              return ($this->supports_https ? 'https://' : 'http://') . $this->hostname() . $this->normalize_key($key);
    }

    private function hostname($path_style = false)
    {
        if ($path_style) return strlen($this->custom_endpoint) ? "{$this->region}.{$this->custom_endpoint}" : "{$this->service}.{$this->region}.amazonaws.com";
        else             return strlen($this->custom_endpoint) ? "{$this->bucket_name}.{$this->region}.{$this->custom_endpoint}" : "{$this->bucket_name}.{$this->service}.{$this->region}.amazonaws.com";
    }

    private function normalize_key($key)
    {
        // URL-escape everything except slashes, and ensure that it starts with a leading slash
        $key = str_replace('%2F', '/', rawurlencode($key));
        if (strlen($key) > 0 && $key[0] != '/') $key = '/' . $key;
        return $key;
    }

    private function _action($verb, $key, $query_params = [], $request_headers = [], $body = NULL)
    {
        $delays = static::$auto_retry_delays;
        do {
            $result = $this->_action_try($verb, $key, $query_params, $request_headers, $body);
            $status = $result[0];
            $success = $status != 0 && $status != 100 && $status != 500 && $status != 502 && $status != 503;
            if ($success) break;

            // Failure
            static::$curl_handle = NULL; // force a fresh Curl handle to be created on next attempt
            $retry_delay = array_shift($delays);
            if ($retry_delay) sleep($retry_delay);
        } while ($delays);

        return $result;
    }

    private function _action_try($verb, $key, $query_params = [], $request_headers = [], $body = NULL)
    {
        $uri = $this->normalize_key($key);
        if ($uri === '') $uri = '/';

        $host = $this->hostname();
        $request_headers['Host'] = $host;
        $content_sha256 = hash('sha256', $body === NULL ? '' : $body);
        $request_headers['x-amz-content-sha256'] = $content_sha256;

        $unix_timestamp = time();
        $timestamp_str = gmdate('Ymd\THis\Z', $unix_timestamp);
        $date_str = gmdate('Ymd', $unix_timestamp);
        $request_headers['x-amz-date'] = $timestamp_str;

        $sorted_query_str_parts = [];
        ksort($query_params, SORT_STRING);
        foreach ($query_params as $n => $v) $sorted_query_str_parts[] = rawurlencode($n) . '=' . rawurlencode($v);
        $query_string = implode('&', $sorted_query_str_parts);

        $canonical_headers = [];
        $signed_headers = [];
        ksort($request_headers, SORT_STRING | SORT_FLAG_CASE);
        foreach ($request_headers as $h => $v) {
            $lower_h = strtolower($h);
            $canonical_headers[] =  $lower_h . ':' . trim($v);
            $signed_headers[] = $lower_h;
        }
        $canonical_header_str = implode("\n", $canonical_headers);
        $signed_headers_str = implode(';', $signed_headers);

        $canonical_request = "$verb\n$uri\n$query_string\n$canonical_header_str\n\n$signed_headers_str\n$content_sha256";
        $scope = "$date_str/{$this->region}/{$this->service}/aws4_request";
        $string_to_sign = "AWS4-HMAC-SHA256\n$timestamp_str\n$scope\n" . hash('sha256', $canonical_request);
        $signature = hash_hmac('sha256', $string_to_sign, hash_hmac('sha256', 'aws4_request', hash_hmac('sha256', $this->service, hash_hmac('sha256', $this->region, hash_hmac('sha256', $date_str, 'AWS4' . $this->secret_key, true), true), true), true));
        $request_headers['Authorization'] = "AWS4-HMAC-SHA256 Credential={$this->access_key_id}/$scope,SignedHeaders=$signed_headers_str,Signature=$signature";
        $proto = $this->supports_https ? 'https' : 'http';
        $url = "$proto://$host$uri" . (strlen($query_string) ? '?' . $query_string : '');
        $headers = [];
        foreach ($request_headers as $h => $v) $headers[] = "$h: $v";

        $c = $this->connect();
        $header_data = '';
        curl_setopt_array($c, [
            CURLOPT_CUSTOMREQUEST => $verb,
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_HEADERFUNCTION => function($c, $data) use (&$header_data) { $header_data .= $data; return strlen($data); },
        ]);
        $response = curl_exec($c);
        $status = curl_getinfo($c, CURLINFO_HTTP_CODE);
        $response_headers = [];
        try { $response_headers = iconv_mime_decode_headers($header_data, ICONV_MIME_DECODE_CONTINUE_ON_ERROR, 'UTF-8'); } catch (Exception $e) { }
        return [$status, $response_headers, $response];
    }

    private static $curl_handle;
    private function connect()
    {
        $this->header_data = '';
        if (static::$curl_handle) return static::$curl_handle;

        static::$curl_handle = curl_init();
        curl_setopt_array(static::$curl_handle, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2_0,
            CURLOPT_CONNECTTIMEOUT => static::$connect_timeout,
            CURLOPT_TIMEOUT => static::$timeout,
            CURLOPT_FOLLOWLOCATION => 0,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);
        return static::$curl_handle;
    }
}
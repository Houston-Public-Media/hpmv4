<?php

if ( ! defined( 'ABSPATH' ) ) exit;
class HPM_Videos {
	public string $playlist_id;
	protected string $account_id;
	protected string $policy_key;
	public string $player_id;
	public int $paging_limit;
	public function __construct() {
		add_action( 'rest_api_init', [ $this, 'init' ] );
		$this->playlist_id = HPM_BC_PLAYLIST_ID;
		$this->account_id = HPM_BC_ACCOUNT_ID;
		$this->policy_key = HPM_BC_POLICY_KEY;
		$this->player_id = HPM_BC_PLAYER_ID;
		$this->paging_limit = HPM_BC_PAGING_LIMIT;
	}
	public function init(): void {
		// Register WP-REST API endpoints
		register_rest_route( 'hpm-video/v1', '/list', [
			'methods'  => 'GET',
			'callback' => [ $this, 'get_api' ],
			'args'     => [
				'limit' => [
					'default' => $this->paging_limit,
					'sanitize_callback' => 'absint'
				],
				'offset' => [
					'default' => 0,
					'sanitize_callback' => 'absint'
				]
			],
			'permission_callback' => function() {
				return true;
			}
		] );

		register_rest_route( 'hpm-video/v1', '/list/(?P<playlistId>[0-9]+)', [
			'methods'  => 'GET',
			'callback' => [ $this, 'get_api' ],
			'args' => [
				'playlistId' => [
					'required' => true
				],
				'limit' => [
					'default' => $this->paging_limit,
					'sanitize_callback' => 'absint'
				],
				'offset' => [
					'default' => 0,
					'sanitize_callback' => 'absint'
				]
			],
			'permission_callback' => function() {
				return true;
			}
		] );
	}
	// Get Brightcove playlist to show on home page after local shows block starts here
	public static function get( $playlistId, $limit = HPM_BC_PAGING_LIMIT, $offset = 0 ): array {
		$url = "https://edge.api.brightcove.com/playback/v1/accounts/" . HPM_BC_ACCOUNT_ID;
		if ( empty( $playlistId ) ) {
			$url .= "/videos";
			$transient_key = 'hpm_bc_videos_' . $limit . '_' . $offset;
		} else {
			$url .= "/playlists/" . $playlistId;
			$transient_key = 'hpm_bc_playlist_' . $playlistId . '_' . $limit . '_' . $offset;
		}
		$url .= "?limit={$limit}&offset={$offset}&sort=-created_at";
		$videos = get_transient( $transient_key );
		if ( !empty( $videos ) ) {
			return $videos;
		} else {
			$videos = [];
		}
		$response = wp_remote_get( $url,
			[
				'headers' => [
					'Accept'       => "application/json;pk=" . HPM_BC_POLICY_KEY,
					'User-Agent'   => 'WordPress/' . get_bloginfo('version'),
				],
				'timeout' => 15,
			]
		);
		if ( is_wp_error( $response ) ) {
			print_r( $response->get_error_message() );
			return $videos;
		}
		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );
		if ( !empty( $data['videos'] ) ) {
			foreach ( $data['videos'] as $video ) {
				$temp = [
					'id' => (int)$video['id'],
					'poster' => $video['poster'],
					'thumbnail' => $video['thumbnail'],
					'name' => $video['name'],
					'description' => ( !empty( $video['description'] ) ? $video['description'] : '' ),
					'duration' => $video['duration'],
					'published' => $video['published_at'],
					'source' => '',
					'type' => '',
					'playerUrl' => 'https://players.brightcove.net/' . HPM_BC_ACCOUNT_ID . '/' . HPM_BC_PLAYER_ID . '_default/index.html?videoId=' . $video['id']
				];
				$ext_ver = 0;
				$hls = $mp4 = '';
				foreach ( $video['sources'] as $source ) {
					if ( preg_match( '/^https:\/\//', $source['src'] ) ) {
						if ( !empty( $source['type'] ) && $source['type'] == 'application/x-mpegURL' ) {
							if ( $ext_ver < $source['ext_x_version'] ) {
								$ext_ver = $source['ext_x_version'];
								$hls = $source['src'];
							}
						} elseif ( !empty( $source['container'] ) && $source['container'] == 'MP4' ) {
							$mp4 = $source['src'];
						}
					}
				}
				if ( !empty( $hls ) ) {
					$temp['source'] = $hls;
					$temp['type'] = 'hls';
				} elseif ( !empty( $mp4 ) ) {
					$temp['source'] = $mp4;
					$temp['type'] = 'mp4';
				} else {
					continue;
				}
				$videos[] = $temp;
			}
		}
		set_transient( $transient_key, $videos, 300 );
		return $videos;
	}

	public function get_api( WP_REST_Request $request ): WP_REST_Response {
		if ( !empty( $request['playlistId'] ) ) {
			$message = 'HPM Videos Playlist (' . $request['playlistId'] . ') Output';
		} else {
			$message = 'HPM Videos Output';
		}
		$videos = $this->get( $request['playlistId'], $request['limit'], $request['offset'] );
		return rest_ensure_response( [ 'code' => 'rest_api_success', 'message' => esc_html__( $message, 'hpm-videos' ), 'data' => [ 'videos' => $videos, 'status' => 200 ] ] );
	}
}
new HPM_Videos();
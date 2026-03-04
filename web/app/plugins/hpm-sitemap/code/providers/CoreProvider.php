<?php
namespace hpmSitemap;

class MapItem {
	public $location;
	public $title;
	public $description;
	public $modified = null ;
	public $priority;
	public $frequency;
	public $images  = null;
}

class MediaItem	{
	public string $location;
	public string $title;
	public string $caption;
	public string $description;
}

interface ISitemapProvider {
	public function getSuppportedTypes(); // returns a list of the types supported by this provider
	public function setFormat( $format ); // sets the base file url for the sitemap
	public function getPageCount( $type ); // returns the number of sitemap pages for this type.
	public function getPage( $type, $page );
}

class SitemapProvider {
	static function getProviderList(): array {
		return [ "index", "posts", "terms", "archive", "authors", "news", "latest" ];
	}

	static function validate( $type ): void {
		$types = self::getProviderList();
		if ( !in_array( $type, $types ) ) {
			echo 'XML Sitemap Generator Error. <br />Invalid Provider type specified: ' . esc_html( $type );
			exit;
		}
	}

	// creates an instance of the correct provider for the given type
	static function getInstance( $type ) {
		self::validate( $type );
		$type = ucwords( $type );
		$file = $type . 'Provider.php';
		if ( @include_once( $file ) ) {
			$class = '\\hpmSitemap\\' . esc_html( $type ) . 'Provider';
			return new $class();
		} else {
			echo 'XML Sitemap Generator Error. <br />Invalid Renderer type specified: ' . esc_html( $type );
			exit;
		}
	}
}

class ProviderCore {
	protected array $urlsList = [];
	protected int $blogPageSize;
	protected bool $isExecuting = false;
	protected string $siteName = "";
	protected string $blogUrl = "";
	protected $instance;
	protected SitemapDefaults $sitemapDefaults;
	protected string $tablemeta;
	protected string $format = "";
	protected GlobalSettings $globalSettings;
	public function __construct() {
		global $wpdb;
		$this->blogPageSize = get_option('posts_per_page');
		$this->urlsList = [];
		$this->siteName = get_option('blogname');
		$this->blogUrl = get_bloginfo( 'url' );
		$this->tablemeta = $wpdb->prefix . 'xsg_sitemap_meta';
		$this->sitemapDefaults = get_option( "hpmXSG_sitemapDefaults", new SitemapDefaults() );
		$this->globalSettings = Core::getGlobalSettings();
	}

	public function setFormat( $format ): void {
		$this->format = $format;
	}

	private function getAttribute( $name, $html ) {
		preg_match( '@' . $name . '="([^"]+)"@' , $html, $match );
		if ( !empty( $match ) ) {
			return str_replace( '&', '&amp;', array_pop( $match ) );
		} else {
			return '';
		}
	}


	// get image from post result
	protected function getImage( $result ) {
		if ( !empty( $result->imageUrl ) ) {
			$new = new MediaItem();
			$new->title = $result->imageTitle;
			$new->caption = $result->imageCaption;
			$new->location = $result->imageUrl;
			return $new;
		}
	}

	// get images from html
	protected function getImages( $content ): array {
		preg_match_all( '/<img[^>]+>/i', $content, $matches );
		$images = [];
		foreach( $matches[0] as $match ) {
			$url = $this->getAttribute( "src", $match );

			// need to validate url is in this site.
			// remove any resizing. -1024x682.
			$new = new MediaItem();
			$new->title = $this->getAttribute( "title", $match );
			$new->caption = $this->getAttribute( "alt", $match );
			$new->location = $url;
			$images[] = $new;
		}
		return $images;
	}

	static function getDateField( $name ): string {
		if ( $name == "created" ) {
			return "post_date";
		} else {
			return "post_modified";
		}
	}

	function getPages( $count, $pageSize ): float {
		return ceil( $count / $pageSize );
	}

	function getBlogPageCount($results): float|int {
		$totalPages = 0;
		foreach( $results as $result ) {
			$pages = 1;
			$posts = $result->posts + 1;
			if ( $posts > $this->blogPageSize ) {
				$pages =  ceil( $posts / $this->blogPageSize );
			}
			$totalPages += $pages;
		}
		return $totalPages;
	}

	function isIncluded( $url, $rules ): bool {
		//todo
		return true;
	}
	function isExcluded( $value ) {
		if ( isset( $value ) && $value == 2 ) {
			return true;
		}
		return false;
	}

	function getMetaValue( $postValue, $tagValue, $default ) {
		if ( isset( $postValue ) && $postValue != 1 ) {
			return $postValue;
		}
		if ( isset( $tagValue ) && $tagValue != 1 ) {
			return $tagValue;
		}
		return $default;
	}

	function addUrls( $postCount, $mapItem ): void {
		$pages = 1;
		if ( $postCount > $this->blogPageSize ) {
			$pages = ceil( $postCount / $this->blogPageSize );
		}

		$this->urlsList[] = $mapItem; // first page
		for ($x = 2; $x <= $pages; $x++) {
			$new = clone $mapItem;
			$new->title = $new->title . " | Page " . $x;
			$new->location = $this->getPageLink( $mapItem->location, $x );
			$this->urlsList[] = $new;
		}
	}

	function getPageLink( $request, $pagenum = 1 ) {
		global $wp_rewrite;
		$pagenum = (int) $pagenum;

		$home_root = preg_quote( home_url(), '|' );
		$request = preg_replace( '|^'. $home_root . '|i', '', $request);
		$request = ltrim( $request, "/" );

		if ( !$wp_rewrite->using_permalinks() || is_admin() ) {
			$base = trailingslashit( $this->blogUrl );
			if ( $pagenum > 1 ) {
				$result = add_query_arg( 'paged', $pagenum, $base . $request );
			} else {
				$result = $base . $request;
			}
		} else {
			$qs_regex = '|\?.*?$|';
			preg_match( $qs_regex, $request, $qs_match );
			if ( !empty( $qs_match[0] ) ) {
				$query_string = $qs_match[0];
				$request = preg_replace( $qs_regex, '', $request );
			} else {
				$query_string = '';
			}

			$request = preg_replace( "|$wp_rewrite->pagination_base/\d+/?$|", '', $request );
			$request = preg_replace( '|^' . preg_quote( $wp_rewrite->index, '|' ) . '|i', '', $request );
			$request = ltrim( $request, '/' );
			$base = trailingslashit( $this->blogUrl );

			if ( $wp_rewrite->using_index_permalinks() && ( $pagenum > 1 || '' != $request ) ) {
				$base .= $wp_rewrite->index . '/';
			}

			if ( $pagenum > 1 ) {
				$request = ( ( !empty( $request ) ) ? trailingslashit( $request ) : $request ) . user_trailingslashit( $wp_rewrite->pagination_base . "/" . $pagenum, 'paged' );
			}
			$result = $base . $request . $query_string;
		}

		return apply_filters( 'get_pagenum_link', $result );
	}
}
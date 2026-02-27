<?php
namespace hpmSitemap;

interface ISitemapRenderer {
	public function renderIndex( $page );
	public function renderPages( $urls );
}

class SitemapRenderer {
	// returns a list of  the core provider types
	static function getRendererList(): array {
		return [ "rss", "xml", "htm", "news" ];
	}

	static function validate( $type ): void {
		$types = self::getRendererList();
		if ( !in_array( $type, $types ) ) {
			echo 'XML Sitemap Generator Error. <br />Invalid Renderer type specified : ' . esc_html( $type );
			exit;
		}
	}

	static function getInstance( $type ) {
		self::validate( $type );
		$type = ucwords( $type );
		$file = $type . 'Renderer.php';
		if ( @include_once( $file ) ) {
			$class = '\\hpmSitemap\\' . esc_html( $type ) . 'Renderer';
			return new $class();
		} else {
			echo 'XML Sitemap Generator Error. <br />Renderer not found : ' . esc_html( $type );
			exit;
		}
	}
}

class RendererCore {
	public $timeZone;
	function __construct() {
		$this->timeZone = wp_timezone();
	}

	public function getDateString( $dt, $format ): ?string {
		$date = new \DateTime();
		$date->setTimestamp( $dt );
		$date->setTimezone( $this->timeZone );
		return esc_attr( $date->format( $format ) );
	}
}
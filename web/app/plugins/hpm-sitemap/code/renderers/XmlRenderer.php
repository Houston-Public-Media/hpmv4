<?php
namespace hpmSitemap;

class XmlRenderer extends RendererCore implements ISitemapRenderer {
	private function getFrequency( $value ): string {
		return match ( $value ) {
			0 => "",
			1 => "default",
			2 => "never",
			3 => "yearly",
			4 => "monthly",
			5 => "weekly",
			6 => "daily",
			7 => "hourly",
			8 => "always",
			default => "xxx",
		};
	}

	private function getPriority( $value ): string {
		return match ( $value ) {
			0 => "",
			1 => "default",
			2 => "0.0",
			3 => "0.1",
			4 => "0.2",
			5 => "0.3",
			6 => "0.4",
			7 => "0.5",
			8 => "0.6",
			9 => "0.7",
			10 => "0.8",
			11 => "0.9",
			12 => "1.0",
			default => "xxx",
		};
	}

	private function renderImages( $images ): void {
		foreach( $images as $image ) {
			echo '<image:image>';
			echo '<image:loc>' . esc_url( $image->location ) . '</image:loc>';
			if ( !empty( $image->caption ) ) {
				echo '<image:caption>' . esc_attr( $image->caption ) . '</image:caption>';
			}
			if ( !empty( $image->title ) ) {
				echo '<image:title>' . esc_attr( $image->title ) . '</image:title>';
			}
			echo "</image:image>\n";
		}
	}

	private function renderItem( $url ): void {
		echo '<url>';
		echo '<loc>' . esc_url( $url->location ) . '</loc>';
		echo '<lastmod>' . $this->getDateString( $url->modified, 'Y-m-d\TH:i:sP' ) . '</lastmod>';
		if ( !$url->frequency == 0 ) {
			echo '<changefreq>' .  esc_attr( $this->getFrequency( $url->frequency ) ) . '</changefreq>';
		}
		if ( !$url->priority == 0 ) {
			echo "<priority>" . esc_attr( $this->getPriority( $url->priority ) ) . "</priority>";
		}
		if ( isset( $url->images ) ) {
			$this->renderImages( $url->images );
		}
		echo "</url>\n";
	}

	public function renderIndex( $urls ): void {
		$urlXls = xsgPluginPath(). '/assets/SitemapXML.xsl';
		ob_get_clean();
		ob_start();
		header( 'Content-Type: text/xml; charset=utf-8' );
		echo '<?xml version="1.0" encoding="UTF-8" ?>';
		echo "\n";
		echo '<?xml-stylesheet type="text/xsl" href="' . esc_url( $urlXls ) . '"?>';
		echo "\n";
		echo '<sitemapindex xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd http://www.google.com/schemas/sitemap-image/1.1 http://www.google.com/schemas/sitemap-image/1.1/sitemap-image.xsd">';
		echo "\n";
		if ( isset( $urls ) ) {
			foreach ( $urls as $url ) {
				echo '<sitemap>';
				echo '<loc>' . esc_url( $url->location ) . '</loc>';
				echo "</sitemap>\n";
			}
		}
		echo "\n";
		echo '</sitemapindex>';
		echo "\n";
		ob_end_flush();
	}

	public function renderPages( $urls ): void {
		$urlXls = xsgPluginPath() . '/assets/SitemapXML.xsl';
		ob_get_clean();
		ob_start();
		header( 'Content-Type: text/xml; charset=utf-8' );
		echo '<?xml version="1.0" encoding="UTF-8" ?>';
		echo "\n";
		echo '<?xml-stylesheet type="text/xsl" href="' . esc_url( $urlXls ) . '"?>';
		echo "\n";
		echo '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd http://www.google.com/schemas/sitemap-image/1.1 http://www.google.com/schemas/sitemap-image/1.1/sitemap-image.xsd">';
		echo "\n";
		if ( isset( $urls ) ) {
			foreach ( $urls as $url ) {
				$this->renderItem( $url );
			}
		}
		echo "\n";
		echo '</urlset>';
		echo "\n";
		ob_end_flush();
	}
}
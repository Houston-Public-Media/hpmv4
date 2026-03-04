<?php
namespace hpmSitemap;

class NewsRenderer extends rendererCore implements ISitemapRenderer {
	private function renderImages( $images ): void {
		foreach( $images as $image ) {
			echo '<image:image>';
			echo '<image:loc>' . esc_url( $image->location ) . '</image:loc>';
			echo '<image:caption>' . esc_attr( $image->caption ) . '</image:caption>';
			echo '<image:title>' . esc_attr( $image->title ) . '</image:title>';
			echo "</image:image>\n";
		}
	}

	private function renderItem( $siteName, $url ): void {
		echo '<url>';
		echo '<loc>' . esc_url( $url->location ) . '</loc>';
		echo '<news:news>';
		echo '<news:publication>';
		echo '<news:name>' . esc_attr( $siteName ) . '</news:name>';
		echo '<news:language>' . substr( esc_attr( get_bloginfo( 'language' ) ), 0, 2 ) . '</news:language>';
		echo '</news:publication>';
		echo '<news:publication_date>' .  $this->getDateString( $url->modified, 'Y-m-d\TH:i:sO' ) . '</news:publication_date>';
		echo '<news:title>' . esc_attr( $url->title ) . '</news:title>';
		echo '</news:news>';
		$this->renderImages( $url->images );
		echo "</url>\n";
	}

	public function renderIndex( $urls ): void {
		return;
	}

	public function renderPages( $urls ): void {
		$siteName = get_option( 'blogname' );
		$urlXls = xsgPluginPath() . '/assets/SitemapXMLnews.xsl';
		ob_get_clean();
		ob_start();
		header('Content-Type: text/xml; charset=utf-8');
		echo '<?xml version="1.0" encoding="UTF-8" ?>';
		echo "\n";
		echo '<?xml-stylesheet type="text/xsl" href="' . esc_url_raw( $urlXls ) . '"?>';
		echo "\n";
		echo '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';
		echo  "\n";
		if ( isset( $urls ) ) {
			foreach ( $urls as $url ) {
				$this->renderItem( $siteName, $url );
			}
		}
		echo "\n";
		echo '</urlset>';
		echo "\n";
		ob_end_flush();
	}
}
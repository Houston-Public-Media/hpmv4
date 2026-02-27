<?php
namespace hpmSitemap;
class HtmRenderer extends RendererCore implements ISitemapRenderer {
	private function renderItem( $url ): void {
		echo '<li><a href="' . esc_url( $url->location ) . '">' . esc_attr( $url->title ) . '</a></li>' . "\n";
	}

	public function renderIndex( $urls ): void {
		$this->doRender( "Wordpress HTML Sitemap Index", $urls );
	}
	public function renderPages( $urls ): void {
		$this->doRender( "Wordpress HTML Sitemap", $urls );
	}
	public function doRender( $title, $urls ): void {
		ob_get_clean();
		header( 'Content-Type: text/html; charset=utf-8' );
		ob_start();
		$this->renderHeader( $title );
		echo "<ul>\n";
		if ( isset( $urls ) ) {
			foreach ( $urls as $url ) {
				$this->renderItem( $url );
			}
			echo "</ul>\n";
		}
		$this->renderFooter();
		echo "\n";
		ob_end_flush();
	}

	function renderHeader( $title ): void { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!--Created using XmlSitemapGenerator.org WordPress Plugin - Free HTML, RSS and XML sitemap generator -->
	<head>
		<title>WordPress Sitemap Generator</title>
		<meta id="MetaDescription" name="description" content="WordPress Sitemap created using XmlSitemapGenerator.org - the free online Google XML sitemap generator" />
		<meta id="MetaKeywords" name="keywords" content="XML, HTML, Sitemap Generator, Wordpress" />
		<meta content="Xml Sitemap Generator .org" name="Author" />
		<style>
			body { font-family: Tahoma, Verdana, Arial, sans-serif; font-size: 1.0em; line-height: 2em; }
			#header { padding:0; margin-top: 10px; margin-bottom: 20px; }
			a { text-decoration: none; color: blue; }
		</style>
	</head>
	<body>
		<h1><?php echo esc_attr( $title ); ?></h1>
		<div id="header">
			<p>This is an HTML Sitemap which helps search engines find all your pages.<br />This is useful for search engines and spiders that do not support the XML Sitemap format.</p>
		</div><?php
	}
	static function renderFooter() { ?>
	</body>
</html><?php
	}
}
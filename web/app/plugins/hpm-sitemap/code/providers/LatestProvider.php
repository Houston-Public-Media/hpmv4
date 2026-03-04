<?php
namespace hpmSitemap;

class LatestProvider extends ProviderCore implements ISitemapProvider {
	public int $maxPageSize = -1;

	public function getSuppportedTypes (): array {
		return [ "archive" ];
	}

	public function getPageCount ( $type ): int {
		return 1;
	}

	public function getPage ( $page, $pageSize ): array {
		global $wpdb;
		$date = self::getDateField( $this->sitemapDefaults->dateField );
		$postTypes = self::getPostTypes();
		$frontPageId = get_option( 'page_on_front' );

		// for latest we have a fixed page only.
		$thisPageSize = 50;
		$offset = 0;

		$cmd = "SELECT posts.*, UNIX_TIMESTAMP({$date}) as sitemapDate
			FROM {$wpdb->posts} as posts 
			WHERE (post_status = 'publish'  ) AND ( post_type = 'post' {$postTypes})   
			AND posts.post_password = '' AND posts.ID <> {$frontPageId}
			ORDER BY {$date} DESC
			LIMIT {$offset},  {$thisPageSize} ";

		$results = $wpdb->get_results( $cmd );
		if ( $results ) {
			$this->doPopulate( $results );
		}
		return $this->urlsList;
	}

	private function getPostTypes (): string {
		$args = [
			'public' => true,
			'_builtin' => false
		];
		$output = 'names'; // 'names' or 'objects' (default: 'names')
		$operator = 'and'; // 'and' or 'or' (default: 'and')

		$post_types = get_post_types( $args, $output, $operator );
		$postTypes = "";
		foreach ( $post_types as $post_type ) {
			$postTypes .= " OR post_type = '{$post_type}'";
		}
		return $postTypes;
	}

	private function doPopulate ( $results ): void {
		foreach ( $results as $result ) {
			$defaults = self::postTypeDefault( $this->sitemapDefaults, $result->post_type );
			if ( $result->post_status == 'future' && $defaults->scheduled == 0 ) {
				continue;
			}
			$pageUrl = get_permalink( $result );
			if ( !( $this->isIncluded( $pageUrl, $this->sitemapDefaults->excludeRules ) ) ) {
				continue;
			}
			$url = new MapItem();
			$url->location = $pageUrl;
			$url->title = get_the_title( $result );
			$url->description = $result->post_excerpt;
			$url->modified = $result->sitemapDate;
			$this->addUrls( 0, $url );

		}
	}

	static function postTypeDefault ( $sitemapDefaults, $name ) {
		if ( $name == 'page' ) {
			return $sitemapDefaults->pages;
		} elseif ( $name == 'post' ) {
			return $sitemapDefaults->posts;
		} else {
			return ( $sitemapDefaults->{$name} ?? $sitemapDefaults->posts );
		}
	}
}
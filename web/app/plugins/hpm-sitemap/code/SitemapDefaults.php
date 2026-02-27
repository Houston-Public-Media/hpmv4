<?php

namespace hpmSitemap;

// settings for generating a map

use stdClass;

class SitemapDefaults {
	function __construct() {
		//($exclude1 = 1,$priority1 = 1,$frequency1 = 1, $inherit = 0)
		$this->homepage = new MetaSettings( 3, 12, 6, 0 );
		$this->pages = new MetaSettings( 3, 8, 4, 0 );
		$this->posts = new MetaSettings( 3, 8, 4, 0 );
		$this->taxonomyCategories = new MetaSettings( 3, 5, 5, 0 );
		$this->taxonomyTags = new MetaSettings( 3, 5, 5, 0 );
		$this->recentArchive = new MetaSettings( 3, 8, 7, 0 );
		$this->oldArchive = new MetaSettings( 3, 5, 3, 0 );
		$this->authors = new MetaSettings( 3, 5, 5, 0 );
	}
	public function __set( string $name, MetaSettings $value ): void {
		$this->customPosts[ $name ] = $value;
	}

	// Handle dynamic property retrieval
	public function __get( string $name ): ?MetaSettings {
		return $this->customPosts[ $name ] ?? null;
	}

	public MetaSettings $homepage;
	public MetaSettings $pages;
	public MetaSettings $posts;
	public MetaSettings $taxonomyCategories;
	public MetaSettings $taxonomyTags;
	public MetaSettings $recentArchive;
	public MetaSettings $oldArchive;
	public MetaSettings $authors;
	public string $dateField = "updated"; // date field for sitemap can be updated or created date.
	public string $excludeRules = "";
	private array $customPosts = [];
}


<?php
namespace hpmSitemap;

class MetaSettings {
	function __construct ( $exclude1 = 1, $priority1 = 1, $frequency1 = 1, $inherit1 = 0, $news1 = 0 ) {
		$this->exclude = $exclude1;
		$this->priority = $priority1;
		$this->frequency = $frequency1;
		$this->inherit = $inherit1;
		$this->news = $news1;
	}

	public int $id = 0;
	public int $itemId = 0;
	public string $itemType = "";
	public int $exclude = 1;
	public int $priority = 1;
	public int $frequency = 1;
	public int $inherit = 0;
	public int $scheduled = 0;
	public int $news = 0;
}
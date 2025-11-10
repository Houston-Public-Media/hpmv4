<?php
function hpm_google_tracker(): void {
	global $wp_query;
?>	<script async="async" src='https://www.googletagservices.com/tag/js/gpt.js'></script>
		<script>
			var googletag = googletag || {};
			googletag.cmd = googletag.cmd || [];
			googletag.cmd.push(function() {
				googletag.defineSlot('/9147267/HPM_Kids_Sidebar', [300, 250], 'div-gpt-ad-1467299583216-0').addService(googletag.pubads());
				googletag.defineSlot('/9147267/HPM_Kids_Sidebar', [300, 250], 'div-gpt-ad-1467299583216-1').addService(googletag.pubads());
				googletag.defineSlot('/9147267/HPM_Kids_Sidebar', [300, 250], 'div-gpt-ad-1467299583216-2').addService(googletag.pubads());
				googletag.defineSlot('/9147267/HPM_Kids_Sidebar', [300, 250], 'div-gpt-ad-1467299583216-3').addService(googletag.pubads());
				var dfpWide = window.innerWidth;
				var dfpUnderNav = document.getElementById('div-gpt-ad-1488818411584-0');
				if (dfpUnderNav !== null) {
					if (dfpWide > 1000) {
						googletag.defineSlot('/9147267/HPM_Under_Nav', [970, 50], 'div-gpt-ad-1488818411584-0').addService(googletag.pubads());
						dfpUnderNav.style.width = '970px';
					} else if (dfpWide <= 1000 && dfpWide > 730) {
						googletag.defineSlot('/9147267/HPM_Under_Nav', [728, 90], 'div-gpt-ad-1488818411584-0').addService(googletag.pubads());
						dfpUnderNav.style.width = '728px';
					} else if (dfpWide <= 730) {
						googletag.defineSlot('/9147267/HPM_Under_Nav', [320, 50], 'div-gpt-ad-1488818411584-0').addService(googletag.pubads());
						dfpUnderNav.style.width = '320px';
					}
				}
				googletag.defineSlot('/9147267/HPM_Music_Sidebar', [300, 250], 'div-gpt-ad-1470409396951-0').addService(googletag.pubads());
				googletag.defineSlot('/9147267/HPM_Bauer_Business_Focus', [300, 250], 'div-gpt-ad-1759329378296-0').addService(googletag.pubads());
<?php
	if ( is_home() ) { ?>
				let mobileGdc = document.querySelectorAll('.homepage-mobile-gdc');
				let desktopGdc = document.querySelectorAll('.homepage-desktop-gdc');
				if ( dfpWide < 1000 ) {
					Array.from(mobileGdc).forEach((gdc) => {
						gdc.classList.remove('hidden');
					});
				} else {
					Array.from(desktopGdc).forEach((gdc) => {
						gdc.classList.remove('hidden');
					});
				}
				googletag.defineSlot('/9147267/HPM_Support_Sidebar', [300, 250], 'div-gpt-ad-1394579228932-3').addService(googletag.pubads());
				googletag.defineSlot('/9147267/HPM_Support_Sidebar', [300, 250], 'div-gpt-ad-1394579228932-4').addService(googletag.pubads());
<?php
	} ?>
				googletag.defineSlot('/9147267/HPM_Support_Sidebar', [300, 250], 'div-gpt-ad-1394579228932-1').addService(googletag.pubads());
				googletag.defineSlot('/9147267/HPM_Support_Sidebar', [300, 250], 'div-gpt-ad-1394579228932-2').addService(googletag.pubads());
				googletag.defineSlot('/9147267/HPM_About_300x250', [300, 250], 'div-gpt-ad-1579034137004-0').addService(googletag.pubads());
				googletag.pubads().addEventListener('slotRenderEnded', function(event) {
					var slotId = event.slot.getSlotElementId();
					if (event.isEmpty) {
						var sideBar = document.getElementById(slotId).parentNode;
						if (sideBar.classList.contains('sidebar-ad')) {
							sideBar.style.cssText += "display: none;";
						}
					}
				});
<?php
	if ( is_home() ) {
		echo "\t\t\t\tgoogletag.pubads().setTargeting('section', 'homepage');\n";
	} elseif ( is_archive() ) {
		if ( !empty( $wp_query->query_vars['category_name'] ) ) {
			echo "\t\t\t\tgoogletag.pubads().setTargeting('category', '" . $wp_query->query_vars['category_name'] . "');\n";
		} elseif ( !empty( $wp_query->query_vars['tag'] ) ) {
			echo "\t\t\t\tgoogletag.pubads().setTargeting('tag', '" . $wp_query->query_vars['tag'] . "');\n";
		}
	} elseif ( is_single() ) {
		if ( get_post_type() == 'shows' ) {
			$cat_no = get_post_meta( get_the_ID(), 'hpm_shows_cat', true );
			if ( !empty( $cat_no ) ) {
				$category = get_term( $cat_no );
				if ( !empty( $category ) ) {
					echo "\t\t\t\tgoogletag.pubads().setTargeting('category', '" . $category->slug . "');\n";
				}
			}
		} else {
			$classes = get_post_class( '', $wp_query->queried_object_id );
			$category = $tag = [];
			foreach ( $classes as $class ) {
				if ( str_contains( $class, 'category-' ) ) {
					$category[] = str_replace( 'category-', '', $class );
				} elseif ( str_contains( $class, 'tag-' ) ) {
					$tag[] = str_replace( 'tag-', '', $class );
				}
			}
			if ( !empty( $category ) || !empty( $tag ) ) {
				echo "\t\t\t\tgoogletag.pubads()";
				if ( !empty( $category ) ) {
					echo ".setTargeting('category', ['" . implode( "','", $category ) . "'])";
				}
				if ( !empty( $tag ) ) {
					echo ".setTargeting('tag', ['" . implode( "','", $tag ) . "'])";
				}
				echo ";\n";
			}
		}
	} elseif ( get_post_type() == 'show' ) {
		echo "\t\t\t\tgoogletag.pubads().setTargeting('category', '" . $wp_query->query_vars['category_name'] . "');\n";
	}?>
				googletag.enableServices();
			});
		</script>
<?php
}
add_action( 'wp_head', 'hpm_google_tracker', 100 );
add_filter( 'gtm_post_category', 'gtm_populate_category_items', 12, 3 );
add_filter( 'gtm_post_tags', 'gtm_populate_tag_items', 12, 3 );
add_filter( 'gtm_story_id', 'gtm_populate_story_id', 12, 3 );
add_filter( 'gtm_permalink', 'gtm_populate_permalink', 12, 3 );
add_filter( 'gtm_author_name', 'gtm_populate_authors', 12, 3 );

function gtm_populate_category_items( $total_match, $match, $post_id ): array|WP_Error|string {
	$terms = wp_get_object_terms( $post_id, 'category', [ 'fields' => 'names' ] );
	if ( is_wp_error( $terms ) || empty( $terms ) ) {
		return '';
	}
	return $terms;
}

function gtm_populate_tag_items( $total_match, $match, $post_id ): array|WP_Error|string {
	$terms = wp_get_object_terms( $post_id, 'post_tag', [ 'fields' => 'names' ] );
	if ( is_wp_error( $terms ) || empty( $terms ) ) {
		return '';
	}
	return $terms;
}

function gtm_populate_story_id( $total_match, $match, $post_id ) {
	return get_post_meta( $post_id, 'npr_story_id', true );
}

function gtm_populate_permalink( $total_match, $match, $post_id ): bool|string {
	return get_permalink( $post_id );
}

function gtm_populate_authors( $total_match, $match, $post_id ): array {
	$coauthors = get_coauthors( $post_id );
	$authors = [];
	foreach ( $coauthors as $coa ) {
		$authors[] = $coa->display_name;
	}
	return $authors;
}

add_action( 'wp_head', 'hpm_google_conversion', 101 );

function hpm_google_conversion(): void {
	if ( !empty( $_GET['google_ad'] ) && $_GET['google_ad'] == 'convert' ) {
		echo "<!-- Event snippet for Outbound click conversion page --> <script> gtag('event', 'conversion', {'send_to': 'AW-10777328260/yyJICKjnw_YCEIT1g5Mo'}); </script>";
	}
}
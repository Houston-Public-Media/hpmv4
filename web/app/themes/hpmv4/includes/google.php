<?php
function hpm_google_tracker() {
	global $wp_query;
?>	<script async="true" src='https://www.googletagservices.com/tag/js/gpt.js'></script>
		<script>
			var googletag = googletag || {};
			googletag.cmd = googletag.cmd || [];
			googletag.cmd.push(function() {
				googletag.defineSlot('/9147267/HPM_Kids_Sidebar', [300, 250], 'div-gpt-ad-1467299583216-0').addService(googletag.pubads());
				googletag.defineSlot('/9147267/HPM_Kids_Sidebar', [300, 250], 'div-gpt-ad-1467299583216-1').addService(googletag.pubads());
				googletag.defineSlot('/9147267/HPM_Kids_Sidebar', [300, 250], 'div-gpt-ad-1467299583216-2').addService(googletag.pubads());
				googletag.defineSlot('/9147267/HPM_Kids_Sidebar', [300, 250], 'div-gpt-ad-1467299583216-3').addService(googletag.pubads());
				var dfpWide = window.innerWidth;
				if ( dfpWide > 1000 ) {
					googletag.defineSlot('/9147267/HPM_Under_Nav', [970, 50], 'div-gpt-ad-1488818411584-0').addService(googletag.pubads());
					document.getElementById('div-gpt-ad-1488818411584-0').style.width = '970px';
				}
				else if ( dfpWide <= 1000 && dfpWide > 730 ) {
					googletag.defineSlot('/9147267/HPM_Under_Nav', [728, 90], 'div-gpt-ad-1488818411584-0').addService(googletag.pubads());
					document.getElementById('div-gpt-ad-1488818411584-0').style.width = '728px';
				}
				else if ( dfpWide <= 730 ) {
					googletag.defineSlot('/9147267/HPM_Under_Nav', [320, 50], 'div-gpt-ad-1488818411584-0').addService(googletag.pubads());
					document.getElementById('div-gpt-ad-1488818411584-0').style.width = '320px';
				}
				googletag.defineSlot('/9147267/HPM_Music_Sidebar', [300, 250], 'div-gpt-ad-1470409396951-0').addService(googletag.pubads());
				googletag.defineSlot('/9147267/HPM_Support_Sidebar', [300, 250], 'div-gpt-ad-1394579228932-1').addService(googletag.pubads());
				googletag.defineSlot('/9147267/HPM_Support_Sidebar', [300, 250], 'div-gpt-ad-1394579228932-2').addService(googletag.pubads());
				//googletag.pubads().collapseEmptyDivs();
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
		$classes = get_post_class( '', $wp_query->queried_object_id );
		$category = $tag = [];
		foreach ( $classes as $class ) {
			if ( strpos( $class, 'category-' ) !== false ) {
				$category[] = str_replace( 'category-', '', $class );
			} elseif ( strpos( $class, 'tag-' ) !== false ) {
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
	} ?>
				googletag.enableServices();
			});
		</script>
		<script>addEventListener('error', window.__e=function f(e){f.q=f.q||[];f.q.push(e)});</script>
		<script async="true" src='https://www.google-analytics.com/analytics.js'></script>
<?php
}
add_action( 'wp_head', 'hpm_google_tracker', 100 );
add_filter( 'gtm_post_category', 'gtm_populate_category_items', 10, 3 );

function gtm_populate_category_items( $total_match, $match, $post_id ) {
	$terms = wp_get_object_terms( $post_id, 'category', [ 'fields' => 'slugs' ] );
	if ( is_wp_error( $terms ) || empty( $terms ) ) :
		return '';
	endif;
	return $terms;
}

add_action( 'wp_head', 'hpm_google_conversion', 101 );

function hpm_google_conversion() {
	if ( !empty( $_GET['google_ad'] ) && $_GET['google_ad'] == 'convert' ) {
		echo "<!-- Event snippet for Outbound click conversion page --> <script> gtag('event', 'conversion', {'send_to': 'AW-10777328260/yyJICKjnw_YCEIT1g5Mo'}); </script>";
	}
}
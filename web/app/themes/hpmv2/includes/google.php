<?php
function hpm_google_tracker() {
?>		<script async='async' src='https://www.googletagservices.com/tag/js/gpt.js'></script>
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
<?php if ( is_home() ) : ?>
				googletag.pubads().setTargeting('section', 'homepage');
<?php endif; ?>
				googletag.enableServices();
			});
			window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;
			ga('create', 'UA-3106036-9', 'auto');
			ga('create', 'UA-3106036-11', 'auto', 'hpmRollup' );
			var custom_vars = {
				nid: {name: "nid", slot: 8, scope_id: 3},
				pop: {name: "pop", slot: 9, scope_id: 3},
				author: {name: "author", slot: 11, scope_id: 3},
				keywords: {name: "tags", slot: 12, scope_id: 3},
				org_id: {name: "org_id", slot: 13, scope_id: 3},
				brand: {name: "CP_Station", slot: 14, scope_id: 2},
				has_audio: {name: "Has_Inline_Audio", slot: 15, scope_id: 3},
				programs: {name: "Program", slot: 16, scope_id: 3},
				category: {name: "Category", slot: 10, scope_id: 3},
				datePublished: {name: "PublishedDate", slot: 17, scope_id: 3},
				wordCount: {name: "WordCount", slot: 18, scope_id: 3},
				story_id: {name: "API_Story_Id", slot: 19, scope_id: 3},
				pmp_guid: {name: "pmp_guid", slot: 20, scope_id: 3}
			};
			metadata = document.getElementsByTagName("meta");
			// no metadata then no custom variables
			if (metadata.length > 0) {
				for (var k = 0; k < metadata.length; k++) {
					if (metadata[k].content !== "") {
						if (custom_vars[metadata[k].name]) {
							if (metadata[k].name === 'keywords' && metadata[k].content.length > 150) {
								var tagString = escape(metadata[k].content);
								var comma = tagString.lastIndexOf('%2C', 150);
								var tag = tagString.substring( comma-5, comma );
								var short = metadata[k].content.substring( 0, metadata[k].content.lastIndexOf( tag, 150 ) + 5 );
								ga('set', "dimension" + custom_vars[metadata[k].name]["slot"], short );
								ga('hpmRollup.set', "dimension" + custom_vars[metadata[k].name]["slot"], short );
							} else {
								ga('set', "dimension" + custom_vars[metadata[k].name]["slot"], metadata[k].content );
								ga('hpmRollup.set', "dimension" + custom_vars[metadata[k].name]["slot"], metadata[k].content );
							}

						}
					}
				}
			}
			ga('send', 'pageview');
			ga('hpmRollup.send', 'pageview');
			function hpmKimbiaComplete(kimbiaData) {
				var charge = kimbiaData['initialCharge'];
				var amount = Number(charge.replace(/[^0-9\.]+/g,""));
				fbq( 'track', 'Purchase', { value: amount, currency: 'USD' } );
				ga('send', 'event', { eventCategory: 'Button', eventAction: 'Submit', eventLabel: 'Donation', eventValue: amount });
			}
		</script>
		<script async src='https://www.google-analytics.com/analytics.js'></script>
<?php
}

add_filter( 'gtm_post_category', 'gtm_populate_category_items', 10, 3 );

function gtm_populate_category_items( $total_match, $match, $post_id ) {
	$terms = wp_get_object_terms( $post_id, 'category', [ 'fields' => 'slugs' ] );
	if ( is_wp_error( $terms ) || empty( $terms ) ) :
		return '';
	endif;
	return $terms;
}
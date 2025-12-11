<?php
	if ( ! defined( 'ABSPATH' ) ) exit;
	global $hpm_podcast_prod, $hpm_podcast_link, $hpm_podcast_cat; ?>
<h3><?php _e( "Featured Podcast", 'hpm-podcasts' ); ?></h3>
<p><strong><?php _e( "Is this podcast being produced internally, featured from an external source, or is it an aggregate feed?", 'hpm-podcasts' ); ?></strong><br />
	<label for="hpm-podcast-prod"><?php _e( "Production:", 'hpm-podcasts' ); ?></label> <select name="hpm-podcast-prod" id="hpm-podcast-prod">
		<option value="internal"<?php selected( $hpm_podcast_prod, 'internal' ); ?>><?php _e( "Internal", 'hpm-podcasts' ); ?></option>
		<option value="external"<?php selected( $hpm_podcast_prod, 'external' ); ?>><?php _e( "External", 'hpm-podcasts' ); ?></option>
		<option value="aggregate"<?php selected( $hpm_podcast_prod, 'aggregate' ); ?>><?php _e( "Aggregate", 'hpm-podcasts' ); ?></option>
	</select>
</p>
<div class="hpm-podcast-external hidden">
<p><strong><?php _e( "If externally produced/hosted, enter the RSS feed link below", 'hpm-podcasts' );
?></strong><br />
<label for="hpm-podcast-rss-override"><?php _e( "URL:", 'hpm-podcasts' ); ?></label> <input type="text" id="hpm-podcast-rss-override" name="hpm-podcast-rss-override" value="<?php echo $hpm_podcast_link['rss-override']; ?>" placeholder="http://example.com/law-blog-with-bob-loblaw/" style="width: 60%;" /></p>
</div>
<div class="hpm-podcast-aggregate hidden">
	<h3>Aggregate Feed Settings</h3>
	<p>An aggregate feed is a podcast feed that contains all of the episodes of selected shows, but treats each show as an individual season.</p>
	<p>This allows you to create a catch-all feed for groups of projects, while maintaining individual feeds for each one.</p>
	<p>In order to create an aggregate feed, check the boxes next to the feeds you want to include. After that, you can drag-and-drop them in the order you want them.</p>
	<div id="hpm-podcast-aggregate-settings">
		<ul>
<?php
	$agg_pods = new WP_Query([
		'post_type' => 'podcasts',
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'meta_query' => [[
			'key' => 'hpm_pod_prod',
			'compare' => '=',
			'value' => 'internal'
		]]
	]);
	$agg_pod_list = [];
	if ( $agg_pods->have_posts() ) {
		while ( $agg_pods->have_posts() ) {
			$agg_pods->the_post();
			$agg_id = get_the_ID();
			$agg_pod_list[ $agg_id ] = [
				'name' => 'hpm-podcasts-aggregate[' . $agg_id . ']',
				'title' => get_the_title()
			];
		}
	}
	wp_reset_query();
	if ( !empty( $hpm_podcast_link['aggregate_feed'] ) ) {
		$agg_pod_temp = [];
		foreach ( $hpm_podcast_link['aggregate_feed'] as $agf ) {
			$agg_pod_temp[ $agf ] = $agg_pod_list[ $agf ];
			unset( $agg_pod_list[ $agf ] );
		}
		foreach ( $agg_pod_list as $aplk => $aplv ) {
			$agg_pod_temp[ $aplk ] = $aplv;
		}
		$agg_pod_list = $agg_pod_temp;
	} else {
		$hpm_podcast_link['aggregate_feed'] = [];
	}
	foreach ( $agg_pod_list as $aplk => $aplv ) {
		$checked = in_array( $aplk, $hpm_podcast_link['aggregate_feed'] ); ?>
			<li draggable="true" class="sortable">
				<div class="updown"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><path d="M145.6 7.7C141 2.8 134.7 0 128 0s-13 2.8-17.6 7.7l-104 112c-6.5 7-8.2 17.2-4.4 25.9S14.5 160 24 160H80V352H24c-9.5 0-18.2 5.7-22 14.4s-2.1 18.9 4.4 25.9l104 112c4.5 4.9 10.9 7.7 17.6 7.7s13-2.8 17.6-7.7l104-112c6.5-7 8.2-17.2 4.4-25.9s-12.5-14.4-22-14.4H176V160h56c9.5 0 18.2-5.7 22-14.4s2.1-18.9-4.4-25.9l-104-112z"/></svg></div>
				<p><input type="checkbox" id="<?php echo $aplv['name']; ?>" name="<?php echo $aplv['name']; ?>" <?php checked( $checked ) ?>/> <label for="<?php echo $aplv['name']; ?>"><?php echo $aplv['title']; ?></label></p>
			</li>
<?php
	}
	?>
		</ul>
	</div>
</div>
<p>&nbsp;</p>
<?php
	$itunes_cats = [
		'Arts' => [
			'Books',
			'Design',
			'Fashion & Beauty',
			'Food',
			'Performing Arts',
			'Visual Arts'
		],
		'Business' => [
			'Careers',
			'Entrepreneurship',
			'Investing',
			'Management',
			'Marketing',
			'Non-Profit'
		],
		'Comedy' => [
			'Comedy Interviews',
			'Improv',
			'Stand-Up'
		],
		'Education' => [
			'Courses',
			'How To',
			'Language Learning',
			'Self-Improvement'
		],
		'Fiction' => [
			'Comedy Fiction',
			'Drama',
			'Science Fiction'
		],
		'Government' => [],
		'History' => [],
		'Health & Fitness' => [
			'Alternative Health',
			'Fitness',
			'Medicine',
			'Mental Health',
			'Nutrition',
			'Sexuality'
		],
		'Kids & Family' => [
			'Education for Kids',
			'Parenting',
			'Pets & Animals',
			'Stories for Kids'
		],
		'Leisure' => [
			'Animation & Manga',
			'Automotive',
			'Aviation',
			'Crafts',
			'Games',
			'Hobbies',
			'Home & Garden',
			'Video Games'
		],
		'Music' => [
			'Music Commentary',
			'Music History',
			'Music Interviews'
		],
		'News' => [
			'Business News',
			'Daily News',
			'Entertainment News',
			'News Commentary',
			'Politics',
			'Sports News',
			'Tech News'
		],
		'Religion & Spirituality' => [
			'Buddhism',
			'Christianity',
			'Hinduism',
			'Islam',
			'Judaism',
			'Religion',
			'Spirituality'
		],
		'Science' => [
			'Astronomy',
			'Chemistry',
			'Earth Sciences',
			'Life Sciences',
			'Mathematics',
			'Natural Sciences',
			'Nature',
			'Physics',
			'Social Sciences'
		],
		'Society & Culture' => [
			'Documentary',
			'Personal Journals',
			'Philosophy',
			'Places & Travel',
			'Relationships'
		],
		'Sports' => [
			'Baseball',
			'Basketball',
			'Cricket',
			'Fantasy Sports',
			'Football',
			'Golf',
			'Hockey',
			'Rugby',
			'Running',
			'Soccer',
			'Swimming',
			'Tennis',
			'Volleyball',
			'Wilderness',
			'Wrestling'
		],
		'Technology' => [],
		'True Crime' => [],
		'TV & Film' => [
			'After Shows',
			'Film History',
			'Film Interviews',
			'Film Reviews',
			'TV Reviews'
		]
	]; ?>
<h3><?php _e( "Category and Page", 'hpm-podcasts' ); ?></h3>
<div class="hpm-podcast-internal hidden">
<p><?php _e( "Select the post category for this podcast:", 'hpm-podcasts' );
	wp_dropdown_categories([
		'show_option_all'	=> __( "Select One" ),
		'taxonomy'			=> 'category',
		'name'				=> 'hpm-podcast-cat',
		'orderby'			=> 'name',
		'selected'			=> $hpm_podcast_cat,
		'hierarchical'		=> true,
		'depth'				=> 3,
		'show_count'		=> false,
		'hide_empty'		=> false
	]); ?></p>
</div>
<p><strong><?php _e( "Enter the page URL for this podcast (show page or otherwise)", 'hpm-podcasts' );
?></strong><br />
<label for="hpm-podcast-link"><?php _e( "URL:", 'hpm-podcasts' ); ?></label> <input type="text" id="hpm-podcast-link" name="hpm-podcast-link" value="<?php echo $hpm_podcast_link['page']; ?>" placeholder="https://example.com/law-blog-with-bob-loblaw/" style="width: 60%;" /></p>
<div class="hpm-podcast-internal hidden">
	<p><strong><?php _e( "How many episodes do you want to show in the feed? (Enter a 0 to display all)", 'hpm-podcasts' ); ?></strong><br />
	<label for="hpm-podcast-limit"><?php _e( "Number of Eps:", 'hpm-podcasts' ); ?></label> <input type="number" id="hpm-podcast-limit" name="hpm-podcast-limit" value="<?php echo $hpm_podcast_link['limit']; ?>" placeholder="0" style="width: 30%;" /></p>

	<p><strong><?php _e( "Is it an episodic podcast or a serialized one?", 'hpm-podcasts' ); ?></strong><br />
		<label for="hpm-podcast-type"><?php _e( "Podcast Type:", 'hpm-podcasts' ); ?></label> <select name="hpm-podcast-type" id="hpm-podcast-type">
			<option value="episodic"<?php selected( $hpm_podcast_link['type'], 'episodic' ); ?>><?php _e( "Episodic", 'hpm-podcasts' ); ?></option>
			<option value="serial"<?php selected( $hpm_podcast_link['type'], 'serial' ); ?>><?php _e( "Serialized", 'hpm-podcasts' ); ?></option>
		</select>
	</p>
</div>
<p>&nbsp;</p>

<h3><?php _e( "iTunes Categories", 'hpm-podcasts' ); ?></h3>
<p><?php _e( "iTunes allows you to select up to 3 category/subcategory combinations.  **The primary category is required, and is what will display in iTunes.**", 'hpm-podcasts' ); ?></p>
<ul>
<?php
	foreach ( $hpm_podcast_link['categories'] as $pos => $cat ) { ?>
	<li>
		<label for="hpm-podcast-icat-<?php echo $pos; ?>"><?php _e( ucwords( $pos )." Category:", 'hpm-podcasts' );
		?></label>
		<select name="hpm-podcast-icat-<?php echo $pos; ?>" id="hpm-podcast-icat-<?php echo $pos; ?>">
			<option value=""<?php selected( $cat, '' ); ?>><?php _e( "Select One", 'hpm-podcasts' ); ?></option>
<?php
		foreach ( $itunes_cats as $it_cat => $it_sub ) { ?>
			<option value="<?php echo $it_cat; ?>"<?php selected( $cat, $it_cat ); ?>><?php _e( $it_cat, 'hpm-podcasts' ); ?></option>
<?php
			if ( !empty( $it_sub ) ) {
				foreach ( $it_sub as $sub ) {
				$cat_sub = $it_cat.'||'.$sub; ?>
				<option value="<?php echo $cat_sub; ?>"<?php selected( $cat, $cat_sub ); ?>><?php _e( $it_cat." > ".$sub, 'hpm-podcasts' ); ?></option>
<?php
				}
			}
		}
?>
		</select>
	</li>
<?php
	} ?>
</ul>
<p>&nbsp;</p>
<h3><?php _e( "External Services", 'hpm-podcasts' ); ?></h3>
<p><label for="hpm-podcast-link-itunes"><?php _e( "Apple Podcasts:", 'hpm-podcasts' ); ?></label> <input type="text" id="hpm-podcast-link-itunes" name="hpm-podcast-link-itunes" value="<?php echo $hpm_podcast_link['itunes']; ?>" placeholder="https://podcasts.apple.com/us/podcast/law-blog-with-bob-loblaw/id123456789?mt=2" style="width: 60%;" /></p>
<p><label for="hpm-podcast-link-npr"><?php _e( "NPR:", 'hpm-podcasts' ); ?></label> <input type="text" id="hpm-podcast-link-npr" name="hpm-podcast-link-npr" value="<?php echo !empty( $hpm_podcast_link['npr'] ) ? $hpm_podcast_link['npr'] : ''; ?>" placeholder="https://app.npr.org/aggregation/PODCASTID" style="width: 60%;" /></p>
<p><label for="hpm-podcast-link-youtube"><?php _e( "YouTube:", 'hpm-podcasts' ); ?></label> <input type="text" id="hpm-podcast-link-youtube" name="hpm-podcast-link-youtube" value="<?php echo !empty( $hpm_podcast_link['youtube'] ) ? $hpm_podcast_link['youtube'] : ''; ?>" placeholder="https://youtube.com/blahblahblah" style="width: 60%;" /></p>
<p><label for="hpm-podcast-link-spotify"><?php _e( "Spotify:", 'hpm-podcasts' ); ?></label> <input type="text" id="hpm-podcast-link-spotify" name="hpm-podcast-link-spotify" value="<?php echo $hpm_podcast_link['spotify']; ?>" placeholder="https://spotify.com/blah" style="width: 60%;" /></p>
<p><label for="hpm-podcast-link-tunein"><?php _e( "TuneIn:", 'hpm-podcasts' ); ?></label> <input type="text" id="hpm-podcast-link-tunein" name="hpm-podcast-link-tunein" value="<?php echo $hpm_podcast_link['tunein']; ?>" placeholder="https://tun.in/abcde" style="width: 60%;" /></p>
<p><label for="hpm-podcast-link-iheart"><?php _e( "iHeart:", 'hpm-podcasts' ); ?></label> <input type="text" id="hpm-podcast-link-iheart" name="hpm-podcast-link-iheart" value="<?php echo $hpm_podcast_link['iheart']; ?>" placeholder="https://iheart.com/abcde" style="width: 60%;" /></p>
<p><label for="hpm-podcast-link-pandora"><?php _e( "Pandora:", 'hpm-podcasts' ); ?></label> <input type="text" id="hpm-podcast-link-pandora" name="hpm-podcast-link-pandora" value="<?php echo $hpm_podcast_link['pandora']; ?>" placeholder="https://pandora.com/abcde" style="width: 60%;" /></p>
<p><label for="hpm-podcast-link-pcast"><?php _e( "Pocket Casts:", 'hpm-podcasts' ); ?></label> <input type="text" id="hpm-podcast-link-pcast" name="hpm-podcast-link-pcast" value="<?php echo $hpm_podcast_link['pcast']; ?>" placeholder="https://pca.st/blah" style="width: 60%;" /></p>
<p><label for="hpm-podcast-link-overcast"><?php _e( "Overcast:", 'hpm-podcasts' ); ?></label> <input type="text" id="hpm-podcast-link-overcast" name="hpm-podcast-link-overcast" value="<?php echo $hpm_podcast_link['overcast']; ?>" placeholder="https://overcast.fm/itunes12345657" style="width: 60%;" /></p>
<p><label for="hpm-podcast-link-amazon"><?php _e( "Amazon Music:", 'hpm-podcasts' ); ?></label> <input type="text" id="hpm-podcast-link-amazon" name="hpm-podcast-link-amazon" value="<?php echo $hpm_podcast_link['amazon']; ?>" placeholder="https://music.amazon.com/podcasts/abcde" style="width: 60%;" /></p>
<p><label for="hpm-podcast-link-podping"><?php _e( "Podping ID:", 'hpm-podcasts' ); ?></label> <input type="text" id="hpm-podcast-link-podping" name="hpm-podcast-link-podping" value="<?php echo $hpm_podcast_link['podping']; ?>" placeholder="123456789" style="width: 60%;" /></p>
<script>
	jQuery(document).ready(function($){
		let excerpt = $('#postexcerpt');
		let imageDiv = $('#postimagediv');
		excerpt.find("button .screen-reader-text").text("Toggle panel: Apple Podcasts Subtitle");
		excerpt.find("h2 span").text("Apple Podcasts Subtitle");
		excerpt.find(".inside p").remove();
		imageDiv.find("button .screen-reader-text").text("Toggle panel: Podcast Logo");
		imageDiv.find("h2 span").text("Podcast Logo");
		imageDiv.find(".inside").prepend('<p class="hide-in-no-js howto">Minimum logo resolution for Apple Podcasts etc. is 1400px x 1400px.  Maximum is 3000px x 3000px.</p>');
		$("#postdivrich").prepend('<h1>Podcast Description</h1>');
		let podcastControls = document.querySelectorAll('.hpm-podcast-aggregate,.hpm-podcast-external,.hpm-podcast-internal');
		let podType = document.querySelector('#hpm-podcast-prod');
		let podcastToggle = ( name ) => {
			Array.from(podcastControls).forEach((pC) => {
				if ( pC.classList.contains('hpm-podcast-' + name) ) {
					pC.classList.remove('hidden');
				} else {
					pC.classList.add('hidden');
				}
			});
		};
		podcastToggle('<?php echo $hpm_podcast_prod; ?>');
		podType.addEventListener('change', () => {
			let podVal = podType.value;
			podcastToggle(podVal);
		});
		let details = document.querySelectorAll('#hpm-podcast-aggregate-settings > ul > li');
		let source;

		let isbefore = (a, b) => {
			if (a.parentNode === b.parentNode) {
				for (var cur = a; cur; cur = cur.previousSibling) {
					if (cur === b) {
						return true;
					}
				}
			}
			return false;
		}
		details.forEach((detail) => {
			detail.addEventListener('dragenter', (e) => {
				let targetelem = e.target;
				while (targetelem.nodeName !== "LI") {
					targetelem = targetelem.parentNode;
				}

				if (isbefore(source, targetelem)) {
					targetelem.parentNode.insertBefore(source, targetelem);
				} else {
					targetelem.parentNode.insertBefore(source, targetelem.nextSibling);
				}
			});
			detail.addEventListener('dragstart', (e) => {
				source = e.target;
				e.dataTransfer.effectAllowed = 'move';
				e.dataTransfer.setDragImage(source,0,0);
			});
		});
	});
</script>
<style>
	#hpm-podcast-aggregate-settings > ul > li {
		border: 2px solid transparent;
		position: relative;
	}
	#hpm-podcast-aggregate-settings > ul > li > .updown {
		display: flex;
		position: absolute;
		top: 0;
		left: 0;
		width: 3rem;
		height: 100%;
		align-content: center;
		justify-content: center;
	}
	#hpm-podcast-aggregate-settings > ul > li > .updown svg {
		overflow: visible;
		width: 1rem;
	}
	#hpm-podcast-aggregate-settings > ul > li > .updown svg path {
		fill: #808080;
	}
	#hpm-podcast-aggregate-settings ul li + li {
		margin-top: 1rem;
	}
	#hpm-podcast-aggregate-settings > ul > li.sortable {
		border: 2px dotted #808080;
		padding-left: 3rem;
	}
	#hpm-podcast-aggregate-settings ul li + li {
		margin-top: 1rem;
	}
	#hpm-podcast-aggregate-settings > ul > li.sortable:hover {
		cursor: grab;
	}
</style>
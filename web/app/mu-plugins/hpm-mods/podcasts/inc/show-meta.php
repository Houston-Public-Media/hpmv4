<?php
	if ( empty( $hpm_shows_cat ) ) {
		$hpm_shows_cat = '';
		$top_story = "<p><em>Please select a Show category and click 'Save' or 'Update'</em></p>";
	} else {
		$top = get_post_meta( $object->ID, 'hpm_shows_top', true );
		$top_story = '<label for="hpm-shows-top">Top Story:</label><select name="hpm-shows-top" id="hpm-shows-top"><option value="None">No Top Story</option>';
		$cat = new WP_Query([
			'cat' => $hpm_shows_cat,
			'post_status' => 'publish',
			'posts_per_page' => 25,
			'post_type' => 'post',
			'ignore_sticky_posts' => 1
		]);
		if ( $cat->have_posts() ) {
			while ( $cat->have_posts() ) {
				$cat->the_post();
				$top_story .= '<option value="' . get_the_ID() . '" ' . selected( $top, get_the_ID(), FALSE ) . '>' . get_the_title() . '</option>';
			}
		}
		wp_reset_query();
		$top_story .= '</select><br />';
	} ?>
<h3><?PHP _e( "Show Category", 'hpm-podcasts' ); ?></h3>
<?php
	wp_dropdown_categories([
		'show_option_all' => __("Select One"),
		'taxonomy'        => 'category',
		'name'            => 'hpm-shows-cat',
		'orderby'         => 'name',
		'selected'        => $hpm_shows_cat,
		'hierarchical'    => true,
		'depth'           => 5,
		'show_count'      => false,
		'hide_empty'      => false,
	]); ?>
<h4><?PHP _e( "Which story should appear first?", 'hpm-podcasts' ); ?></h4>
<?php echo $top_story; ?>
<p>&nbsp;</p>
<h3><?PHP _e( "Banner Images", 'hpm-podcasts' ); ?></h3>
<p>Use the buttons below to select your mobile, tablet, and desktop banner images</p>
<?php
	$hpm_mobile_url = $hpm_tablet_url = $hpm_desktop_url = '';
	if ( !empty( $hpm_show_meta['banners']['mobile'] ) ) {
		$hpm_mobile_temp = wp_get_attachment_image_src( $hpm_show_meta['banners']['mobile'], 'medium' );
		$hpm_mobile_url = ' style="background-image: url(' . $hpm_mobile_temp[0] . ')"';
	}
	if ( !empty( $hpm_show_meta['banners']['tablet'] ) ) {
		$hpm_tablet_temp = wp_get_attachment_image_src( $hpm_show_meta['banners']['tablet'], 'medium' );
		$hpm_tablet_url = ' style="background-image: url(' . $hpm_tablet_temp[0] . ')"';
	}
	if ( !empty( $hpm_show_meta['banners']['desktop'] ) ) {
		$hpm_desktop_temp = wp_get_attachment_image_src( $hpm_show_meta['banners']['desktop'], 'medium' );
		$hpm_desktop_url = ' style="background-image: url(' . $hpm_desktop_temp[0] . ')"';
	}
?>
<div class="hpm-show-banner-wrap">
	<div class="hpm-show-banner">
		<div class="hpm-show-banner-image" id="hpm-show-banner-mobile"<?php echo $hpm_mobile_url; ?>></div>
		<button class="hpm-show-banner-select button button-primary" data-show="mobile">Mobile</button>
		<input value="<?php echo $hpm_show_meta['banners']['mobile']; ?>" type="hidden" id="hpm-show-banner-mobile-id" name="hpm-show-banner-mobile-id" />
		<?php echo ( !empty( $hpm_show_meta['banners']['mobile'] ) ? '<button class="hpm-show-banner-remove button button-secondary" data-show="mobile" style="border-color: red; color: red;">Remove</button>' : '' ); ?>
	</div>
	<div class="hpm-show-banner">
		<div class="hpm-show-banner-image" id="hpm-show-banner-tablet"<?php echo $hpm_tablet_url; ?>></div>
		<button class="hpm-show-banner-select button button-primary" data-show="tablet">Tablet</button>
		<input value="<?php echo $hpm_show_meta['banners']['tablet']; ?>" type="hidden" id="hpm-show-banner-tablet-id" name="hpm-show-banner-tablet-id" />
		<?php echo ( !empty( $hpm_show_meta['banners']['tablet'] ) ? '<button class="hpm-show-banner-remove button button-secondary" data-show="tablet" style="border-color: red; color: red;">Remove</button>' : '' ); ?>
	</div>
	<div class="hpm-show-banner">
		<div class="hpm-show-banner-image" id="hpm-show-banner-desktop"<?php echo $hpm_desktop_url; ?>></div>
		<button class="hpm-show-banner-select button button-primary" data-show="desktop">Desktop</button>
		<input value="<?php echo $hpm_show_meta['banners']['desktop']; ?>" type="hidden" id="hpm-show-banner-desktop-id" name="hpm-show-banner-desktop-id" />
		<?php echo ( !empty( $hpm_show_meta['banners']['desktop'] ) ? '<button class="hpm-show-banner-remove button button-secondary" data-show="desktop" style="border-color: red; color: red;">Remove</button>' : '' ); ?>
	</div>
</div>
<p>&nbsp;</p>

<h3><?PHP _e( "Show Information", 'hpm-podcasts' ); ?></h3>
<ul>
	<li><label for="hpm-show-times"><?php _e( "Show Times:", 'hpm-podcasts' ); ?></label> <input type="text" id="hpm-show-times" name="hpm-show-times" value="<?PHP echo $hpm_show_meta['times']; ?>" placeholder="Tuesdays at 8pm, etc." style="width: 60%;" /></li>
	<li><label for="hpm-show-hosts"><?php _e( "Hosts:", 'hpm-podcasts' ); ?></label> <input type="text" id="hpm-show-hosts" name="hpm-show-hosts" value="<?PHP echo $hpm_show_meta['hosts']; ?>" placeholder="Ernie, Big Bird, etc." style="width: 60%;" /></li>
</ul>
<p>&nbsp;</p>

<?php
	$podcasts = new WP_Query([
		'post_type' => 'podcasts',
		'post_status' => 'publish',
		'orderby' => 'name',
		'order' => 'ASC',
		'posts_per_page' => -1
	]); ?>
<h3><?PHP _e( "Podcast Feed", 'hpm-podcasts' ); ?></h3>
<p><?php _e( "If this show has/is a podcast, select it from the dropdown." , 'hpm-podcasts' ); ?><br />
<label for="hpm-show-pod"><?php _e( "Podcast:", 'hpm-podcasts' ); ?></label>
<select name="hpm-show-pod" id="hpm-show-pod">
	<option value=""<?PHP selected( '', $hpm_show_meta['podcast'], TRUE ); ?>><?PHP _e( "Select One", 'hpm-podcasts' ); ?></option>
<?php
	if ( $podcasts->have_posts() ) {
		while ( $podcasts->have_posts() ) {
			$podcasts->the_post(); ?>
			<option value="<?PHP echo $post->ID; ?>"<?PHP selected( $hpm_show_meta['podcast'], $post->ID, TRUE );?>><?PHP the_title(); ?></option>
<?php
		}
	} ?>
</select></p>

<p>&nbsp;</p>

<h3><?php _e( "YouTube Playlist ID:", 'hpm-podcasts' ); ?></h3>
<p><?php _e( "If this is a TV show with a playlist of videos on YouTube, enter the ID here to populate the player." , 'hpm-podcasts' ); ?><br />
<label for="hpm-show-ytp"><i><?php _e( "https://www.youtube.com/playlist?list=", 'hpm-podcasts' ); ?></i></label><input type="text" id="hpm-show-ytp" name="hpm-show-ytp" value="<?PHP echo $hpm_show_meta['ytp']; ?>" placeholder="YouTube Gobbldeegook" style="width: 40%;" /></p>
<p>&nbsp;</p>

<h3><?PHP _e( "Social Accounts", 'hpm-podcasts' ); ?></h3>
<ul>
	<li>
		<label for="hpm-social-fb"><?php _e( "Facebook:", 'hpm-podcasts' ); ?></label> <i>https://facebook.com/</i><input type="text" id="hpm-social-fb" name="hpm-social-fb" value="<?PHP echo $hpm_show_social['fb']; ?>" placeholder="page.name" style="width: 33%;" />
	</li>
	<li>
		<label for="hpm-social-twitter"><?php _e( "Twitter:", 'hpm-podcasts' ); ?></label> <i>https://twitter.com/</i><input type="text" id="hpm-social-twitter" name="hpm-social-twitter" value="<?PHP echo $hpm_show_social['twitter']; ?>" placeholder="handle" style="width: 33%;" />
	</li>
	<li>
		<label for="hpm-social-yt"><?php _e( "YouTube:", 'hpm-podcasts' ); ?></label> <input type="text" id="hpm-social-yt" name="hpm-social-yt" value="<?PHP echo $hpm_show_social['yt']; ?>" placeholder="YouTube Channel or Playlist URL" style="width: 33%;" />
	</li>
	<li>
		<label for="hpm-social-insta"><?php _e( "Instagram:", 'hpm-podcasts' ); ?></label> <i>https://instagram.com/</i><input type="text" id="hpm-social-insta" name="hpm-social-insta" value="<?PHP echo $hpm_show_social['insta']; ?>" placeholder="account.name" style="width: 33%;" />
	</li>
</ul>
<script>
	function capitalizeFirstLetter(string) {
		return string[0].toUpperCase() + string.slice(1);
	}
	jQuery(document).ready(function($){
		$('.hpm-show-banner-select').click(function(e){
			e.preventDefault();
			let size = $(this).attr('data-show');
			let frame = wp.media({
				title: 'Choose Your ' + capitalizeFirstLetter(size) + ' Banner',
				library: {type: 'image'},
				multiple: false,
				button: {text: 'Set ' + capitalizeFirstLetter(size) + ' Banner'}
			});
			frame.on('select', function(){
				let sizes = frame.state().get('selection').first().attributes.sizes;
				let thumb = sizes.full.url;
				if ( typeof sizes.medium !== 'undefined' ) {
					thumb = sizes.medium.url;
				}
				let attachId = frame.state().get('selection').first().id;
				$('#hpm-show-banner-'+size).css( 'background-image', 'url('+thumb+')' )
				$('#hpm-show-banner-'+size+'-id').val(attachId);
			});
			frame.open();
		});
		$('.hpm-show-banner-remove').click(function(e){
			e.preventDefault();
			let size = $(this).attr('data-show');
			$('#hpm-show-banner-'+size).css( 'background-image', '' )
			$('#hpm-show-banner-'+size+'-id').val('');
		});
	});
</script>
<style>
	.hpm-show-banner-wrap {
		overflow: hidden;
	}
	.hpm-show-banner {
		width: 20%;
		padding: 1em;
		float: left;
		text-align: center;
	}
	.hpm-show-banner .hpm-show-banner-image {
		height: 0;
		width: 100%;
		padding-bottom: calc(100% / 1.5);
		background-repeat: no-repeat;
		background-size: cover;
		background-position: top center;
		border: 1px dotted #bfbfbf;
		margin-bottom: 0.5em;
	}
</style>
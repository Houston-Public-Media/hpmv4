<?php
	wp_deregister_style( 'gutenberg-pdfjs' );
	wp_deregister_style( 'wp-block-library' );
	wp_deregister_style( 'wp-block-library-theme' );
?><!doctype html>
<html amp <?php language_attributes(); ?>>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
	<?php do_action( 'amp_post_template_head', $this ); ?>
	<style amp-custom>
		<?php $this->load_parts( [ 'style' ] ); ?>
		<?php do_action( 'amp_post_template_css', $this ); ?>
	</style>
</head>

<body class="<?php echo esc_attr( $this->get( 'body_class' ) ); ?>">

<?php $this->load_parts( [ 'header-bar' ] ); ?>

<article class="amp-wp-article">
<?php
	$amp_title = $this->post->post_title;
	if ( empty( $amp_title ) ) :
		$amp_title = $this->post->post_excerpt;
	endif; ?>
	<header class="amp-wp-article-header">
		<h1 class="amp-wp-title"><?php echo wp_kses_data( $amp_title ); ?></h1>
		<div class="amp-wp-meta amp-wp-byline">
			<?PHP coauthors_posts_links( ', ', ', ', '<span class="amp-wp-author author vcard">', '</span>', true ); ?>
		</div>
		<?php $this->load_parts( apply_filters( 'amp_post_article_header_meta', [ 'meta-time' ] ) ); ?>
	</header>

	<div class="amp-wp-article-content">
		<?php
			if ( $this->post->post_type == 'attachment' ) :
				$attach = get_post_meta( $this->ID, '_wp_attachment_metadata', true );
				if ( preg_match( '/image/', $this->post->post_mime_type ) ) :
					$media_credit = get_post_meta( $this->ID, '_wp_attachment_source_name', true );
					$media_credit_url = get_post_meta( $this->ID, '_wp_attachment_source_url', true );
					$media_license = get_post_meta( $this->ID, '_wp_attachment_license', true );
					$media_license_url = get_post_meta( $this->ID, '_wp_attachment_license_url', true );
					echo '<img src="'.wp_get_attachment_url( $this->ID ).'" alt="'.get_the_excerpt().'" />';
					if ( !empty( $media_credit_url ) ) :
						echo '<p>Credit: <a href="'.$media_credit_url.'">'.$media_credit.'</a></p>';
					else :
						echo '<p>Credit: '.$media_credit.'</p>';
					endif;
					if ( !empty( $media_license_url ) && !empty( $media_license ) ) :
						echo '<p>License: <a href="'.$media_license_url.'">'.$media_license.'</a></p>';
					elseif ( !empty( $media_license ) ) :
						echo '<p>License: '.$media_license.'</p>';
					endif; ?>
				<p>File Information:</p>
				<?PHP
					if ( !empty( $attach['filesize'] ) ) :
						$size = round( ( ( $attach['filesize'] / 1024 ) / 1024 ), 2, PHP_ROUND_HALF_UP )."MB";
					else :
						$size = 'Unknown';
					endif;
				?>
				<ul>
					<li>File Type: <?PHP echo $this->post->post_mime_type; ?></li>
					<li>Width: <?PHP echo $attach['width']; ?>px</li>
					<li>Height: <?PHP echo $attach['height']; ?>px</li>
					<li>File Size: <?PHP echo $size; ?></li>
					<?php if ( !empty( $attach['image_meta']['camera'] ) ) : ?><li>Camera: <?PHP echo $attach['image_meta']['camera']; ?></li><?php endif; ?>
					<?php if ( !empty( $attach['image_meta']['aperture'] ) ) : ?><li>Aperture: <?PHP echo $attach['image_meta']['aperture']; ?></li><?php endif; ?>
					<?php if ( !empty( $attach['image_meta']['focal_length'] ) ) : ?><li>Focal Length: <?PHP echo $attach['image_meta']['focal_length']; ?></li><?php endif; ?>
					<?php if ( !empty( $attach['image_meta']['iso'] ) ) : ?><li>ISO: <?PHP echo $attach['image_meta']['iso']; ?></li><?php endif; ?>
					<?php if ( !empty( $attach['image_meta']['shutter_speed'] ) ) : ?><li>Shutter Speed: <?PHP echo $attach['image_meta']['shutter_speed']; ?></li><?php endif; ?>
				</ul><?php
				endif;
			else :
				echo $this->get( 'post_amp_content' );
			endif;
		?>
	</div>

	<footer class="amp-wp-article-footer">
		<?php $this->load_parts( apply_filters( 'amp_post_article_footer_meta', [ 'meta-taxonomy' ] ) ); ?>
	</footer>

</article>

<?php $this->load_parts( [ 'footer' ] ); ?>

<?php do_action( 'amp_post_template_footer', $this ); ?>
<amp-analytics type="chartbeat">
	<script type="application/json">
		{
			"vars": {
				"uid": "33583",
				"domain": "houstonpublicmedia.org",
				"sections": "<?php echo str_replace( '&amp;', '&', wp_strip_all_tags( get_the_category_list( ', ', 'multiple', $this->ID ) ) ); ?>",
				"authors": "<?php coauthors( ',', ',', '', '', true ); ?>",
				"title": "<?php echo wp_kses_data( $amp_title ); ?>",
				"canonicalPath": "<?php echo get_the_permalink(); ?>"
			}
		}
	</script>
</amp-analytics>
</body>
</html>

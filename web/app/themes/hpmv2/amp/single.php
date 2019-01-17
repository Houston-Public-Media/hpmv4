<?php if (function_exists('newrelic_disable_autorum')) : newrelic_disable_autorum(); endif; ?>
<!doctype html>
<html amp <?php language_attributes(); ?>>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
	<?php do_action( 'amp_post_template_head', $this ); ?>
	<style amp-custom>
		<?php $this->load_parts( array( 'style' ) ); ?>
		<?php do_action( 'amp_post_template_css', $this ); ?>
	</style>
</head>

<body class="<?php echo esc_attr( $this->get( 'body_class' ) ); ?>">

<?php $this->load_parts( array( 'header-bar' ) ); ?>

<article class="amp-wp-article">

	<header class="amp-wp-article-header">
		<h1 class="amp-wp-title"><?php echo wp_kses_data( $this->get( 'post_title' ) ); ?></h1>
		<div class="amp-wp-meta amp-wp-byline">
			<?PHP coauthors_posts_links( ', ', ', ', '<span class="amp-wp-author author vcard">', '</span>', true ); ?>
		</div>
		<?php $this->load_parts( apply_filters( 'amp_post_article_header_meta', array( 'meta-time' ) ) ); ?>
	</header>

	<div class="amp-wp-article-content">
		<?php echo $this->get( 'post_amp_content' ); // amphtml content; no kses ?>
	</div>

	<footer class="amp-wp-article-footer">
		<?php $this->load_parts( apply_filters( 'amp_post_article_footer_meta', array( 'meta-taxonomy' ) ) ); ?>
	</footer>

</article>

<?php $this->load_parts( array( 'footer' ) ); ?>

<?php do_action( 'amp_post_template_footer', $this ); ?>
<amp-analytics type="chartbeat">
	<script type="application/json">
		{
			"vars": {
				"uid": "33583",
				"domain": "houstonpublicmedia.org",
				"sections": "<?php echo str_replace( '&amp;', '&', wp_strip_all_tags( get_the_category_list( ', ', 'multiple', get_the_ID()	) ) ); ?>",
				"authors": "<?php coauthors( ',', ',', '', '', true ); ?>",
				"title": "<?php echo wp_kses_data( $this->get( 'post_title' ) ); ?>",
				"canonicalPath": "<?php echo get_the_permalink(); ?>"
			}
		}
	</script>
</amp-analytics>
</body>
</html>

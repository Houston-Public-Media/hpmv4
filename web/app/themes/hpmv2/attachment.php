<?php
/**
 * The template for displaying pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage HPMv2
 * @since HPMv2 1.0
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?PHP
			while ( have_posts() ) :
				the_post();
				$mime = get_post_mime_type();
				$postClass = get_post_class();
				if ( preg_match( '/image/', $mime ) ) :
					$postClass[] = 'attachment-full';
				endif; ?>
			<article id="post-<?php the_ID(); ?>" <?php echo "class=\"".implode( ' ', $postClass )."\""; ?>>
				<header class="entry-header">
					<?php
						the_title( '<h1 class="entry-title">', '</h1>' );
						if ( preg_match( '/image/', $mime ) ) :
							the_excerpt();
						else :
							echo "<p>".get_excerpt_by_id( wp_get_post_parent_id( $post_ID ) )."</p>";
						endif; ?>
				</header><!-- .entry-header -->
				<div class="entry-content">
					<?php
						$attach = get_post_meta( get_the_ID(), '_wp_attachment_metadata', true );
						$s3 = get_post_meta( get_the_ID(), 'amazonS3_info', true );
						if ( preg_match( '/image/', $mime ) ) :
							$media_credit = get_post_meta( get_the_ID(), '_wp_attachment_source_name', true );
							$media_credit_url = get_post_meta( get_the_ID(), '_wp_attachment_source_url', true );
							$media_license = get_post_meta( get_the_ID(), '_wp_attachment_license', true );
							$media_license_url = get_post_meta( get_the_ID(), '_wp_attachment_license_url', true );
							echo '<img src="'.wp_get_attachment_url( get_the_ID() ).'" alt="'.get_the_excerpt().'" />';
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
						<li>File Type: <?PHP echo $mime; ?></li>
						<li>Width: <?PHP echo $attach['width']; ?>px</li>
						<li>Height: <?PHP echo $attach['height']; ?>px</li>
						<li>File Size: <?PHP echo $size; ?></li>
						<?php if ( !empty( $attach['image_meta']['camera'] ) ) : ?><li>Camera: <?PHP echo $attach['image_meta']['camera']; ?></li><?php endif; ?>
						<?php if ( !empty( $attach['image_meta']['aperture'] ) ) : ?><li>Aperture: <?PHP echo $attach['image_meta']['aperture']; ?></li><?php endif; ?>
						<?php if ( !empty( $attach['image_meta']['focal_length'] ) ) : ?><li>Focal Length: <?PHP echo $attach['image_meta']['focal_length']; ?></li><?php endif; ?>
						<?php if ( !empty( $attach['image_meta']['iso'] ) ) : ?><li>ISO: <?PHP echo $attach['image_meta']['iso']; ?></li><?php endif; ?>
						<?php if ( !empty( $attach['image_meta']['shutter_speed'] ) ) : ?><li>Shutter Speed: <?PHP echo $attach['image_meta']['shutter_speed']; ?></li><?php endif; ?>
					</ul>
					<?PHP		
						elseif ( preg_match( '/audio/', $mime ) ) : 
							if ( $mime == 'audio/mpeg' || $mime == 'audio/wav' ) :
								echo do_shortcode( '[audio id="'.get_the_ID().'"][/audio]' );	
							else : ?>
					<p><a href="<?PHP echo wp_get_attachment_url( get_the_ID() ); ?>?source=download-attachment">Download	the
                            file</a></p>
					<?php
							endif; ?>	
					<p>File Information:</p>
						<?PHP
							$bitrate = ($attach['bitrate']/1000).'kbps '.strtoupper($attach['bitrate_mode']);
							$sample = ($attach['sample_rate']/1000).'kHz';
							if ( !empty( $attach['filesize'] ) ) :
								$size = round( ( ( $attach['filesize'] / 1024 ) / 1024 ), 2, PHP_ROUND_HALF_UP )."MB";
							else :
								$size = 'Unknown';
							endif;
						?>
					<ul>
						<li>Format: <?PHP echo strtoupper($attach['dataformat']); ?></li>
						<li>Channels: <?PHP echo ucwords($attach['channelmode']); ?></li>
						<li>Bitrate: <?PHP echo $bitrate; ?></li>
						<li>Sample Rate: <?PHP echo $sample; ?></li>
						<li>Length: <?PHP echo $attach['length_formatted']; ?></li>
						<li>File Size: <?PHP echo $size; ?></li>
					</ul>
					<?php
						elseif ( preg_match( '/video/', $mime ) ) :
							if ( $mime == 'video/quicktime' || $mime == 'video/mp4' ) : ?>
					<link rel="stylesheet" href="https://cdn.hpm.io/static/js/flowplayer/skin/functional.css">
					<script src="https://cdn.hpm.io/static/js/flowplayer/flowplayer.min.js"></script>
					<div id="videodisplay" class="player"></div>
					<style>
						#videodisplay {
							background-color: black;
						}
					</style>
					<script>
						flowplayer.conf = {
							ratio: 9/16,
							splash: true,
							analytics: "UA-3106036-9",
							live: true,
							embed: false,
							wmode: 'transparent',
							fullscreen: false
						};
						flowplayer("#videodisplay", {
							clip: {
								title: '<?php echo get_the_title(); ?>',
								sources: [
									{
										type: '<?PHP echo $mime; ?>',
										src: '<?PHP echo wp_get_attachment_url( get_the_ID() ); ?>?source=flowplayer-attachment'
									}
								]
							}
						});
					</script>
					<?php
							endif; ?>
					<p><a href="<?PHP echo wp_get_attachment_url( get_the_ID() ); ?>?source=download-attachment">Download the
                            file</a></p>
					<p>File Information:</p>
						<?PHP
							$bitrate_vid = ($attach['bitrate']/1000).'kbps '.strtoupper($attach['bitrate_mode']);
							$bitrate_audio = ($attach['audio']['bitrate']/1000).'kbps '.strtoupper($attach['audio']['bitrate_mode']);
							$sample = ($attach['audio']['sample_rate']/1000).'kHz';
							if ( !empty( $attach['filesize'] ) ) :
								$size = round( ( ( $attach['filesize'] / 1024 ) / 1024 ), 2, PHP_ROUND_HALF_UP )."MB";
							else :
								$size = 'Unknown';
							endif;
						?>
					<ul>
						<li>Format: <?PHP echo strtoupper($attach['dataformat']); ?></li>
						<?php if ( !empty( $attach['encoder'] ) ) : ?><li>Video Codec: <?PHP echo $attach['encoder']; ?></li><?PHP elseif ( !empty( $attach['codec'] ) ) : ?><li>Video Codec: <?PHP echo $attach['codec'];?></li><?PHP endif; ?>
						<?php if ( !empty( $attach['bitrate'] ) ) : ?><li>Video Bitrate: <?PHP echo $bitrate_vid; ?></li><?PHP endif; ?>
						<li>Video Width: <?PHP echo $attach['width']; ?>px</li>
						<li>Video Height: <?PHP echo $attach['height']; ?>px</li>
						<?php if ( !empty( $attach['audio']['bitrate'] ) ) : ?><li>Audio Bitrate: <?php echo $bitrate_audio; ?></li><?php endif; ?>
						<?php if ( !empty( $attach['audio']['sample_rate'] ) ) : ?><li>Audio Sample Rate: <?PHP echo $sample; ?></li><?php endif; ?>
						<?php if ( !empty( $attach['audio']['codec'] ) ) : ?><li>Audio Codec: <?php echo $attach['audio']['codec']; ?></li><?php endif; ?>
						<li>Length: <?PHP echo $attach['length_formatted']; ?></li>
						<li>File Size: <?PHP echo $size; ?></li>
					</ul>
					<?php
						elseif ( preg_match( '/application/', $mime ) ) : 
							$site_url = site_url();
							echo do_shortcode('[pdfjs-viewer url='.$site_url.'/'.$s3['key'].' viewer_width=600px viewer_height=700px fullscreen=true download=true print=true openfile=false]');?>
					<p>File Information:</p>
						<?PHP
							if ( !empty( $attach['filesize'] ) ) :
								$size = round( ( ( $attach['filesize'] / 1024 ) / 1024 ), 2, PHP_ROUND_HALF_UP )."MB";
							else :
								$size = 'Unknown';
							endif;
						?>
					<ul>
						<li>File Size: <?PHP echo $size; ?></li>
					</ul>
					<?php
						endif; ?>
					<p>From the article: <a href="<?PHP echo get_permalink( wp_get_post_parent_id( get_the_ID() ) ); ?>"><?PHP echo get_the_title( wp_get_post_parent_id( get_the_ID() ) ); ?></a></p>
					<?php
						the_content( sprintf(
							__( 'Continue reading %s', 'hpmv2' ),
							the_title( '<span class="screen-reader-text">', '</span>', false )
						) );
					?>
					<div id="article-share">
					<?php
						$uri_title = rawurlencode( get_the_title() );
						$facebook_link = rawurlencode( get_the_permalink().'?utm_source=facebook-share-attachment&utm_medium=button&utm_campaign=hpm-share-link' );
						$twitter_link = rawurlencode( get_the_permalink().'?utm_source=twitter-share-attachment&utm_medium=button&utm_campaign=hpm-share-link' );
						$linkedin_link = rawurlencode( get_the_permalink().'?utm_source=linked-share-attachment&utm_medium=button&utm_campaign=hpm-share-link' );
						$uri_excerpt = rawurlencode( get_the_excerpt() ); ?>
						<h4>Share</h4>
						<div class="article-share-icon">
							<a href="https://www.facebook.com/sharer.php?u=<?php echo $facebook_link; ?>" target="_blank" data-dialog="400:368">
								<span class="fa fa-facebook" aria-hidden="true"></span>
							</a>
						</div>
						<div class="article-share-icon">
							 <a href="https://twitter.com/share?text=<?PHP echo $uri_title; ?>&amp;url=<?PHP echo $twitter_link; ?>" target="_blank" data-dialog="364:250">
								<span class="fa fa-twitter" aria-hidden="true"></span>
							</a>
						</div>
						<div class="article-share-icon">
							<a href="mailto:?subject=Someone%20Shared%20an%20Article%20From%20Houston%20Public%20Media%21&body=I%20would%20like%20to%20share%20an%20article%20I%20found%20on%20Houston%20Public%20Media!%0A%0A<?php the_title(); ?>%0A%0A<?php the_permalink(); ?>">
								<span class="fa fa-envelope" aria-hidden="true"></span>
							</a>
						</div>
						<div class="article-share-icon">
							<a href="http://www.linkedin.com/shareArticle?mini=true&source=Houston+Public+Media&summary=<?PHP echo $uri_excerpt; ?>&title=<?PHP echo $uri_title; ?>&url=<?PHP echo $linkedin_link; ?>" target="_blank" data-dialog="600:471">
								<span class="fa fa-linkedin" aria-hidden="true"></span>
							</a>
						</div>
					</div>
				</div><!-- .entry-content -->

				<footer class="entry-footer">
				<?PHP	
					$tags_list = get_the_tag_list( '', _x( ' ', 'Used between list items, there is a space after the comma.', 'hpmv2' ) );
					if ( $tags_list ) {
						printf( '<p class="screen-reader-text"><span class="tags-links"><span class="screen-reader-text">%1$s </span>%2$s</span></p>',
							_x( 'Tags', 'Used before tag names.', 'hpmv2' ),
							$tags_list
						);
					}
					edit_post_link( __( 'Edit', 'hpmv2' ), '<span class="edit-link">', '</span>' ); ?>
				</footer><!-- .entry-footer -->
			</article><!-- #post-## -->
			<?php
				endwhile;
				if ( !preg_match( '/image/', $mime ) ) : ?>
			<aside class="column-right">
				<?php get_template_part( 'sidebar', 'none' ); ?>
			</aside>
			<?php endif; ?>
		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>

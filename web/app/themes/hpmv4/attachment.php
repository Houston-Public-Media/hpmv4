<?php
/**
 * The template for displaying pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage HPMv4
 * @since HPMv4 4.0
 */

get_header(); ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?PHP
			while ( have_posts() ) :
				the_post();
				$mime = get_post_mime_type();
				$extra = '';
				if ( preg_match( '/image/', $mime ) ) :
					$extra = 'attachment-full';
				endif; ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( $extra ); ?>>
				<header class="entry-header">
					<?php
						$attach_title = get_the_title();
						if ( empty( $attach_title ) && preg_match( '/image/', $mime ) ) :
							$attach_title = get_post_meta( get_the_ID(), '_wp_attachment_image_alt', true );
						endif; ?>
						<h1 class="entry-title"><?php echo $attach_title; ?></h1>
					<?php
						if ( preg_match( '/image/', $mime ) ) :
							the_excerpt();
						else :
							echo "<p>".get_excerpt_by_id( wp_get_post_parent_id( $post_ID ) )."</p>";
						endif; ?>
				</header>
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
							if ( $mime == 'video/quicktime' || $mime == 'video/mp4' ) :
								echo do_shortcode('[video src="' . wp_get_attachment_url( get_the_ID() ) .'"][/video]');
							endif; ?>
					<p><a href="<?PHP echo wp_get_attachment_url( get_the_ID() ); ?>?source=download-attachment">Download the file</a></p>
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
							if ( !empty( $s3 ) ) :
								echo do_shortcode('[pdfjs-viewer url='.$site_url.'/'.$s3['key'].' viewer_width=600px viewer_height=700px fullscreen=true download=true print=true openfile=false]');
							else :
								echo "<p>We're sorry, this attachment is no longer available.</p>";
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
						<li>File Size: <?PHP echo $size; ?></li>
					</ul>
					<?php
						endif; ?>
					<p>From the article: <a href="<?PHP echo get_permalink( wp_get_post_parent_id( get_the_ID() ) ); ?>"><?PHP echo get_the_title( wp_get_post_parent_id( get_the_ID() ) ); ?></a></p>
					<?php
						the_content( sprintf(
							__( 'Continue reading %s', 'hpmv4' ),
							the_title( '<span class="screen-reader-text">', '</span>', false )
						) );
						hpm_article_share();
					?>
				</div>
				<footer class="entry-footer">
				<?PHP
					$tags_list = get_the_tag_list( '', _x( ' ', 'Used between list items, there is a space after the comma.', 'hpmv4' ) );
					if ( $tags_list ) {
						printf( '<p class="screen-reader-text"><span class="tags-links"><span class="screen-reader-text">%1$s </span>%2$s</span></p>',
							_x( 'Tags', 'Used before tag names.', 'hpmv4' ),
							$tags_list
						);
					}
					edit_post_link( __( 'Edit', 'hpmv4' ), '<span class="edit-link">', '</span>' ); ?>
				</footer>
			</article>
			<?php
				endwhile;
				if ( !preg_match( '/image/', $mime ) ) : ?>
			<aside class="column-right">
				<?php get_template_part( 'sidebar', 'none' ); ?>
			</aside>
			<?php endif; ?>
		</main>
	</div>
<?php get_footer(); ?>

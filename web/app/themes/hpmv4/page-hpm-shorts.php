<?php
/*
Template Name: HPM Shorts
*/
get_header();
$options = get_option( 'hpm_videos' );
$perPage = $options['paging_limit'];
$currentPage = isset( $_GET['vpage'] ) ? max( 1, intval( $_GET['vpage'] ) ) : 1;
$offset = ( $currentPage - 1 ) * $perPage;
$videos = HPM_Videos::get( false, $perPage, $offset );
$hasNextPage = count( $videos ) === $perPage; ?>
<style>
	.btn-primary{background-color: #237bbd; !important;}
</style>

	<script src="https://players.brightcove.net/<?php echo $options['account_id']; ?>/<?php echo $options['player_id']; ?>_default/index.min.js"></script>
	<div id="primary" class="content-area">
		<main id="main" class="site-main">
			<header class="page-header banner">
				<h1 class="page-title"><?php echo get_the_title(); ?></h1>
			</header>
			<div class="page-content">
				<?php the_content(); ?>
			</div>
			<?php if ( !empty( $videos ) && post_password_required() === false ) { ?>
			<section class="video-grid-section">
				<div class="row g-4">
				<?php foreach ($videos as $video) {
					$poster = $video['poster'] ?? $video['thumbnail'] ?? '';
					$hlsSource = $video['source']; ?>
					<div class="col-lg-3 col-md-6 col-12">
						<div class="card h-100" style="border:none;background:#237bbd;">
							<img src="<?php echo esc_url( $poster ); ?>" class="card-img-top thumbnail" data-src="<?php echo esc_url( $hlsSource ); ?>" alt="<?php echo esc_html($video['name'] ?? ''); ?>" style="cursor:pointer;">
							<video class="w-100 d-none" controls playsinline preload="none"></video>
							<div class="card-body">
								<h6 class="card-title mb-0 text-white">
									<?php echo esc_html($video['name'] ?? ''); ?>
								</h6>
							</div>
						</div>
					</div>
				<?php } ?>
				</div>
				<nav class="mt-5 d-flex justify-content-between">
				<?php if ( $currentPage > 1 ) { ?>
					<a class="btn btn-primary" href="<?php echo esc_url(add_query_arg('vpage', $currentPage - 1)); ?>">
						Previous
					</a>
				<?php
					}
					if ( $hasNextPage ) { ?>
					<a class="btn btn-primary ms-auto" href="<?php echo esc_url(add_query_arg('vpage', $currentPage + 1)); ?>">
						Next
					</a>
				<?php } ?>
				</nav>
			</section>
			<?php } ?>
		</main>
	</div>
	<script>
		document.addEventListener("DOMContentLoaded", function(){
			const thumbnails = document.querySelectorAll(".thumbnail");
			thumbnails.forEach(function(img){
				img.addEventListener("click", function(){
					const video = this.nextElementSibling;
					const src = this.dataset.src;
					if(!src) return;
					video.src = src;
					this.classList.add("d-none");
					video.classList.remove("d-none");
					video.play();
				});
			});
		});
	</script>
<?php get_footer(); ?>
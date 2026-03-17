<script src="https://players.brightcove.net/<?php echo HPM_BC_ACCOUNT_ID . '/' . HPM_BC_PLAYER_ID; ?>_default/index.min.js"></script>
<style>
    .carousel-control-next, .carousel-control-prev{ width: unset; }
</style>
<?php
	$videos = hpm_getBrightcovePlaylist();
	$perSlide = 4;
	$total = count( $videos );
	$slides = ceil( $total / $perSlide );
	if ( !empty( $videos ) ) { ?>
	<section class="section radio-list">
		<h2 class="title mb-4">
			<strong>HPM <span>Shorts</span></strong>
		</h2>
		<div id="videoCarousel" class="carousel slide" data-bs-ride="false">
			<div class="carousel-inner">
			<?php for ($i = 0; $i < $slides; $i++) { ?>
				<div class="carousel-item <?php echo $i === 0 ? 'active' : ''; ?>">
					<div class="row g-4">
					<?php
						$start = $i * $perSlide;
						$chunk = array_slice( $videos, $start, $perSlide );
						foreach ( $chunk as $video ) { ?>
						<div class="col-lg-3 col-md-6 col-12">
							<div class="card h-100" style="border:none; background:#237bbd;">
								<img
									src="<?php echo esc_url( $video['poster'] ?? '' ); ?>"
									class="card-img-top thumbnail"
									data-src="<?php echo esc_url( $video['source'] ); ?>"
									data-video-id="<?php echo esc_attr( $video['id'] ); ?>"
									style="cursor:pointer;">
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
					</div>
			<?php } ?>
			</div>
			<button class="carousel-control-prev" type="button" data-bs-target="#videoCarousel" data-bs-slide="prev">
				<span class="carousel-control-prev-icon"></span>
			</button>
			<button class="carousel-control-next" type="button" data-bs-target="#videoCarousel" data-bs-slide="next">
				<span class="carousel-control-next-icon"></span>
			</button>
		</div>
	</section>
<?php } ?>
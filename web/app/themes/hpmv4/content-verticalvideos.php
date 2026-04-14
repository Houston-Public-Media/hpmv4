<?php
	$options = get_option( 'hpm_videos' );
	$videos = HPM_Videos::get( false, 12 );
	$perSlide = 4;
	$total = $videos['count'];
	$slides = ceil( $total / $perSlide );
	if ( !empty( $videos['videos'] ) ) { ?>
	<section class="section radio-list">
		<script src="https://players.brightcove.net/<?php echo $options['account_id'] . '/' . $options['player_id']; ?>_default/index.min.js"></script>
		<style>
			.carousel-control-next, .carousel-control-prev{ width: unset; }
		</style>
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
						$chunk = array_slice( $videos['videos'], $start, $perSlide );
						foreach ( $chunk as $video ) { ?>
						<div class="col-lg-3 col-md-6 col-12">
							<div class="card h-100" style="border:none; background:#237bbd;">
								<img
									src="<?php echo esc_url( $video['poster'] ?? '' ); ?>"
									class="card-img-top thumbnail"
									data-src="<?php echo esc_url( $video['source'] ); ?>"
									data-video-id="<?php echo esc_attr( $video['id'] ); ?>"
									alt="<?php echo esc_html( $video['name'] ) ?? ''; ?>"
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
        <div style="text-align: right;"><a href="/hpm-shorts" style="font-weight: bold; color:#237bbd; font-size: 13px; text-decoration: none;">View all Videos</a></div>
		<script>
			document.addEventListener("DOMContentLoaded", function () {
				const thumbnails = document.querySelectorAll(".thumbnail");

				thumbnails.forEach(function (img) {
					img.addEventListener("click", function () {

						const video = this.nextElementSibling;
						const src = this.dataset.src;

						if (!video || !src) return;

						// 🔴 Pause ALL videos first
						document.querySelectorAll("#videoCarousel video").forEach(function (v) {
							v.pause();
						});

						// 🟡 Hide all videos & show thumbnails again
						document.querySelectorAll("#videoCarousel video").forEach(function (v) {
							v.classList.add("d-none");
							if (v.previousElementSibling) {
								v.previousElementSibling.classList.remove("d-none");
							}
						});

						// 🟢 Load source only once
						if (!video.src) {
							video.src = src;
						}

						// 🟢 Show and play selected video
						this.classList.add("d-none");
						video.classList.remove("d-none");

						video.play().catch(err => {
							console.log("Playback error:", err);
						});
					});
				});
			});
		</script>
	</section>
<?php } ?>
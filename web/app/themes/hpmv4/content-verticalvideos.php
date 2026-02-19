<?php
$videos = hpm_getBrightcovePlaylist(HPM_BC_ACCOUNT_ID, HPM_BC_PLAYLIST_ID, HPM_BC_POLICY_KEY);
$chunks = array_chunk($videos, 4);
?>

<?php if (!empty($chunks)) : ?>
    <section class="section radio-list">
        <h2 class="title mb-4">
            <strong>HPM <span>Shorts</span></strong>
        </h2>
        <div id="videoCarousel" class="carousel slide" data-bs-ride="false">
            <div class="carousel-inner">
                <?php foreach ($chunks as $index => $group) : ?>
                    <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                        <div class="row g-4">
                           <?php foreach ($group as $video) :
                                $hlsSource = '';
                                if (!empty($video['sources'])) {
                                    foreach ($video['sources'] as $source) {
                                        if (!empty($source['src']) && str_contains($source['src'], '.m3u8')) {
                                            $hlsSource = $source['src'];
                                            break;
                                        }
                                    }
                                }
                                if (!$hlsSource) continue;
                                ?>
                                <div class="col-lg-3 col-md-6 col-12">
                                    <div class="card h-100" style="border:none; background-color: #e7e7e8;">
                                        <img src="<?php echo esc_url($video['poster'] ?? ''); ?>" class="card-img-top thumbnail" data-src="<?php echo esc_url($hlsSource); ?>"
                                             style="cursor:pointer;">
                                        <video class="w-100 d-none" controls></video>
                                        <div class="card-body">
                                            <h6 class="card-title mb-0" style="color:#404040;">
                                                <?php echo esc_html($video['name'] ?? ''); ?>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#videoCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#videoCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </section>
<?php endif; ?>
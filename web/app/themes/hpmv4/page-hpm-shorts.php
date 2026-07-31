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
$hasNextPage = ( $offset + $perPage ) < $videos['total']; ?>
<?php //echo $options['player_id']; ?>
<style>
    .btn-primary{ background-color: #237bbd; !important; }
</style>
<script src="https://players.brightcove.net/<?php echo $options['account_id']; ?>/default_default/index.min.js"></script>
<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <header class="page-header banner">
            <h1 class="page-title"><?php echo get_the_title(); ?></h1>
        </header>
        <div class="page-content">
            <?php the_content(); ?>
        </div>
        <?php if ( !empty( $videos['videos'] ) && post_password_required() === false ) { ?>
            <section class="video-grid-section">
                <div class="row g-4">
                    <?php foreach ( $videos['videos'] as $video ) {
                        $poster = $video['poster'] ?? $video['thumbnail'] ?? '';
                        $hlsSource = $video['source']; ?>
                        <div class="col-lg-3 col-md-6 col-12">
                            <div class="card h-100" style="border:none;background:#237bbd;">
                                <img src="<?php echo esc_url( $poster ); ?>" class="card-img-top thumbnail" data-src="<?php echo esc_url( $hlsSource ); ?>" alt="<?php echo esc_html($video['name'] ?? ''); ?>" style="cursor:pointer;">
                                <!--<video class="w-100 d-none" controls playsinline preload="none"></video>-->
                                <video
                                        class="w-100 d-none hpm-video-player"
                                        controls
                                        playsinline
                                        preload="none"
                                        data-video-id="<?php echo esc_attr($video['id'] ?? ''); ?>"
                                        data-video-name="<?php echo esc_attr($video['name'] ?? ''); ?>"
                                ></video>
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
        const analyticsBaseURL =  window.location.protocol + "//metrics.brightcove.com/tracker/v2/?";
        const session = Math.floor(Math.random() * 1000000) + "_" + new Date().toISOString();
        const pageURL = encodeURIComponent(window.location.href);
        const referrer = encodeURIComponent(document.referrer);
        const ACCOUNT_ID = "<?php echo esc_js($options['account_id']); ?>";
        let currentVideo = null;
        let firstTimeUpdate = true;
        let initialPosition = 0;
        let lastPosition = 0;
        function sendData(url){
            const img = document.createElement("img");
            img.src = url;
            img.style.display = "none";
            document.body.appendChild(img);
        }
        function sendAnalyticsEvent(eventType, videoElement, extra={}){
            if(!currentVideo){
                return;
            }
            let url = analyticsBaseURL +
                "event=" + eventType +
                "&session=" + session +
                "&domain=houstonpublicmedia.org" +
                "&account=" + ACCOUNT_ID +
                "&time=" + Date.now() +
                "&destination=" + pageURL;
            if(referrer){
                url += "&source=" + referrer;
            }
            url += "&video=" + encodeURIComponent(currentVideo.id);
            url += "&video_name=" +  encodeURIComponent(currentVideo.name);
            if(eventType === "video_engagement"){
                url += "&video_duration=" +  videoElement.duration;
                url += "&range=" + extra.range;
            }
            //console.log("Brightcove Analytics:" +url);
            sendData(url);
        }
        function setupVideo(video){
            let started = false;
            video.addEventListener("play", function(){
                if(!started){
                    started = true;
                    sendAnalyticsEvent( "video_view",  video );
                }
            });
            video.addEventListener("loadedmetadata", function(){
                sendAnalyticsEvent(
                    "video_impression",
                    video
                );
            });
            video.addEventListener("timeupdate", function(e){
                let position = video.currentTime;
                if(firstTimeUpdate){
                    initialPosition = position;
                    lastPosition = position;
                    firstTimeUpdate = false;
                }
                if( Math.floor(position) - Math.floor(lastPosition) >= 10 ){
                    let range = initialPosition +  ".." + position;
                    sendAnalyticsEvent( "video_engagement",  video,
                        {
                            range: range
                        }
                    );
                    lastPosition = position;
                }
            });
        }
        document.querySelectorAll(".thumbnail")
            .forEach(function(img){
                img.addEventListener("click", function(){
                    document.querySelectorAll(".hpm-video-player")
                        .forEach(function(v){
                            v.pause();
                            v.currentTime = 0;
                            v.classList.add("d-none");
                            if(v.previousElementSibling){
                                v.previousElementSibling.classList.remove("d-none");
                            }
                        });
                    const video = this.nextElementSibling;
                    const src = this.dataset.src;
                    currentVideo = {
                        id: video.dataset.videoId,
                        name: video.dataset.videoName
                    };
                    if(!src){
                        return;
                    }
                    video.src = src;
                    this.classList.add("d-none");
                    video.classList.remove("d-none");
                    firstTimeUpdate = true;
                    initialPosition = 0;
                    lastPosition = 0;
                    setupVideo(video);
                    video.play();
                });
            });
    });
</script>

<?php get_footer(); ?>

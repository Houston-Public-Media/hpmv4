document.addEventListener("DOMContentLoaded", function () {
	let currentVideo = null;
	let currentThumbnail = null;
	let currentHls = null;
	function resetCurrentVideo() {
		if (currentVideo) {
			currentVideo.pause();
			currentVideo.currentTime = 0;
			currentVideo.classList.add('d-none');
			if (currentThumbnail) {
				currentThumbnail.classList.remove('d-none');
			}
			if (currentHls) {
				currentHls.destroy();
				currentHls = null;
			}
			currentVideo = null;
			currentThumbnail = null;
		}
	}
	document.querySelectorAll('.thumbnail').forEach(thumbnail => {
		thumbnail.addEventListener('click', function () {
			const card = this.closest('.card');
			const video = card.querySelector('video');
			const src = this.getAttribute('data-src');
			if (currentVideo && currentVideo !== video) {
				resetCurrentVideo();
			}
			this.classList.add('d-none');
			video.classList.remove('d-none');
			if (Hls.isSupported()) {
				currentHls = new Hls();
				currentHls.loadSource(src);
				currentHls.attachMedia(video);
			} else if (video.canPlayType('application/vnd.apple.mpegurl')) {
				video.src = src;
			}
			video.play();
			currentVideo = video;
			currentThumbnail = this;
		});
	});
	const carousel = document.querySelector('#videoCarousel');
	if (carousel) {
		carousel.addEventListener('slide.bs.carousel', function () {
			resetCurrentVideo();
		});
	}
});
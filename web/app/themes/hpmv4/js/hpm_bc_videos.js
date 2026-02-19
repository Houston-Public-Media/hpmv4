document.addEventListener("DOMContentLoaded", function () {

	let currentVideo = null;
	let currentThumbnail = null;
	let currentHls = null;

	document.querySelectorAll('.thumbnail').forEach(thumbnail => {

		thumbnail.addEventListener('click', function () {

			const card = this.closest('.card');
			const video = card.querySelector('video');
			const src = this.getAttribute('data-src');

			if (currentVideo && currentVideo !== video) {
				currentVideo.pause();
				currentVideo.currentTime = 0;
				currentVideo.classList.add('d-none');
				currentThumbnail.classList.remove('d-none');
				if (currentHls) currentHls.destroy();
			}

			this.classList.add('d-none');
			video.classList.remove('d-none');

			if (Hls.isSupported()) {
				const hls = new Hls();
				hls.loadSource(src);
				hls.attachMedia(video);
				currentHls = hls;
			} else if (video.canPlayType('application/vnd.apple.mpegurl')) {
				video.src = src;
			}

			video.play();
			currentVideo = video;
			currentThumbnail = this;
		});

	});

});

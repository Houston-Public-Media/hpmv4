import { hpm, npUpdateData, npUpdateHtml  } from '../main.js';
export let jpp = {
	'version': 1,
	'streams': {
		'news': {
			'type': 'audio',
			'title': 'HPM News',
			'sources': {
				'aac' : {
					'src': 'https://stream.houstonpublicmedia.org/news-aac',
					'type': 'audio/aac'
				},
				'mp3': {
					'src': 'https://stream.houstonpublicmedia.org/news-mp3',
					'type': 'audio/mpeg'
				},
				'hls': {
					'src': 'https://hls.houstonpublicmedia.org/hpmnews/playlist.m3u8',
					'type': 'application/vnd.apple.mpegurl'
				}
			}
		},
		'classical': {
			'type': 'audio',
			'title': 'HPM Classical',
			'sources': {
				'aac' : {
					'src': 'https://stream.houstonpublicmedia.org/classical-aac',
					'type': 'audio/aac'
				},
				'mp3': {
					'src': 'https://stream.houstonpublicmedia.org/classical-mp3',
					'type': 'audio/mpeg'
				},
				'hls': {
					'src': 'https://hls.houstonpublicmedia.org/classical/playlist.m3u8',
					'type': 'application/vnd.apple.mpegurl'
				}
			}
		},
		'mixtape': {
			'type': 'audio',
			'title': 'HPM Mixtape',
			'sources': {
				'aac' : {
					'src': 'https://stream.houstonpublicmedia.org/mixtape-aac',
					'type': 'audio/aac'
				},
				'mp3': {
					'src': 'https://stream.houstonpublicmedia.org/mixtape-mp3',
					'type': 'audio/mpeg'
				},
				'hls': {
					'src': 'https://hls.houstonpublicmedia.org/mixtape/playlist.m3u8',
					'type': 'application/vnd.apple.mpegurl'
				}
			}
		},
	},
	'assetsUrl': 'https://cdn.houstonpublicmedia.org/assets/',
	'elements': {},
	'prefStream': 'news'
};
jpp.loadPlayer = () => {
	const player = document.getElementById('jpp-player');
	let prefStream = localStorage.getItem('prefStream');
	if ( prefStream == null ) {
		localStorage.setItem('prefStream','news');
		prefStream = 'news';
	} else {
		prefStream = localStorage.getItem('prefStream');
	}
	jpp.prefStream = prefStream;
	//jpp.player.source = jpp.streams[prefStream];
	document.getElementById('jpp-button-'+prefStream).classList.add('jpp-button-active');
	if (Hls.isSupported()) {
		let hls = new Hls();
		hls.attachMedia(player);
		hls.on(Hls.Events.MEDIA_ATTACHED, () => {
			hls.loadSource(jpp['streams'][prefStream]['sources']['hls']['src']);
			hls.on(Hls.Events.MANIFEST_PARSED, (event, data) => {
				console.log("manifest loaded, found " + data.levels.length + " quality level");
			});
		});
		jpp.hls = hls;
	}
	jpp.player = player;
	hpm.stationIds.news.refresh = true;
	hpm.stationIds.news.obj = 'jpp';
	hpm.stationIds.classical.refresh = true;
	hpm.stationIds.classical.obj = 'jpp';
	hpm.stationIds.mixtape.refresh = true;
	hpm.stationIds.mixtape.obj = 'jpp';
	for (let st in hpm.stationIds) {
		if ( hpm.stationIds[st].refresh ) {
			fetch(hpm.stationIds[st].feed)
				.then((response) => response.json())
				.then((data) => {
					npUpdateData(data,st);
				});
		}
	}
};
jpp.playerCreate = () => {
	jpp.elements['streams'] = document.getElementById('jpp-streams');
	jpp.elements['menu'] = document.getElementById('jpp-menu');
	jpp.elements['menuUp'] = document.getElementById('jpp-menu-up');
	jpp.elements['menuDown'] = document.getElementById('jpp-menu-down');
	jpp.elements['menuWrap'] = document.getElementById('jpp-menu-wrap');
	jpp.elements['nowPlaying'] = document.getElementById('jpp-now-playing');
	jpp.elements['play'] = document.getElementById('jpp-player-play');
	jpp.elements['stop'] = document.getElementById('jpp-player-stop');
	jpp.elements['volumeUp'] = document.getElementById('jpp-player-volume');
	jpp.elements['mute'] = document.getElementById('jpp-player-mute');
	jpp.elements['main'] = document.getElementById('jpp-main');
	for (let stream in jpp.streams ) {
		jpp.elements.streams.innerHTML += '<div class="menu-station-section"><button data-station="'+stream+'" id="jpp-button-'+stream+'" class="jpp-station"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><use href="#hpm-play-button"></use></svg></button><div id="menu-station-'+stream+'"></div></div>';
	}
	jpp.buttonManage();
	jpp.loadPlayer();
};
jpp.buttonManage = () => {
	let menuButtons = document.querySelectorAll('#jpp-menu-wrap button');
	Array.from(menuButtons).forEach((item) => {
		item.addEventListener('click', () => {
			if (item.classList.contains('jpp-button-active')) {
				jpp.player.play();
			}
			let station = item.getAttribute('data-station');
			Array.from(jpp.elements.streams.querySelectorAll('button')).forEach((button) => {
				button.classList.remove('jpp-button-active');
			});
			item.classList.add('jpp-button-active');
			jpp.player.pause();
			if (jpp.elements.play.classList.contains('hidden')) {
				jpp.elements.play.classList.toggle('hidden');
			}
			if (!jpp.elements.stop.classList.contains('hidden')) {
				jpp.elements.stop.classList.toggle('hidden');
			}
			jpp.hls.destroy();
			jpp.hls = new Hls();
			jpp.hls.attachMedia(jpp.player);
			jpp.hls.loadSource(jpp['streams'][station]['sources']['hls']['src']);
			localStorage.setItem('prefStream',station);
			jpp.prefStream = station;
			jpp.player.play();
			if (!jpp.elements.play.classList.contains('hidden')) {
				jpp.elements.play.classList.toggle('hidden');
			}
			if (jpp.elements.stop.classList.contains('hidden')) {
				jpp.elements.stop.classList.toggle('hidden');
			}
			npUpdateHtml('jpp', station, 'false');
		});
	});
};
jpp.menuButton = () => {
	jpp.elements.menuUp.addEventListener('click', () => {
		jpp.elements.menuWrap.classList.toggle('jpp-menu-active');
		jpp.elements.menuUp.classList.toggle('hidden');
		jpp.elements.menuDown.classList.toggle('hidden');
	});
	jpp.elements.menuDown.addEventListener('click', () => {
		jpp.elements.menuWrap.classList.toggle('jpp-menu-active');
		jpp.elements.menuUp.classList.toggle('hidden');
		jpp.elements.menuDown.classList.toggle('hidden');
	});
	jpp.elements.play.addEventListener('click', () => {
		jpp.elements.play.classList.toggle('hidden');
		jpp.elements.stop.classList.toggle('hidden');
		jpp.player.play();
	});
	jpp.elements.stop.addEventListener('click', () => {
		jpp.elements.play.classList.toggle('hidden');
		jpp.elements.stop.classList.toggle('hidden');
		jpp.player.pause();
	});
	jpp.elements.volumeUp.addEventListener('click', () => {
		jpp.elements.volumeUp.classList.toggle('hidden');
		jpp.elements.mute.classList.toggle('hidden');
		jpp.player.muted = !jpp.player.muted;
	});
	jpp.elements.mute.addEventListener('click', () => {
		jpp.elements.volumeUp.classList.toggle('hidden');
		jpp.elements.mute.classList.toggle('hidden');
		jpp.player.muted = !jpp.player.muted;
	});
};
jpp.init = () => {
	jpp.playerCreate();
	jpp.menuButton();
};

window.addEventListener('DOMContentLoaded', () => {
	jpp.init();
});
document.addEventListener('turbo:load', () => {
	// console.log('Turbo Load');
	// hpm.navHandlers();
	// hpm.videoHandlers();
	// hpm.shareHandlers();
	// hpm.audioEmbeds();
	// hpm.audioPlayers();
	// hpm.localBanners();
});
let jpp = {
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
jpp.inIframe = () => {
	try {
		return window.self !== window.top;
	} catch (e) {
		return true;
	}
}
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
					hpm.npUpdateData(data,st);
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
			hpm.npUpdateHtml('jpp', station, 'false');
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
jpp.receiveMessage = (event) => {
	if (event.data.sender !== 'jpp') {
		return false;
	}
	if (event.data.message === 'iframeload') {
		document.title = event.data.payload.title;
		let historyPrev = history.state;
		history.replaceState(historyPrev, event.data.payload.title, event.data.payload.path);
		jpp.elements.loader.classList.add('hidden');
	} else if ( event.data.message === 'linkclick' ) {
		let newUrl = new URL(event.data.payload.url);
		jpp.elements.iframe.src = newUrl.pathname;
		jpp.elements.loader.classList.remove('hidden');
	} else if ( event.data.message === 'audioplayer' ) {
		jpp.player.pause();
	} else if ( event.data.message === 'iframeheight' ) {
		jpp.elements.iframe.setAttribute('height', event.data.payload.height);
	}
};
jpp.clickManage = () => {
	let aHref = document.querySelectorAll('a');
	Array.from(aHref).forEach((link) => {
		if ( link.getAttribute('data-jpp') !== null || link.href.includes('mailto:') ) {
			return false;
		} else {
			link.addEventListener('click', (e) => {
				e.preventDefault();
				let onSite = link.hostname.includes('houstonpublicmedia.org');
				if ( ( link.hostname && !onSite ) || link.pathname.includes('/wp-admin/') ) {
					window.open(link.href,'_blank');
				} else {
					let hrefN = new URL(link.href);
					let oldHref = window.location.pathname;
					let b = document.querySelector('body');
					if (b.hasAttribute('style')) b.removeAttribute('style');
					if (b.hasAttribute('class')) b.removeAttribute('class');
					Array.from(b.children).forEach((item) => {
						if (!item.id.includes('jpp-player-') && !item.id.includes('hpm-') && item.id !== 'sprite-plyr') {
							b.removeChild(item);
						}
					});
					let frameWrap = document.createElement('div');
					frameWrap.id = 'jpp-frame-wrap';
					frameWrap.innerHTML = '<div id="jpp-loader"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M304 48C304 74.51 282.5 96 256 96C229.5 96 208 74.51 208 48C208 21.49 229.5 0 256 0C282.5 0 304 21.49 304 48zM304 464C304 490.5 282.5 512 256 512C229.5 512 208 490.5 208 464C208 437.5 229.5 416 256 416C282.5 416 304 437.5 304 464zM0 256C0 229.5 21.49 208 48 208C74.51 208 96 229.5 96 256C96 282.5 74.51 304 48 304C21.49 304 0 282.5 0 256zM512 256C512 282.5 490.5 304 464 304C437.5 304 416 282.5 416 256C416 229.5 437.5 208 464 208C490.5 208 512 229.5 512 256zM74.98 437C56.23 418.3 56.23 387.9 74.98 369.1C93.73 350.4 124.1 350.4 142.9 369.1C161.6 387.9 161.6 418.3 142.9 437C124.1 455.8 93.73 455.8 74.98 437V437zM142.9 142.9C124.1 161.6 93.73 161.6 74.98 142.9C56.24 124.1 56.24 93.73 74.98 74.98C93.73 56.23 124.1 56.23 142.9 74.98C161.6 93.73 161.6 124.1 142.9 142.9zM369.1 369.1C387.9 350.4 418.3 350.4 437 369.1C455.8 387.9 455.8 418.3 437 437C418.3 455.8 387.9 455.8 369.1 437C350.4 418.3 350.4 387.9 369.1 369.1V369.1z"/></svg></div><iframe id="jpp-frame-iframe" src="'+hrefN.pathname+'" frameborder="0" allowfullscreen></iframe>';
					b.append(frameWrap);
					jpp.elements['iframe'] = document.getElementById('jpp-frame-iframe');
					jpp.elements['loader'] = document.getElementById('jpp-loader');
					b.class = 'persist-child-present';
					history.pushState({'previous':oldHref}, link.innerText, hrefN.pathname);
					window.addEventListener('message', (event) => {
						jpp.receiveMessage(event);
					}, false);
					window.addEventListener('popstate', (e) => {
						jpp.elements.iframe.setAttribute('src', history.state.previous);
					});
					document.getElementsByTagName('html')[0].setAttribute('style','margin-top: 0 !important');
				}
			});
		}
	});
};
jpp.parentFrame = () => {
	history.replaceState({'previous':window.location.pathname}, document.title, window.location.pathname);
	jpp.playerCreate();
	jpp.menuButton();
	jpp.clickManage();
};
jpp.childFrame = () => {
	window.addEventListener('load', () => {
		window.parent.postMessage({'message': 'iframeload','payload': {'path':window.location.pathname,'title':document.title}, 'sender': 'jpp'},'*');
	});
	window.addEventListener('DOMContentLoaded', () => {
		window.parent.postMessage({'message': 'iframeheight','payload': {'height':document.body.getBoundingClientRect().height}, 'sender': 'jpp'},'*');
	});
	let aHref = document.querySelectorAll('a');
	Array.from(aHref).forEach((link) => {
		link.addEventListener('click', (e) => {
			e.preventDefault();
			e.stopPropagation();
			if ( ( link.hostname && link.hostname.replace('www.','') !== location.hostname.replace('www.','') ) || link.pathname.includes('/wp-admin/') ) {
				window.open(link.href,'_blank');
			} else {
				window.parent.postMessage({'message': 'linkclick','payload': {'url':link.href, 'text':link.innerText}, 'sender': 'jpp'},'*');
				return false;
			}
		});
	});
};
let getQueryVariable = ( variable ) => {
	let query = window.location.search.substring(1);
	let vars = query.split("&");
	for (let i= 0; i < vars.length; i++) {
		let pair = vars[i].split("=");
		if (pair[0] === variable) {
			return pair[1];
		}
	}
};
window.addEventListener('DOMContentLoaded', (event) => {
	let sourceVar = getQueryVariable("source");
	if ( sourceVar === 'pwa' ) {
		sessionStorage.setItem('source', sourceVar);
	}
	if ( sessionStorage.getItem('source') === 'pwa' ) {
		let tagCss = document.createElement('link');
		let firstScriptTag = document.getElementsByTagName('script')[0];
		tagCss.href = 'https://assets.houstonpublicmedia.org/app/themes/hpmv4/js/experiments/persistent.css?v=20240903';
		tagCss.rel = 'stylesheet';
		tagCss.type = 'text/css';
		tagCss.media = 'all';
		tagCss.id = 'hpm-persistent';
		firstScriptTag.parentNode.insertBefore(tagCss, firstScriptTag);
		document.querySelector('#jpp-player-persist').classList.remove('hidden', 'visually-hidden');
		if (!jpp.inIframe()) {
			jpp.parentFrame();
		} else {
			jpp.childFrame();
		}
	}
});
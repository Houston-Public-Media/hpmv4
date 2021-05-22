var jpp = {
	'version': 1,
	'streams': {
		'news': {
			'type': 'audio',
			'title': 'HPM News',
			'sources': [{
				'src': 'https://stream.houstonpublicmedia.org/news-aac',
				'type': 'audio/aac'
			},{
				'src': 'https://stream.houstonpublicmedia.org/news-mp3',
				'type': 'audio/mpeg'
			}]
		},
		'classical': {
			'type': 'audio',
			'title': 'HPM Classical',
			'sources': [{
				'src': 'https://stream.houstonpublicmedia.org/classical-aac',
				'type': 'audio/aac'
			},{
				'src': 'https://stream.houstonpublicmedia.org/classical-mp3',
				'type': 'audio/mpeg'
			}]
		},
		'mixtape': {
			'type': 'audio',
			'title': 'HPM Mixtape',
			'sources': [{
				'src': 'https://stream.houstonpublicmedia.org/mixtape-aac',
				'type': 'audio/aac'
			},{
				'src': 'https://stream.houstonpublicmedia.org/mixtape-mp3',
				'type': 'audio/mpeg'
			}]
		},
	},
	'podcastList': 'https://www.houstonpublicmedia.org/wp-json/hpm-podcast/v1/list',
	'assetsUrl': 'https://cdn.hpm.io/assets/',
	'playlist': [],
	'podcasts': [],
	'elements': {}
};
jpp.inIframe = () => {
	try {
		return window.self !== window.top;
	} catch (e) {
		return true;
	}
}
jpp.getJSON = (url, callback) => {
	var xhr = new XMLHttpRequest();
	xhr.open('GET', url, true);
	xhr.responseType = 'json';
	xhr.onload = () => {
		var status = xhr.status;
		if (status === 200) {
			callback(null, xhr.response);
		} else {
			callback(status, xhr.response);
		}
	};
	xhr.send();
};
jpp.loadStyles = () => {
	var styles = {'fa': false, 'plyr': false};
	var loaded = document.querySelectorAll('link[rel=stylesheet]');
	for(var m = 0; m < loaded.length; m++){
		var href = loaded[m].getAttribute('href');
		if (href.includes('fontawesome')) {
			styles.fa = true;
		} else if (href.includes('plyr')) {
			styles.plyr = true;
		}
	}
	if (!styles.fa) {
		var faStyle = document.createElement('link');
		faStyle.rel = 'stylesheet';
		faStyle.href = jpp.assetsUrl+'fonts/fontawesome/css/all.css';
		document.head.append(faStyle);
	}
	if (!styles.plyr) {
		var plyrStyle = document.createElement('link');
		plyrStyle.rel = 'stylesheet';
		plyrStyle.href = jpp.assetsUrl+'js/plyr/plyr.css';
		document.head.append(plyrStyle);
	}
	var jppStyle = document.createElement('link');
	jppStyle.rel = 'stylesheet';
	jppStyle.href = '/app/themes/hpmv2/js/persistent.css';
	document.head.append(jppStyle);
}
jpp.loadPlyr = () => {
	var plyrJs = document.createElement('script');
	plyrJs.src = jpp.assetsUrl + 'js/plyr/plyr.js';
	const controls = [ 'play', 'progress', 'current-time', 'mute', 'volume', 'settings', 'airplay' ];
	plyrJs.addEventListener('load',function(e){
		const player = new Plyr('#jpp-player', { controls, 'invertTime': false });
		var prefStream = getCookie('prefStream');
		if ( prefStream == null ) {
			setCookie('prefStream','news',365*24);
			prefStream = 'news';
		} else {
			prefStream = getCookie('prefStream');
		}
		jpp.player = player;
		jpp.player.source = jpp.streams[prefStream];
		document.getElementById('jpp-button-'+prefStream).classList.add('jpp-button-active');
		jpp.player.on('playing', (event) => {
			jpp.elements.menuWrap.classList.add('jpp-now-play');
			jpp.elements.nowPlaying.innerHTML = 'Now Playing: '+ jpp.player.config.title;
		});
		jpp.player.on('play', (event) => {
			jpp.elements.menuWrap.classList.add('jpp-now-play');
			jpp.elements.nowPlaying.innerHTML = 'Now Playing: '+ jpp.player.config.title;
		});
		jpp.player.on('ended', (event) => {
			jpp.elements.menuWrap.classList.remove('jpp-now-play');
			jpp.elements.nowPlaying.innerHTML = 'Now Playing: Nothing yet...';
		});
	});
	document.head.append(plyrJs);
}
jpp.playerCreate = () => {
	var jpper = document.createElement('div');
	jpper.id = 'jpp-player-persist';
	jpper.innerHTML = `
	<div id="jpp-player-wrap"><audio id="jpp-player" controls crossorigin playsinline preload="none"></audio></div>
	<div id="jpp-menu-wrap">
		<aside id="jpp-menu">
			<button data-section="streams" id="jpp-button-streams" class="jpp-menu-section jpp-button-active">Streams</button>
			<button data-section="podcasts" id="jpp-button-podcasts" class="jpp-menu-section">Podcasts</button>
		</aside>
		<div id="jpp-submenus">
			<aside id="jpp-streams" class="jpp-section-active"></aside>
			<aside id="jpp-podcasts"></aside>
		</div>
		<div id="jpp-now-playing">Now Playing: Nothing yet...</div>
	</div>
	<div id="jpp-button-wrap">
		<button id="jpp-button-menu"><span class="fas fa-bars"></span></button>
	</div>`;
	document.body.append(jpper);
	jpp.elements['streams'] = document.getElementById('jpp-streams');
	jpp.elements['podcasts'] = document.getElementById('jpp-podcasts');
	jpp.elements['menu'] = document.getElementById('jpp-menu');
	jpp.elements['menuButton'] = document.getElementById('jpp-button-menu');
	jpp.elements['menuWrap'] = document.getElementById('jpp-menu-wrap');
	jpp.elements['nowPlaying'] = document.getElementById('jpp-now-playing');
	for ( stream in jpp.streams ) {
		jpp.elements.streams.innerHTML += '<button data-station="'+stream+'" id="jpp-button-'+stream+'" class="jpp-station">'+jpp.streams[stream]['title']+'</button>';
	}
	getJSON( jpp.podcastList, function(err, data) {
		if (err !== null) {
			console.log(err);
		} else {
			data.data.list.forEach( (item, index) => {
				jpp.podcasts.push({
					'name': item.name,
					'slug': item.slug,
					'feed': item.feed_json,
					'page': item.archive,
					'episode': {
						'audio': item.latest_episode.audio,
						'title': item.latest_episode.title
					}
				});
			});
			jpp.podListUpdate();
		}
	});
};
jpp.podListUpdate = () => {
	var list = '';
	jpp.podcasts.forEach((item) => {
		list += '<button data-station="'+item.slug+'" data-audio="'+ item.episode.audio +'" data-title="'+ item.name +': '+item.episode.title +'">' + item.name + '</button>';
	});
	jpp.elements.podcasts.innerHTML = list;
	jpp.buttonManage();
};
jpp.buttonManage = () => {
	var menuButtons = document.querySelectorAll('#jpp-menu-wrap button');
	Array.from(menuButtons).forEach((item) => {
		item.addEventListener('click', () => {
			if (item.classList.contains('jpp-button-active')) {
				return false;
			}
			Array.from(item.parentNode.children).forEach((button) => {
				button.classList.remove('jpp-button-active');
			});
			item.classList.add('jpp-button-active');
			var station = item.getAttribute('data-station');
			var section = item.getAttribute('data-section');
			if (station !== null) {
				var audio = item.getAttribute('data-audio');
				jpp.player.stop();
				if (audio == null) {
					jpp.player.source = jpp['streams'][station];
					setCookie('prefStream',station,365*24);
				} else {
					jpp.player.source = {
						'type': 'audio',
						'title': item.getAttribute('data-title'),
						'sources': [{
							'src': audio,
							'type': 'audio/mpeg'
						}]
					};
				}
				jpp.player.play();
			} else if (section !== null) {
				Array.from(document.querySelectorAll('#jpp-submenus aside')).forEach((submenu) => {
					submenu.classList.remove('jpp-section-active');
				});
				document.querySelector('aside#jpp-'+section).classList.add('jpp-section-active');
			}
		});
	});
}
jpp.menuButton = () => {
	jpp.elements.menuButton.addEventListener('click',function(e){
		var menuB = document.querySelector('#jpp-button-menu span.fas');
		if (jpp.elements.menuWrap.classList.contains('jpp-menu-active')) {
			jpp.elements.menuWrap.classList.remove('jpp-menu-active');
			menuB.classList.add('fa-bars');
			menuB.classList.remove('fa-chevron-down');
		} else {
			jpp.elements.menuWrap.classList.add('jpp-menu-active');
			menuB.classList.remove('fa-bars');
			menuB.classList.add('fa-chevron-down');
		}
	});
};
jpp.receiveMessage = (event) => {
	if (event.data.sender !== 'jpp') {
		return false;
	}
	if (event.data.message == 'iframeload') {
		document.title = event.data.payload.title;
		var historyPrev = history.state;
		history.replaceState(historyPrev, event.data.payload.title, event.data.payload.path);
		jpp.elements.loader.classList.add('hidden');
	} else if ( event.data.message == 'linkclick' ) {
		var newUrl = new URL(event.data.payload.url);
		jpp.elements.iframe.src = newUrl.pathname;
		jpp.elements.loader.classList.remove('hidden');
	} else if ( event.data.message == 'audioplayer' ) {
		jpp.playlistUpdate(event.data.payload.title, event.data.payload.audio);
	}
};
jpp.clickManage = () => {
	var aHref = document.querySelectorAll('a');
	Array.from(aHref).forEach((link) => {
		if ( link.getAttribute('data-jpp') !== null || link.href.includes('mailto:') ) {
			return false;
		} else {
			link.addEventListener('click', (e) => {
				e.preventDefault();
				if ( ( link.hostname && link.hostname.replace('www.','') !== location.hostname.replace('www.','') ) || link.pathname.includes('/wp-admin/') ) {
					window.open(link.href,'_blank');
				} else {
					if ( timeOuts.length > 0 ) {
						Array.from(timeOuts).forEach((item) => {
							clearTimeout(item);
						});
					}
					var hrefN = new URL(link.href);
					var oldHref = window.location.pathname;
					var b = document.querySelector('body');
					if (b.hasAttribute('style')) b.removeAttribute('style');
					if (b.hasAttribute('class')) b.removeAttribute('class');
					Array.from(b.children).forEach((item) => {
						if (!item.id.includes('jpp-player-') && item.id !== 'sprite-plyr') {
							b.removeChild(item);
						}
					});
					var frameWrap = document.createElement('div');
					frameWrap.id = 'jpp-frame-wrap';
					frameWrap.innerHTML = '<div id="jpp-loader" class="fa-3x"><span class="fas fa-spinner fa-spin"></span></div><iframe id="jpp-frame-iframe" src="'+hrefN.pathname+'" frameborder="0" allowfullscreen></iframe>';
					b.append(frameWrap);
					jpp.elements['iframe'] = document.getElementById('jpp-frame-iframe');
					jpp.elements['loader'] = document.getElementById('jpp-loader');
					b.class = 'persist-child-present';
					history.pushState({'previous':oldHref}, link.innerText, hrefN.pathname);
					window.addEventListener('message', (event) => {
						jpp.receiveMessage(event);
					}, false);
					window.addEventListener('popstate',function(e){
						jpp.elements.iframe.setAttribute('src', history.state.previous);
					});
				}
			});
		}
	});
};
jpp.parentFrame = () => {
	history.replaceState({'previous':window.location.pathname}, document.title, window.location.pathname);
	jpp.playerCreate();
	jpp.loadPlyr();
	jpp.menuButton();
	jpp.clickManage();
};
jpp.childFrame = () => {
	window.addEventListener('load',function(){
		window.parent.postMessage({'message': 'iframeload','payload': {'path':window.location.pathname,'title':document.title}, 'sender': 'jpp'},'*');
	});
	var aHref = document.querySelectorAll('a');
	Array.from(aHref).forEach((link) => {
		link.addEventListener('click', function(e){
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

/*	TODO: Add event listeners for playlist on update
	TODO: Add code to popup menu and select playlist submenu on add
	TODO: Add options for podcasts and playlist entries (play now, remove, etc)
*/
jpp.loadStyles();
window.addEventListener('DOMContentLoaded', (event) => {
	if ( !jpp.inIframe() ) {
		jpp.parentFrame();
	} else {
		jpp.childFrame();
	}
});
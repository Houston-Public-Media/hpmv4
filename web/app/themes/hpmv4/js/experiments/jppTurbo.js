if ( typeof jpp !== typeof undefined && jpp !== false) {
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
	'podcasts': [],
	'elements': {},
	'state': 'stopped'
};
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
jpp.loadPlyr = () => {
	const controls = [ 'play' ];
	const player = new Plyr('#jpp-player', { controls, 'volume': 1, 'loadSprite': true, 'muted': false, 'autopause': true });
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
};
jpp.playerCreate = () => {
	jpp.elements['streams'] = document.getElementById('jpp-streams');
	jpp.elements['podcasts'] = document.getElementById('jpp-podcasts');
	jpp.elements['playlist'] = document.getElementById('jpp-playlist');
	jpp.elements['menu'] = document.getElementById('jpp-menu');
	jpp.elements['menuUp'] = document.getElementById('jpp-menu-up');
	jpp.elements['menuDown'] = document.getElementById('jpp-menu-down');
	jpp.elements['menuWrap'] = document.getElementById('jpp-menu-wrap');
	jpp.elements['nowPlaying'] = document.getElementById('jpp-now-playing');
	for ( stream in jpp.streams ) {
		jpp.elements.streams.innerHTML += '<button data-station="'+stream+'" id="jpp-button-'+stream+'" class="jpp-station">'+jpp.streams[stream]['title']+'</button>';
	}
	jpp.getJSON( jpp.podcastList, function(err, data) {
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
		list += '<button data-station="'+item.slug+'" data-audio="'+ item.episode.audio +'">' + item.name + '</button>';
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
	jpp.elements.menuUp.addEventListener('click', (e) => {
		jpp.elements.menuWrap.classList.toggle('jpp-menu-active');
		jpp.elements.menuUp.classList.toggle('hidden');
		jpp.elements.menuDown.classList.toggle('hidden');
	});
	jpp.elements.menuDown.addEventListener('click', (e) => {
		jpp.elements.menuWrap.classList.toggle('jpp-menu-active');
		jpp.elements.menuUp.classList.toggle('hidden');
		jpp.elements.menuDown.classList.toggle('hidden');
	});
};
jpp.init = () => {
	jpp.playerCreate();
	jpp.loadPlyr();
	jpp.menuButton();
};

/*	TODO: Add event listeners for playlist on update
	TODO: Add code to popup menu and select playlist submenu on add
	TODO: Add options for podcasts and playlist entries (play now, remove, etc)
*/
window.addEventListener('DOMContentLoaded', (event) => {
	jpp.init();
});
document.addEventListener('turbo:before-fetch-response', (event) => {
	console.log(event);
	googletag.pubads().refresh();
});
}
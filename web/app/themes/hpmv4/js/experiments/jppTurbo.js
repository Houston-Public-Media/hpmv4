if ( typeof jpp !== 'object') {
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
		'prefStream': 'news'
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
		jpp.prefStream = prefStream;
		jpp.player = player;
		jpp.player.source = jpp.streams[prefStream];
		document.getElementById('jpp-button-'+prefStream).classList.add('jpp-button-active');
		jpp.player.on('ended', (event) => {
			hpm.npUpdateHtml('jpp', jpp.prefStream, 'false');
			hpm.stationIds.news.refresh = true;
			hpm.stationIds.classical.refresh = true;
			hpm.stationIds.mixtape.refresh = true;
			jpp.player.source = jpp['streams'][jpp.prefStream];
			jpp.player.play();
		});

		hpm.stationIds.news.refresh = true;
		hpm.stationIds.news.obj = 'jpp';
		hpm.stationIds.classical.refresh = true;
		hpm.stationIds.classical.obj = 'jpp';
		hpm.stationIds.mixtape.refresh = true;
		hpm.stationIds.mixtape.obj = 'jpp';
		for (let st in hpm.stationIds) {
			if ( hpm.stationIds[st].refresh ) {
				hpm.getJSON( hpm.stationIds[st].feed, (err, data) => {
					if (err !== null) {
						console.log(err);
					} else {
						hpm.npUpdateData(data,st);
					}
				});
			}
		}
	};
	jpp.playerCreate = () => {
		jpp.elements['streams'] = document.getElementById('jpp-streams');
		jpp.elements['podcasts'] = document.getElementById('jpp-podcasts');
		jpp.elements['menu'] = document.getElementById('jpp-menu');
		jpp.elements['menuUp'] = document.getElementById('jpp-menu-up');
		jpp.elements['menuDown'] = document.getElementById('jpp-menu-down');
		jpp.elements['menuWrap'] = document.getElementById('jpp-menu-wrap');
		jpp.elements['nowPlaying'] = document.getElementById('jpp-now-playing');
		for ( stream in jpp.streams ) {
			jpp.elements.streams.innerHTML += '<button data-station="'+stream+'" id="jpp-button-'+stream+'" class="jpp-station">'+jpp.streams[stream]['title']+'</button>';
		}
		jpp.getJSON( jpp.podcastList, (err, data) => {
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
				jpp.getJSON( 'https://hpm-recast.streamguys1.com/api/sgrecast/podcasts/5/5e152475a85bb?limit=1&format=json', (err, data) => {
					if (err !== null) {
						console.log(err);
					} else {
						jpp.podcasts.push({
							'name': data.channel.title,
							'slug': 'hpm-newscasts',
							'feed': 'https://hpm-recast.streamguys1.com/api/sgrecast/podcasts/5/5e152475a85bb?format=json',
							'page': data.channel.link,
							'episode': {
								'audio': data.channel.items[0].url,
								'title': data.channel.items[0].title
							}
						});
						jpp.podListUpdate();
					}
				});
			}
		});
	};
	jpp.podListUpdate = () => {
		var list = '';
		jpp.podcasts.forEach((item) => {
			list += '<details><summary>' + item.name + '</summary><button data-station="'+item.slug+'" data-title="'+item.episode.title+'" data-audio="'+ item.episode.audio +'">Latest Episode</button><p><a href="' + item.page + '">Podcast Archive</a></p></details>';
		});
		jpp.elements.podcasts.innerHTML = list;
		jpp.buttonManage();
	};
	jpp.buttonManage = () => {
		var menuButtons = document.querySelectorAll('#jpp-menu-wrap button');
		Array.from(menuButtons).forEach((item) => {
			item.addEventListener('click', () => {
				if (item.classList.contains('jpp-button-active')) {
					jpp.player.play();
				}
				var station = item.getAttribute('data-station');
				var section = item.getAttribute('data-section');
				if (station !== null) {
					Array.from(jpp.elements.podcasts.children).forEach((button) => {
						Array.from(button.children).forEach((child) => {
							child.classList.remove('jpp-button-active');
						});
					});
					Array.from(jpp.elements.streams.children).forEach((button) => {
						button.classList.remove('jpp-button-active');
					});
					item.classList.add('jpp-button-active');
					var audio = item.getAttribute('data-audio');
					jpp.player.stop();
					if (audio == null) {
						jpp.player.source = jpp['streams'][station];
						setCookie('prefStream',station,365*24);
						jpp.prefStream = station;
						hpm.stationIds.news.refresh = true;
						hpm.stationIds.classical.refresh = true;
						hpm.stationIds.mixtape.refresh = true;
						timeOuts.push(setInterval('hpm.npDataDownload()',60000));
						hpm.npUpdateHtml(hpm.stationIds[ station ]['obj'], station, hpm.stationIds[ station ]['next']);
					} else {
						jpp.player.source = {
							'type': 'audio',
							'title': item.getAttribute('data-title'),
							'sources': [{
								'src': audio,
								'type': 'audio/mpeg'
							}]
						};
						hpm.stationIds.news.refresh = false;
						hpm.stationIds.classical.refresh = false;
						hpm.stationIds.mixtape.refresh = false;
						if ( timeOuts.length > 0 ) {
							Array.from(timeOuts).forEach((item) => {
								clearTimeout(item);
							});
						}
						var stationString = 'Podcast||' + item.innerText + '||' + item.getAttribute('data-title');
						hpm.npUpdateHtml('jpp', stationString, 'false');
					}
					jpp.player.play();

				} else if (section !== null) {
					Array.from(document.querySelectorAll('#jpp-submenus aside')).forEach((submenu) => {
						submenu.classList.remove('jpp-section-active');
					});
					Array.from(item.parentNode.children).forEach((button) => {
						button.classList.remove('jpp-button-active');
					});
					item.classList.add('jpp-button-active');
					document.querySelector('aside#jpp-'+section).classList.add('jpp-section-active');
				}
			});
		});
	};
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
	document.addEventListener('turbo:load', (event) => {
		console.log(event);

	});
	document.addEventListener('turbo:load', (event) => {
		console.log('Turbo Load');
		console.log(event);
		googletag.pubads().refresh();
		hpm.navHandlers();
		hpm.videoHandlers();
		hpm.shareHandlers();
		hpm.audioEmbeds();
		hpm.npSearch();
		hpm.audioPlayers();
		hpm.localBanners();
	});
}
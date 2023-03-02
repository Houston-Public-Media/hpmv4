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
	'assetsUrl': 'https://cdn.houstonpublicmedia.org/assets/',
	'podcasts': [],
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
	// jpp.player.on('play', (event) => {
	// 	hpm.npUpdateHtml(hpm.stationIds[ prefStream ]['obj'], prefStream, hpm.stationIds[ prefStream ]['next']);
	// });
	jpp.player.on('ended', (event) => {
		jpp.elements.nowPlaying.innerHTML = 'Now Playing: Nothing yet...';
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
						if (!item.id.includes('jpp-player-') && !item.id.includes('hpm-') && item.id !== 'sprite-plyr') {
							b.removeChild(item);
						}
					});
					var frameWrap = document.createElement('div');
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
	window.addEventListener('load', () => {
		window.parent.postMessage({'message': 'iframeload','payload': {'path':window.location.pathname,'title':document.title}, 'sender': 'jpp'},'*');
	});
	var aHref = document.querySelectorAll('a');
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
// TODO: Add event listeners for playlist on update
// TODO: Add code to popup menu and select playlist submenu on add
// TODO: Add options for podcasts and playlist entries (play now, remove, etc)
window.addEventListener('DOMContentLoaded', (event) => {
	if ( !jpp.inIframe() ) {
		jpp.parentFrame();
	} else {
		jpp.childFrame();
	}
});
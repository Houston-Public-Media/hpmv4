if ( typeof hpm !== 'object') {
	var getCookie = (cname) => {
		var name = cname + "=";
		var decodedCookie = decodeURIComponent(document.cookie);
		var ca = decodedCookie.split(';');
		for(var i = 0; i <ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0) === ' ') {
				c = c.substring(1);
			}
			if (c.indexOf(name) === 0) {
				return c.substring(name.length, c.length);
			}
		}
		return null;
	}
	var timeOuts = [];
	var setCookie = (cname, cvalue, exhours) => {
		var d = new Date();
		d.setTime(d.getTime() + (exhours*60*60*1000));
		var expires = 'expires=' + d.toUTCString();
		document.cookie = cname + '=' + cvalue + ';' + expires + ';path=/;SameSite=lax;Secure;';
	};

	if ( getCookie('inapp') !== null ) {
		var css = document.createElement('style');
		css.appendChild(document.createTextNode('#foot-banner, #top-donate, #masthead nav#site-navigation .nav-top.nav-donate, .top-banner { display: none; }'));
		document.getElementsByTagName("head")[0].appendChild(css);
	}

	var amPm = (timeString) => {
		var hourEnd = timeString.indexOf(":");
		var H = +timeString.substr(0, hourEnd);
		var h = H % 12 || 12;
		var ampm = (H < 12 || H === 24) ? " AM" : " PM";
		return timeString = h + timeString.substr(hourEnd, 3) + ampm;
	};

	var hpm = {};
	hpm.getJSON = function(url, callback) {
		var xhr = new XMLHttpRequest();
		xhr.open('GET', url, true);
		xhr.responseType = 'json';
		xhr.onload = function() {
			var status = xhr.status;
			if (status === 200) {
				callback(null, xhr.response);
			} else {
				callback(status, xhr.response);
			}
		};
		xhr.send();
	};

	hpm.navHandlers = () => {
		var siteNav = document.querySelector('nav#site-navigation');
		var buttonDiv = document.querySelectorAll('div[tabindex="0"]');
		var topMenu = document.querySelector('#top-mobile-menu');
		var closeMenu = document.querySelector('#top-mobile-close');
		var topSearch = document.querySelector('#top-search');
		var searchInput = document.querySelector('#top-search > form > input[type=search]');
		if ( siteNav !== null ) {
			var menuWithChildren = siteNav.querySelectorAll('li.menu-item-has-children');
			siteNav.addEventListener('focusin', () => {
				document.body.classList.add('nav-active-menu');
			});
			siteNav.addEventListener('focusout', () => {
				document.body.classList.remove('nav-active-menu');
			});
			topMenu.addEventListener('click', () => {
				document.body.classList.add('nav-active-menu');
			});
			closeMenu.addEventListener('click', () => {
				document.body.classList.remove('nav-active-menu');
			});
			if ( menuWithChildren !== null ) {
				Array.from(menuWithChildren).forEach((menuC) => {
					menuC.addEventListener('focusin', () => {
						menuC.firstElementChild.setAttribute('aria-expanded', 'true');
					});
					menuC.addEventListener('focusout', () => {
						menuC.firstElementChild.setAttribute('aria-expanded', 'false');
						if (window.innerWidth > 840) {
							menuC.classList.remove('nav-active');
						}
					});
					menuC.addEventListener('click', () => {
						menuC.classList.toggle('nav-active');
					});
					menuC.firstElementChild.addEventListener('click', (event) => {
						if (window.innerWidth < 1024) {
							if (event.currentTarget.getAttribute('aria-expanded') == 'true' ) {
								event.preventDefault();
								document.getElementById('focus-sink').focus({preventScroll:true});
							}
						}
					});
				});
			}
		}
		if ( topSearch !== null ) {
			topSearch.addEventListener('click', () => {
				searchInput.focus({preventScroll:true});
			});
		}
		Array.from(buttonDiv).forEach((bD) => {
			bD.addEventListener('focusin', () => {
				bD.setAttribute('aria-expanded', 'true');
			});
			bD.addEventListener('focusout', () => {
				bD.setAttribute('aria-expanded', 'false');
			});
		});
	};

	hpm.videoHandlers = () => {
		var allVideos = document.querySelectorAll("iframe[src*='vimeo.com'], iframe[src*='youtube.com']," +
			" iframe[src*='youtube-nocookie.com'],iframe[src*='ustream.tv'], iframe[src*='google.com/maps']," +
			" iframe[src*='drive.google.com'], iframe[src*='vuhaus.com'], object, embed, .videoarchive," +
			" iframe[src*='googleusercontent.com'], iframe[src*='player.pbs.org']," +
			" iframe[src*='facebook.com/plugins/video.php'], iframe[src*='houstontranstar.org']," +
			" iframe[src*='archive.org/embed'], iframe[src*='jwplayer.com']");
		window.ytPlayers = [];
		var youtube = false;
		if ( document.getElementById('youtube-player') !== null ) {
			youtube = true;
		}
		Array.from(allVideos).forEach((video) => {
			var iframeClass;
			var vidHigh = video.getAttribute('height');
			var vidWide = video.getAttribute('width');
			video.removeAttribute('height');
			video.removeAttribute('width');
			var frameSrc = video.src;
			if ( vidWide == '100%' && vidHigh == '100%' ) {
				var ratio = 1.6667;
			} else {
				var ratio = vidWide/vidHigh;
			}
			if ( frameSrc.indexOf('google.com/maps') !== -1 || frameSrc.indexOf('googleusercontent.com') !== -1 || frameSrc.indexOf('houstontranstar.org') !== -1 ) {
				iframeClass = 'iframe-embed-tall';
			} else {
				if ( frameSrc.indexOf('youtube') !== -1 ) {
					var query = new URL(frameSrc);
					if ( query.search.indexOf('enablejsapi') == -1 ) {
						if (query.search == '') {
							video.src += '?enablejsapi=1';
						} else {
							video.src += '&enablejsapi=1';
						}
					}
					window.ytPlayers.push( video.id );
					youtube = true;
				}
				if ( ratio > 1 ) {
					if( frameSrc.indexOf('player.pbs') !== -1 ) {
						if ( frameSrc.indexOf('ga-livestream') !== -1 ) {
							iframeClass = 'iframe-embed';
						} else {
							iframeClass = 'iframe-embed-pbs';
						}

					} else {
						iframeClass = 'iframe-embed';
					}
				} else {
					iframeClass = 'iframe-embed-vert';
				}
			}
			video.parentNode.classList.add(iframeClass);
		});
		if (youtube) {
			var tag = document.createElement('script');
			tag.src = "https://cdn.hpm.io/assets/js/youtube.js?v=1";
			var firstScriptTag = document.getElementsByTagName('script')[0];
			firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
		}
	};

	hpm.shareHandlers = () => {
		var popOut = document.querySelectorAll(".social-icon button, #top-listen button, .nav-listen-live a, #top-watch button");
		Array.from(popOut).forEach((pop) => {
			pop.addEventListener('click', (e) =>{
				var attr = pop.getAttribute('data-dialog');
				var hrefCheck = pop.getAttribute('data-href');
				if ( hrefCheck.includes('mailto:') ) {
					return true;
				} else {
					if (typeof attr !== typeof undefined && attr !== false) {
						e.preventDefault();
					}
					else {
						e.preventDefault();
						attr = '576:730';
					}
					var size = attr.split(':');
					var text = pop.innerText;
					var myWindow = window.open(hrefCheck, text, "width=" + size[0] + ",height=" + size[1]);
				}
			});
		});
	};

	hpm.audioEmbeds = () => {
		var embeds = document.querySelectorAll('.plyr-audio-embed')
		Array.from(embeds).forEach((emb) => {
			emb.addEventListener('click', (e) => {
				e.preventDefault();
				emb.nextElementSibling.classList.toggle('plyr-audio-embed-active');
			});
		});
		var embC = document.querySelectorAll('.plyr-audio-embed-close')
		Array.from(embC).forEach((emC) => {
			emC.addEventListener('click', () => {
				emC.parentNode.parentNode.classList.remove('plyr-audio-embed-active');
			});
		});
	};

	hpm.localBanners = () => {
		var topBanner = document.querySelectorAll('.top-banner');
		if (topBanner !== null) {
			Array.from(topBanner).forEach((item) => {
				item.addEventListener('click', () => {
					var attr = item.id;
					if ( typeof attr !== typeof undefined && attr !== false) {
						ga('hpmprod.send', 'event', 'Top Banner', 'click', attr);
						ga('hpmRollupprod.send', 'event', 'Top Banner', 'click', attr);
						ga('hpmWebAmpprod.send', 'event', 'Top Banner', 'click', attr);
					}
				});
			});
		}
	}

	hpm.audioPlayers = () => {
		var jsPlay = document.querySelectorAll('.js-player');
		if (jsPlay !== null) {
			const players = Array.from(jsPlay).map(p => new Plyr(p));
			hpm.players = players;
			hpm.players.forEach((player) => {
				player.on('play', (event) => {
					var mediaName = event.detail.plyr.media.currentSrc;
					ga('hpmprod.send', 'event', 'Plyr', 'Play', mediaName);
					ga('hpmRollupprod.send', 'event', 'Plyr', 'Play', mediaName);
					ga('hpmWebAmpprod.send', 'event', 'Plyr', 'Play', mediaName);
				});
				player.on('ended', (event) => {
					var mediaName = event.detail.plyr.media.currentSrc;
					ga('hpmprod.send', 'event', 'Plyr', 'Ended', mediaName);
					ga('hpmRollupprod.send', 'event', 'Plyr', 'Ended', mediaName);
					ga('hpmWebAmpprod.send', 'event', 'Plyr', 'Ended', mediaName);
				});
			});
		}
	};

	hpm.stationIds = {
		'news': {
			'feed': 'https://api.composer.nprstations.org/v1/widget/519131dee1c8f40813e79115/now?format=json&show_song=true',
			'nowPlaying': {},
			'refresh': false,
			'next': false,
			'obj': {}
		},
		'classical': {
			'feed': 'https://api.composer.nprstations.org/v1/widget/51913211e1c8408134a6d347/now?format=json&show_song=true',
			'nowPlaying': {},
			'refresh': false,
			'next': false,
			'obj': {}
		},
		'mixtape': {
			'feed': 'https://s3-us-west-2.amazonaws.com/hpmwebv2/assets/nowplay/mixtape.json',
			'nowPlaying': {},
			'refresh': false,
			'next': false,
			'obj': {}
		},
		'tv81': {
			'feed': 'https://s3-us-west-2.amazonaws.com/hpmwebv2/assets/nowplay/tv8.1.json',
			'nowPlaying': {},
			'refresh': false,
			'next': false,
			'obj': {}
		},
		'tv82': {
			'feed': 'https://s3-us-west-2.amazonaws.com/hpmwebv2/assets/nowplay/tv8.2.json',
			'nowPlaying': {},
			'refresh': false,
			'next': false,
			'obj': {}
		},
		'tv83': {
			'feed': 'https://s3-us-west-2.amazonaws.com/hpmwebv2/assets/nowplay/tv8.3.json',
			'nowPlaying': {},
			'refresh': false,
			'next': false,
			'obj': {}
		},
		'tv84': {
			'feed': 'https://s3-us-west-2.amazonaws.com/hpmwebv2/assets/nowplay/tv8.4.json',
			'nowPlaying': {},
			'refresh': false,
			'next': false,
			'obj': {}
		}
	};
	hpm.npSearch = () => {
		var nowPlay = document.querySelectorAll('.hpm-nowplay');
		Array.from(nowPlay).forEach((np) => {
			var station = np.getAttribute('data-station');
			hpm.stationIds[ station ].refresh = true;
			hpm.stationIds[ station ].next = np.getAttribute('data-upnext');
			hpm.stationIds[ station ].obj = np;
		});
		if ( document.body.classList.contains('page-template-page-listen') ) {
			hpm.npDataDownload();
		}
		timeOuts.push(setInterval('hpm.npDataDownload()',60000));
	};
	hpm.npDataDownload = () => {
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
	hpm.npUpdateData = (data, station) => {
		if (JSON.stringify(data) !== JSON.stringify(hpm.stationIds[station]['nowPlaying']) ) {
			hpm.stationIds[station]['nowPlaying'] = data;
			let hpmUpdate = new CustomEvent('hpm:npUpdate', {
				'detail': {
					'updated': station
				}
			});
			document.dispatchEvent(hpmUpdate);
		}
	};
	document.addEventListener('hpm:npUpdate', (event) => {
		var station = event['detail']['updated'];
		hpm.npUpdateHtml(hpm.stationIds[ station ]['obj'], station, hpm.stationIds[ station ]['next']);
	});
	hpm.npUpdateHtml = (object,station,next) => {
		var output = '';
		if ( !station.startsWith('Podcast||') ) {
			var data = hpm.stationIds[station]['nowPlaying'];
		}
		if (next == 'true') {
			output = '<h2>On Now</h2>';
		}
		if ( station.startsWith('tv') ) {
			if (next == 'true') {
				output += '<ul>';
				for ( var al = 0; al < data['airlist'].length; al++ ) {
					if (al == 1) {
						output += '</ul><h2>Coming Up</h2><ul>'
					}
					var airStart = new Date(data['airlist'][al]['air-start']);
					output += '<li>'+
						airStart.toLocaleTimeString([],{hour:'numeric',minute: '2-digit' }) +
						': ' + data['airlist'][al]['version']['series']['series-title'] + '</li>';
				}
				output += '</ul>';
			} else {
				output += '<h3>'+data['airlist'][0]['version']['series']['series-title']+'</h3>';
			}
		} else if ( station === 'mixtape' ) {
			output += '<h3>'+data[0]+' - '+data[1]+'</h3>';
		} else if ( station.startsWith('Podcast||') ) {
			var stationArr = station.split("||");
			output = '<p>' + stationArr[1] + '</p><h3>' + stationArr[2] + '</h3>';
		} else {
			if ( typeof data.onNow.song !== 'object') {
				output += '<h3>'+data.onNow.program.name+'</h3>';
			} else {
				output += '<h3>';
				if (data.onNow.song.composerName.length > 0) {
					output += data.onNow.song.composerName + ' - ';
				}
				output += data.onNow.song.trackName.replace('&','&amp;') + "</h3>";
			}
			if (next == 'true') {
				output += '<p>Up Next</p><ul><li>'+amPm(data.nextUp[0].fullstart)+': '+data.nextUp[0].program.name+'</li></ul>';
			}
		}
		if (object == 'jpp') {
			if ( station == jpp.prefStream ) {
				jpp.elements.nowPlaying.innerHTML = '<p>Houston Public Media '+station+'</p>'+output;
			} else if ( station.startsWith('Podcast||') ) {
				jpp.elements.nowPlaying.innerHTML = output;
			}
		}
		if (object !== 'jpp') {
			object.innerHTML = output;
		}
	};

	document.addEventListener('DOMContentLoaded', () => {
		hpm.navHandlers();
		hpm.videoHandlers();
		hpm.shareHandlers();
		hpm.audioEmbeds();
		hpm.npSearch();
		hpm.audioPlayers();
		hpm.localBanners();
	});
}
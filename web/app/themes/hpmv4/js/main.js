let hpmGetCookie = (cname) => {
	let name = cname + "=";
	let decodedCookie = decodeURIComponent(document.cookie);
	let ca = decodedCookie.split(';');
	for(let i = 0; i <ca.length; i++) {
		let c = ca[i];
		while (c.charAt(0) === ' ') {
			c = c.substring(1);
		}
		if (c.indexOf(name) === 0) {
			return c.substring(name.length, c.length);
		}
	}
	return null;
}
let timeOuts = [];
let hpmSetCookie = (cname, cvalue, exhours) => {
	let d = new Date();
	d.setTime(d.getTime() + (exhours*60*60*1000));
	let expires = 'expires=' + d.toUTCString();
	document.cookie = cname + '=' + cvalue + ';' + expires + ';path=/;SameSite=lax;Secure;';
};

if ( hpmGetCookie('inapp') !== null ) {
	let css = document.createElement('style');
	css.appendChild(document.createTextNode('#foot-banner, #top-donate, #masthead nav#site-navigation .nav-top.nav-donate, .top-banner { display: none; }'));
	document.getElementsByTagName("head")[0].appendChild(css);
}

let amPm = (timeString) => {
	let hourEnd = timeString.indexOf(":");
	let H = +timeString.substr(0, hourEnd);
	let h = H % 12 || 12;
	let ampm = (H < 12 || H === 24) ? " AM" : " PM";
	return h + timeString.substr(hourEnd, 3) + ampm;
};

let hpm = {};

hpm.navHandlers = () => {
	let siteNav = document.querySelector('nav#site-navigation');
	let buttonDiv = document.querySelectorAll('div[tab-index="0"]');
	let topMenu = document.querySelector('#top-mobile-menu');
	let closeMenu = document.querySelector('#top-mobile-close');
	let topSearch = document.querySelector('#top-search');
	let searchInput = document.querySelector('#top-search > form > input[type=search]');
	if ( siteNav !== null ) {
		let menuWithChildren = siteNav.querySelectorAll('li.menu-item-has-children');
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
						if (event.currentTarget.getAttribute('aria-expanded') === 'true' ) {
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
	let allVideos = document.querySelectorAll("iframe[src*='vimeo.com'], iframe[src*='youtube.com']," +
		" iframe[src*='youtube-nocookie.com'],iframe[src*='ustream.tv'], iframe[src*='google.com/maps']," +
		" iframe[src*='drive.google.com'], iframe[src*='vuhaus.com'], object, embed, .videoarchive," +
		" iframe[src*='googleusercontent.com'], iframe[src*='player.pbs.org']," +
		" iframe[src*='facebook.com/plugins/video.php'], iframe[src*='houstontranstar.org']," +
		" iframe[src*='archive.org/embed'], iframe[src*='jwplayer.com']");
	window.ytPlayers = [];
	let youtube = false;
	if ( document.getElementById('youtube-player') !== null ) {
		youtube = true;
	}
	Array.from(allVideos).forEach((video) => {
		let iframeClass;
		let vidHigh = video.getAttribute('height');
		let vidWide = video.getAttribute('width');
		video.removeAttribute('height');
		video.removeAttribute('width');
		let frameSrc = video.src;
		let ratio = vidWide/vidHigh;
		if ( vidWide === '100%' && vidHigh === '100%' ) {
			ratio = 1.6667;
		}
		if ( typeof frameSrc !== 'string' ) {
			return false;
		}
		if ( frameSrc.indexOf('google.com/maps') !== -1 || frameSrc.indexOf('googleusercontent.com') !== -1 || frameSrc.indexOf('houstontranstar.org') !== -1 ) {
			iframeClass = 'iframe-embed-tall';
		} else {
			if ( frameSrc.indexOf('youtube') !== -1 ) {
				let query = new URL(frameSrc);
				if ( query.search.indexOf('enablejsapi') === -1 ) {
					if (query.search === '') {
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
		let tag = document.createElement('script');
		tag.src = "https://cdn.houstonpublicmedia.org/assets/js/youtube.js?v=3";
		let firstScriptTag = document.getElementsByTagName('script')[0];
		firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
	}
};

hpm.shareHandlers = () => {
	let popOut = document.querySelectorAll(".service-icon button, #top-listen button, .nav-listen-live a, #top-watch button");
	Array.from(popOut).forEach((pop) => {
		pop.addEventListener('click', (e) => {
			let attr = pop.getAttribute('data-dialog');
			let hrefCheck = pop.getAttribute('data-href');
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
				let size = attr.split(':');
				let text = pop.innerText;
				window.open(hrefCheck, text, "width=" + size[0] + ",height=" + size[1]);
			}
		});
	});
};

hpm.audioEmbeds = () => {
	let embeds = document.querySelectorAll('.plyr-audio-embed')
	Array.from(embeds).forEach((emb) => {
		emb.addEventListener('click', (e) => {
			e.preventDefault();
			emb.nextElementSibling.classList.toggle('plyr-audio-embed-active');
		});
	});
	let embC = document.querySelectorAll('.plyr-audio-embed-close')
	Array.from(embC).forEach((emC) => {
		emC.addEventListener('click', () => {
			emC.parentNode.parentNode.classList.remove('plyr-audio-embed-active');
		});
	});
};

hpm.localBanners = () => {
	let topBanner = document.querySelectorAll('.top-banner');
	if (topBanner !== null) {
		Array.from(topBanner).forEach((item) => {
			item.addEventListener('click', () => {
				let attr = item.id;
				if ( typeof attr !== typeof undefined && attr !== false) {
					gtag('event', 'top_banner', {'event_label': attr,'event_category': 'click'});
				}
			});
		});
	}
}

hpm.audioPlayers = () => {
	let jsPlay = document.querySelectorAll('.js-player');
	if (jsPlay !== null) {
		hpm.players = Array.from(jsPlay).map(p => new Plyr(p));
		hpm.players.forEach((player) => {
			player.on('play', (event) => {
				let mediaName = event.detail.plyr.media.currentSrc;
				gtag('event', 'plyr', {'event_label': mediaName,'event_category': 'play'});
			});
			player.on('ended', (event) => {
				let mediaName = event.detail.plyr.media.currentSrc;
				gtag('event', 'plyr', {'event_label': mediaName,'event_category': 'ended'});
			});
		});
	}
};

hpm.stationIds = {
	'news': {
		'station': {
			'type': 'radio',
			'id': 0
		},
		'refresh': false,
		'obj': {}
	},
	'classical': {
		'station': {
			'type': 'radio',
			'id': 1
		},
		'refresh': false,
		'obj': {}
	},
	'thevibe': {
		'station': {
			'type': 'radio',
			'id': 2
		},
		'refresh': false,
		'obj': {}
	},
	'tv81': {
		'station': {
			'type': 'tv',
			'id': 0
		},
		'refresh': false,
		'obj': {}
	},
	'tv82': {
		'station': {
			'type': 'tv',
			'id': 1
		},
		'refresh': false,
		'obj': {}
	},
	'tv83': {
		'station': {
			'type': 'tv',
			'id': 2
		},
		'refresh': false,
		'obj': {}
	},
	'tv84': {
		'station': {
			'type': 'tv',
			'id': 3
		},
		'refresh': false,
		'obj': {}
	},
	'tv86': {
		'station': {
			'type': 'tv',
			'id': 4
		},
		'refresh': false,
		'obj': {}
	}
};
hpm.npSearch = () => {
	let nowPlay = document.querySelectorAll('.hpm-nowplay');
	let checkNow = false;
	Array.from(nowPlay).forEach((np) => {
		let station = np.getAttribute('data-station');
		if (station !== null ) {
			checkNow = true;
			hpm.stationIds[ station ].refresh = true;
			hpm.stationIds[ station ].obj = np;
		}
	});
	if ( checkNow ) {
		hpm.npDataDownload();
	}
	timeOuts.push(setInterval('hpm.npDataDownload()',60000));
};
hpm.npDataDownload = () => {
	fetch('https://cdn.houstonpublicmedia.org/assets/nowplay/all.json')
		.then((response) => response.json())
		.then((data) => {
			for (let st in hpm.stationIds) {
				if ( hpm.stationIds[st].refresh ) {
					let output = '';
					let current = data[ hpm.stationIds[st].station.type ][ hpm.stationIds[st].station.id ];
					if ( hpm.stationIds[st].station.type === 'tv' ) {
						output += '<h3>' + current.artist + '</h3>';
					} else {
						if ( current.artist === current.album ) {
							output += '<h3>' + current.title + '</h3>';
						} else {
							output += '<h3>' + current.artist + ' - ' + current.title + "</h3>";
						}
					}
					if (hpm.stationIds[st].obj === 'jpp') {
						if ( st === jpp.prefStream ) {
							jpp.elements.nowPlaying.innerHTML = '<div><p>Houston Public Media ' + st + '</p>' + output + '</div>';
						}
						document.getElementById('menu-station-' + st).innerHTML = '<p>Houston Public Media ' + st + '</p>' + output;
					} else {
						hpm.stationIds[st].obj.innerHTML = output;
					}
				}
			}
		});
};

document.addEventListener('DOMContentLoaded', () => {
	hpm.navHandlers();
	hpm.videoHandlers();
	hpm.shareHandlers();
	hpm.audioEmbeds();
	hpm.npSearch();
	hpm.audioPlayers();
	hpm.localBanners();
	let navWrap = document.querySelector('.navigation-wrap');
	if ( navWrap !== null ) {
		let headerHeight = navWrap.getBoundingClientRect().height;
		document.addEventListener("scroll", () => {
			if (window.scrollY > headerHeight) {
				if (!document.body.classList.contains('sticky-nav')) {
					document.body.classList.add('sticky-nav');
				}
			} else {
				if (document.body.classList.contains('sticky-nav')) {
					document.body.classList.remove('sticky-nav');
				}
			}
		});
	}
});
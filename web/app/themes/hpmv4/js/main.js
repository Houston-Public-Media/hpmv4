let getCookie = (cname) => {
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
let setCookie = (cname, cvalue, exhours) => {
	let d = new Date();
	d.setTime(d.getTime() + (exhours*60*60*1000));
	let expires = 'expires=' + d.toUTCString();
	document.cookie = cname + '=' + cvalue + ';' + expires + ';path=/;SameSite=lax;Secure;';
};

if ( getCookie('inapp') !== null ) {
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

// hpm.donateAB = () => {
// 	let donateButton = document.querySelector('#top-listen > .btn-donate');
// 	if (donateButton !== null) {
// 		let rand = Math.floor(Math.random() * 20);
// 		var option = "";
// 		if ( rand > 9 ) {
// 			donateButton.href = "https://donate.houstonpublicmedia.org/form-name-1?utm_source=donate-button-option-a&utm_content=donate-button-option-a&utm_campaign=website-donate-button-ab&utm_medium=donate-button";
// 			option = "a";
// 		} else {
// 			donateButton.href = "https://donate.houstonpublicmedia.org/form-name-2?utm_source=donate-button-option-b&utm_content=donate-button-option-b&utm_campaign=website-donate-button-ab&utm_medium=donate-button";
// 			option = "b";
// 		}
// 		donateButton.addEventListener('click', () => {
// 			gtag('event', 'donate_button_test', {'event_label': 'donate_test_' + option,'event_category': 'click'});
// 		});
// 	}
// }

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
	'thevibe': {
		'feed': 'https://s3-us-west-2.amazonaws.com/hpmwebv2/assets/nowplay/thevibe.json',
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
	},
	'tv86': {
		'feed': 'https://s3-us-west-2.amazonaws.com/hpmwebv2/assets/nowplay/tv8.6.json',
		'nowPlaying': {},
		'refresh': false,
		'next': false,
		'obj': {}
	}
};
hpm.npSearch = () => {
	let nowPlay = document.querySelectorAll('.hpm-nowplay');
	Array.from(nowPlay).forEach((np) => {
		let station = np.getAttribute('data-station');
		if (station !== null ) {
			hpm.stationIds[ station ].refresh = true;
			hpm.stationIds[ station ].next = np.getAttribute('data-upnext');
			hpm.stationIds[ station ].obj = np;
		}
	});
	if ( document.body.classList.contains('page-template-page-listen') ) {
		hpm.npDataDownload();
	}
	timeOuts.push(setInterval('hpm.npDataDownload()',60000));
};
hpm.npDataDownload = () => {
	for (let st in hpm.stationIds) {
		if ( hpm.stationIds[st].refresh ) {
			if ( hpm.stationIds[st].refresh ) {
				fetch(hpm.stationIds[st].feed)
					.then((response) => response.json())
					.then((data) => {
						hpm.npUpdateData(data,st);
					});
			}
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
	let station = event['detail']['updated'];
	hpm.npUpdateHtml(hpm.stationIds[ station ]['obj'], station, hpm.stationIds[ station ]['next']);
});
hpm.npUpdateHtml = (object,station,next) => {
	let output = '';
	let data;
	data = hpm.stationIds[station]['nowPlaying'];
	if (next === 'true') {
		output = '<h2>On Now</h2>';
	}
	if ( station.startsWith('tv') ) {
		if (next === 'true') {
			output += '<ul>';
			for ( let al = 0; al < data['airlist'].length; al++ ) {
				if (al === 1) {
					output += '</ul><h2>Coming Up</h2><ul>'
				}
				let airStart = new Date(data['airlist'][al]['air-start']);
				output += '<li>'+
					airStart.toLocaleTimeString([],{hour:'numeric',minute: '2-digit' }) +
					': ' + data['airlist'][al]['version']['series']['series-title'] + '</li>';
			}
			output += '</ul>';
		} else {
			output += '<h3>'+data['airlist'][0]['version']['series']['series-title']+'</h3>';
		}
	} else if ( station === 'thevibe' ) {
		output += '<h3>'+data.artist+' - '+data.song+'</h3>';
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
		if (next === 'true') {
			output += '<p>Up Next</p><ul><li>'+amPm(data.nextUp[0].start_time)+': '+data.nextUp[0].program.name+'</li></ul>';
		}
	}
	if (object === 'jpp') {
		if ( station === jpp.prefStream ) {
			jpp.elements.nowPlaying.innerHTML = '<div><p>Houston Public Media ' + station + '</p>' + output + '</div>';
			if ( station === 'news' ) {
				jpp.elements.nowPlaying.innerHTML += '<div class="playing-next"><p>Coming up @ ' + amPm(data.nextUp[0].start_time) + '</p><h3>' + data.nextUp[0].program.name + '</h3></div>';
			}
		}
		document.getElementById('menu-station-'+station).innerHTML = '<p>Houston Public Media ' + station + '</p>' + output;
	}
	if (object !== 'jpp') {
		object.innerHTML = output;
	}
};

// document.addEventListener("mouseup", (event) => {
// 	let selection = document.getSelection();
// 	let selectionText = document.getSelection ? document.getSelection().toString() : document.selection.createRange().toString();
// 	if ( selectionText.length > 0 ) {
// 		let copyLink = document.querySelector('#copyLink-container');
// 		if ( copyLink !== null ) {
// 			copyLink.remove();
// 		}
// 		let parent = selection.baseNode.parentElement;
// 		parent.insertAdjacentHTML( 'beforebegin', '<div id="copyLink-container"><span id="copyLink" data-text="' + encodeURIComponent( selectionText ) + '" onclick="copyToClip()">Copy 🔗 to Clipboard</span></div>' );
// 	}
// });
// let copyToClip = () => {
// 	let copyLink = document.querySelector('#copyLink');
// 	let copyLinkContain = document.querySelector('#copyLink-container');
// 	let selectionText = copyLink.getAttribute('data-text');
// 	let currentLink = window.location.href + "#:~:text=" + selectionText;
// 	console.log('Copied to clipboard: ' + currentLink);
// 	navigator.clipboard.writeText(currentLink);
// 	copyLink.innerHTML = "Copied!";
// 	copyLinkContain.classList.add('fadeout');
// 	setTimeout(function(){ document.querySelector('#copyLink-container').remove() }, 5000);
// }

document.addEventListener('DOMContentLoaded', () => {
	hpm.navHandlers();
	hpm.videoHandlers();
	hpm.shareHandlers();
	hpm.audioEmbeds();
	hpm.npSearch();
	hpm.audioPlayers();
	hpm.localBanners();
	// hpm.donateAB();
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
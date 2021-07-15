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

const hpm = {};
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
	var navChild = document.querySelectorAll('.nav-top.menu-item-has-children');
	var navArray = Array.from(navChild);
	navArray.forEach((nC) => {
		nC.addEventListener('click', (event) => {
			navArray.forEach((item) => {
				if (nC !== item) {
					item.classList.remove('nav-active');
				}
			});
			nC.classList.toggle('nav-active');
		});
	});
	var navBack = document.querySelectorAll('li.nav-back');
 	Array.from(navBack).forEach((nB) => {
		nB.addEventListener('click', (event) => {
			nB.classList.remove('nav-active');
		});
	});

	if (!document.body.classList.contains('single-embeds')) {
		var topMenu = document.querySelector('#top-mobile-menu');
		var topSearch = document.querySelector('#top-search .fa-search');
		if ( topMenu !== null ) {
			topMenu.addEventListener('click', (event) => {
				if (window.innerWidth < 801 || document.body.classList.contains('page-template-page-listen')) {
					if (document.body.classList.contains('nav-active-menu')) {
						topMenu.innerHTML = '<span class="fas fa-bars" aria-hidden="true"></span><br /><span class="top-mobile-text">Menu</span>';
						document.body.classList.remove('nav-active-menu');
					} else {
						topMenu.innerHTML = '<span class="fas fa-times" aria-hidden="true"></span><br /><span class="top-mobile-text">Close</span>';
						document.body.classList.add('nav-active-menu');
					}
				} else {
					return false;
				}
			});
		}
		if (topSearch !== null) {
			topSearch.addEventListener('click', (event) => {
				var sForm = document.querySelector('#top-search .search-form');
				var sField = document.querySelector('#top-search .search-field');
				if ( window.innerWidth > 800 ) {
					if ( !sForm.classList.contains('search-active') ) {
						sField.focus();
					}
					sForm.classList.toggle('search-active');
				} else {
					return false;
				}
			});
		}
		var topSched = document.querySelector('#top-schedule .top-schedule-label button');
		if (topSched !== null) {
			topSched.addEventListener('click', (e) => {
				e.preventDefault();
				document.querySelector('#top-schedule .top-schedule-link-wrap').classList.toggle('top-sched-active');
			});
		}
	}
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
		var ratio = vidWide/vidHigh;
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
	var popOut = document.querySelectorAll(".article-share-icon button, #top-listen button, .nav-listen-live a, #top-watch button");
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
	window.addEventListener('scroll', () => {
		hpm.shareButtons();
	});
	window.addEventListener('resize', () => {
		hpm.shareButtons();
	});
};

hpm.shareButtons = () => {
	var share = document.querySelector('#article-share');
	if (share !== null) {
		var entry = document.querySelector('#main article .entry-content');
		var footer = document.querySelector('footer#colophon');
		var post = document.querySelector('#main article');
		if (window.innerWidth > 840) {
			var shareD = share.getBoundingClientRect();
			var entryD = entry.getBoundingClientRect();
			var footD = footer.getBoundingClientRect();
			var postD = post.getBoundingClientRect();
			if (entryD.top < 0 && footD.top > window.innerHeight) {
				if ( !share.classList.contains('fixed') ) {
					share.classList.add('fixed');
				}
				var newLeft = postD.left + (1.875 * 16);
				if (shareD.left !== newLeft) {
					share.style.left = newLeft + 'px';
				}
			} else {
				share.classList.remove('fixed');
				share.removeAttribute('style');
			}
		} else {
			share.classList.remove('fixed');
				share.removeAttribute('style');
		}
	}
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

hpm.contentToggles = () => {
	var passportFaq = document.querySelectorAll('.passport-faq');
	Array.from(passportFaq).forEach((pF) => {
		pF.addEventListener('click', () => {
			if (pF.classList.contains('passport-active')) {
				pF.classList.remove('passport-active');
				pF.nextElementSibling.style.display = 'none';
			} else {
				pF.classList.add('passport-active');
				pF.nextElementSibling.style.display = 'block';
			}
		});
	});
	var passportDevice = document.querySelectorAll('#passport-devices li');
	Array.from(passportDevice).forEach((pD) => {
		pD.addEventListener('click', () => {
			var dat = pD.getAttribute('data-device');
			if (pD.classList.contains('passport-active')){
				return false;
			} else {
				Array.from(passportDevice).forEach((ppL) => {
					ppL.classList.remove('passport-active');
				});
				Array.from(document.querySelectorAll('.passport-device')).forEach((ppD) => {
					ppD.style.display = "none";
				});
				pD.classList.add('passport-active');
				document.querySelector('#'+dat).style.display = "block";
			}
		});
	});
	var infoT = document.querySelectorAll('.info-toggle');
	Array.from(infoT).forEach((inf) => {
		inf.addEventListener('click', () => {
			if ( inf.classList.contains('info-toggle-active') ) {
				inf.classList.remove('info-toggle-active');
				inf.nextElementSibling.style.display = 'none';
			} else {
				inf.classList.add('info-toggle-active');
				inf.nextElementSibling.style.display = 'block';
			}
		});
	});
};

hpm.stationIds = {
	'news': {
		'feed': 'https://api.composer.nprstations.org/v1/widget/519131dee1c8f40813e79115/now?format=json&show_song=true',
		'nowPlaying': {}
	},
	'classical': {
		'feed': 'https://api.composer.nprstations.org/v1/widget/51913211e1c8408134a6d347/now?format=json&show_song=true',
		'nowPlaying': {}
	},
	'mixtape': {
		'feed': 'https://s3-us-west-2.amazonaws.com/hpmwebv2/assets/nowplay/mixtape.json',
		'nowPlaying': {}
	},
	'tv81': {
		'feed': 'https://s3-us-west-2.amazonaws.com/hpmwebv2/assets/nowplay/tv8.1.json',
		'nowPlaying': {}
	},
	'tv82': {
		'feed': 'https://s3-us-west-2.amazonaws.com/hpmwebv2/assets/nowplay/tv8.2.json',
		'nowPlaying': {}
	},
	'tv83': {
		'feed': 'https://s3-us-west-2.amazonaws.com/hpmwebv2/assets/nowplay/tv8.3.json',
		'nowPlaying': {}
	},
	'tv84': {
		'feed': 'https://s3-us-west-2.amazonaws.com/hpmwebv2/assets/nowplay/tv8.4.json',
		'nowPlaying': {}
	}
};
hpm.stationLoad = {};
hpm.npSearch = () => {
	hpm.stationLoad = {};
	var nowPlay = document.querySelectorAll('.hpm-nowplay');
	Array.from(nowPlay).forEach((np) => {
		var station = np.getAttribute('data-station');
		var next = np.getAttribute('data-upnext');
		hpm.stationLoad[ station ] = { 'next': next, 'obj': np };
	});
	hpm.npDataDownload();
	timeOuts.push(setInterval('hpm.npDataDownload()',60000));
};
hpm.npDataDownload = () => {
	for (let st in hpm.stationLoad) {
		hpm.getJSON( hpm.stationIds[st].feed, (err, data) => {
			if (err !== null) {
				console.log(err);
			} else {
				hpm.npUpdateData(data,st);
			}
		});
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
	if ( typeof hpm.stationLoad[ station ] == 'object' ) {
		hpm.npUpdateHtml(hpm.stationLoad[ station ]['obj'], station, hpm.stationLoad[ station ]['next']);
	}
});
hpm.npUpdateHtml = (object,station,next) => {
	var output = '';
	var data = hpm.stationIds[station]['nowPlaying'];
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
	object.innerHTML = output;
};

document.addEventListener('DOMContentLoaded', () => {
	hpm.navHandlers();
	hpm.videoHandlers();
	hpm.shareHandlers();
	hpm.audioEmbeds();
	hpm.contentToggles();
	hpm.npSearch();
	hpm.audioPlayers();
});
document.addEventListener('turbo:before-fetch-response', () => {
	if ( timeOuts.length > 0 ) {
		Array.from(timeOuts).forEach((item) => {
			clearInterval(item);
		});
	}
	hpm.npSearch();
});

document.addEventListener('DOMContentLoaded', () => {
	var setupOverlay = (overlay,target) => {
		var contain = document.createElement('div');
		contain.classList.add('credits-container');
		Array.from(target.classList).forEach((tCl) => {
			contain.classList.add(tCl);
		});
		var styles = [];
		styles.push('width: ' + target.width + 'px' );
		styles.push('margin-right: ' + (target.style['margin-right'] == '' ? '0' : target.style['margin-right']) + 'px');
		styles.push('margin-left: ' + (target.style['margin-left'] == '' ? '0' : target.style['margin-left']) + 'px');
		styles.push('margin-bottom: ' + (target.style['margin-bottom'] == '' ? '0' : target.style['margin-bottom']) + 'px');
		styles.push('border-bottom-left-radius: ' + (target.style['border-bottom-left-radius'] == '' ? '0' : target.style['border-bottom-left-radius']) + 'px');
		styles.push('border-bottom-right-radius: ' + (target.style['border-bottom-right-radius'] == '' ? '0' : target.style['border-bottom-right-radius']) + 'px');
		overlay.setAttribute('style',styles.join('; '));
		var parent = target.parentNode;
		contain.innerHTML = target.outerHTML + overlay.outerHTML;
		if (parent.nodeName == 'a') {
			parent.outerHTML = contain.outerHTML;
		} else {
			target.outerHTML = contain.outerHTML;
		}
	};
	var credits = document.querySelectorAll('.credits-overlay');
	var targets = [];
	Array.from(credits).forEach((cred) => {
		targets.push( {'target': 'img' + cred.getAttribute('data-target'), 'overlay': cred } );
	});
	targets.forEach((target) => {
		var t = document.querySelector(target.target);
		if (t !== null) {
			setupOverlay( target.overlay, t );
		}
	});
});
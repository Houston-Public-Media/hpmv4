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
		document.querySelector('#top-mobile-menu').addEventListener('click', (event) => {
			if (window.innerWidth < 801 || document.body.classList.contains('page-template-page-listen')) {
				if (document.body.classList.contains('nav-active-menu')) {
					this.innerHTML = '<span class="fas fa-bars" aria-hidden="true"></span><br /><span class="top-mobile-text">Menu</span>';
					document.body.classList.remove('nav-active-menu');
				} else {
					this.innerHTML = '<span class="fas fa-times" aria-hidden="true"></span><br /><span class="top-mobile-text">Close</span>';
					document.body.classList.add('nav-active-menu');
				}
			} else {
				return false;
			}
		});
		document.querySelector('#top-search .fa-search').addEventListener('click', (event) => {
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
		var topSched = document.querySelector('#top-schedule .top-schedule-label button');
		if (topSched !== null) {
			topSched.addEventListener('click', (e) => {
				e.preventDefault();
				document.querySelector('#top-schedule .top-schedule-link-wrap').classList.toggle('top-sched-active');
			});
		}
		var passport = document.querySelector('.nav-passport > .nav-item-head-main');
		if (passport !== null) {
			passport.innerHTML = '<img src="https://cdn.hpm.io/assets/images/icons/Passport-Icon-Head.png" class="nav-passport-icon"> Passport';
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
		tag.src = "https://cdn.hpm.io/assets/js/youtube.js";
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
	if (document.body.classList.contains('single-post')) {
		var share = document.querySelector('#article-share');
		if (typeof share == 'object' ) {
			var e = share.getBoundingClientRect();
			var p = share.parentNode.getBoundingClientRect().height;
			var o = p - e.height;
			window.addEventListener('scroll', () => {
				if (window.innerWidth > 800) {
					var a = window.scrollY;
					if (a > e.top) {
						var k = a - e.top;
						if (k < o) {
							share.style.top = k + 10 + 'px';
						}
						else if (k >= o) {
							share.style.top = o + 'px';
						}
					}
					else {
						share.style.top = 0 + 'px';
					}
				}
			});
		}
	}
};

hpm.audioEmbeds = () => {
	var embeds = document.querySelectorAll('.jp-audio-embed')
	Array.from(embeds).forEach((emb) => {
		emb.addEventListener('click', (e) => {
			e.preventDefault();
			var parentID = emb.parentNode.id;
			document.querySelector('#'+parentID+'-popup').classList.add('jp-audio-embed-active');
		});
	});
	var embC = document.querySelectorAll('.jp-audio-embed-close')
	Array.from(embC).forEach((emC) => {
		emC.addEventListener('click', () => {
			var parentID = emC.parentNode.parentNode.id;
			document.querySelector('#'+parentID).classList.remove('jp-audio-embed-active');
		});
	});
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
	'news': 'https://api.composer.nprstations.org/v1/widget/519131dee1c8f40813e79115/now?format=json&show_song=true',
	'classical': 'https://api.composer.nprstations.org/v1/widget/51913211e1c8408134a6d347/now?format=json&show_song=true',
	'mixtape': 'https://s3-us-west-2.amazonaws.com/hpmwebv2/assets/nowplay/mixtape.json',
	'tv81': 'https://s3-us-west-2.amazonaws.com/hpmwebv2/assets/nowplay/tv8.1.json',
	'tv82': 'https://s3-us-west-2.amazonaws.com/hpmwebv2/assets/nowplay/tv8.2.json',
	'tv83': 'https://s3-us-west-2.amazonaws.com/hpmwebv2/assets/nowplay/tv8.3.json',
	'tv84': 'https://s3-us-west-2.amazonaws.com/hpmwebv2/assets/nowplay/tv8.4.json'
}
hpm.stationLoad = [];
var hpmNowPlaying = (station,next) => {
	let check = {
		'station': station,
		'next': next
	};
	if (hpm.stationLoad.length > 0) {
		let match = false;
		for (let s in hpm.stationLoad) {
			if ( hpm.stationLoad[s].station == station && hpm.stationLoad[s].next == next ) {
				match = true;
			}
		}
		if (!match) {
			hpm.stationLoad.push(check);
		}
	} else {
		hpm.stationLoad.push(check);
	}
	if (hpm.stationLoad.length == 1) {
		document.addEventListener("DOMContentLoaded", () => {
			hpm.updateStations();
			timeOuts.push(setInterval("hpm.updateStations()", 60000));
		});
	}
}
hpm.updateStations = () => {
	for (let s in hpm.stationLoad) {
		if ( hpm.stationLoad[s].station !== 'all' ) {
			hpm.getJSON( hpm.stationIds[hpm.stationLoad[s].station], (err, data) => {
				if (err !== null) {
					console.log(err);
				} else {
					hpm.updateData(data,hpm.stationLoad[s].station,hpm.stationLoad[s].next);
				}
			});
		} else {
			for (let st in hpm.stationIds) {
				hpm.getJSON( hpm.stationIds[st], (err, data) => {
					if (err !== null) {
						console.log(err);
					} else {
						hpm.updateData(data,st,hpm.stationLoad[s].next);
					}
				});
			}
			/* hpm.getJSON( hpm.stationIds['news'], (err, data) => {
				if (err !== null) {
					console.log(err);
				} else {
					hpm.updateData(data,'news',hpm.stationLoad[s].next);
				}
			});
			hpm.getJSON( hpm.stationIds['classical'], (err, data) => {
				if (err !== null) {
					console.log(err);
				} else {
					hpm.updateData(data,'classical',hpm.stationLoad[s].next);
				}
			});
			hpm.getJSON( hpm.stationIds['mixtape'], (err, data) => {
				if (err !== null) {
					console.log(err);
				} else {
					hpm.updateData(data,'mixtape',hpm.stationLoad[s].next);
				}
			});
			hpm.getJSON( hpm.stationIds['tv81'], (err, data) => {
				if (err !== null) {
					console.log(err);
				} else {
					hpm.updateData(data,'tv81',hpm.stationLoad[s].next);
				}
			});
			hpm.getJSON( hpm.stationIds['tv82'], (err, data) => {
				if (err !== null) {
					console.log(err);
				} else {
					hpm.updateData(data,'tv82',hpm.stationLoad[s].next);
				}
			});
			hpm.getJSON( hpm.stationIds['tv83'], (err, data) => {
				if (err !== null) {
					console.log(err);
				} else {
					hpm.updateData(data,'tv83',hpm.stationLoad[s].next);
				}
			});
			hpm.getJSON( hpm.stationIds['tv84'], (err, data) => {
				if (err !== null) {
					console.log(err);
				} else {
					hpm.updateData(data,'tv84',hpm.stationLoad[s].next);
				}
			}); */
		}
	}
};

hpm.updateData = (data,station,next) => {
	var output = '';
	if (next) {
		output = '<h2>On Now</h2>';
	}
	if ( station.startsWith('tv') ) {
		if (next) {
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
		output = '<h3>'+data[0]+' - '+data[1]+'</h3><p>Album: '+data[2]+'</p>';
	} else {
		if ( typeof data.onNow.song !== 'object') {
			output = '<h3>'+data.onNow.program.name+'</h3>';
		} else {
			var descs = [];
			if (data.onNow.song.composerName.length > 0) {
				descs.push("Composer: "+data.onNow.song.composerName );
			}
			if (data.onNow.song.conductor.length > 0) {
				descs.push("Conductor: "+data.onNow.song.conductor);
			}
			if (data.onNow.song.copyright.length > 0 && data.onNow.song.catalogNumber.length > 0) {
				descs.push("Catalog Number: "+data.onNow.song.copyright+" "+data.onNow.song.catalogNumber);
			}
			extra = descs.join(', ');
			output = "<h3>"+data.onNow.song.trackName.replace('&','&amp;')+"</h3><p>"+extra+"</p>";
		}
		if (next) {
			output += '<p>Up Next</p><ul><li>'+amPm(data.nextUp[0].fullstart)+': '+data.nextUp[0].program.name+'</li></ul>';
		}
	}
	var nps;
	if (next) {
		nps = document.querySelectorAll('.nowplay-'+station+'-next');
	} else {
		nps = document.querySelectorAll('.nowplay-'+station);
	}
	for (var n in nps) {
		nps[n].innerHTML = output;
	}
};
document.addEventListener('DOMContentLoaded', () => {
	hpm.navHandlers();
	hpm.videoHandlers();
	hpm.shareHandlers();
	hpm.audioEmbeds();
	hpm.contentToggles();
});

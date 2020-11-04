function parameters($) {
	var bod = $('body');
	var siteNav = $('#site-navigation');
	window.wide = $(window).width();
	window.high = $(window).height();
	window.mastHigh = $('#masthead').height();
	window.bodyHigh = $('#page').height();
	window.topMobileMenu = $('#top-mobile-menu').outerWidth();
	window.move = wide - topMobileMenu;
	window.totalHigh = mastHigh + bodyHigh;
	window.listenLive = bod.hasClass('page-template-page-listen');

	if (wide < 801 || listenLive === true )
	{
		siteNav.width(move);
	} else {
		bod.removeClass('nav-active-menu').removeAttr('style');
		siteNav.removeAttr('style');
	}
	if (navigator.userAgent.toLowerCase().indexOf('firefox') > -1) {
		$('.felix-type-b > .thumbnail-wrap').each(function() {
			var felixWide = $(this).width();
			$(this).css('padding-bottom', felixWide/1.5+'px');
		});
		$('.national-image, .related-image').each(function() {
			var felixWide = $(this).width();
			$(this).css('padding-bottom', felixWide/1.5+'px');
		});
	}
	if ( !!navigator.userAgent.match(/Trident.*rv\:11\./) && wide > 1024 ) {
		$( '#masthead nav#site-navigation div.nav-top, #masthead nav#site-navigation .nav-top a').css('font-size','1em');
	}

}
function getCookie(cname) {
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
function setCookie(cname, cvalue, exhours) {
	var d = new Date();
	d.setTime(d.getTime() + (exhours*60*60*1000));
	var expires = 'expires=' + d.toUTCString();
	document.cookie = cname + '=' + cvalue + ';' + expires + ';path=/';
}
jQuery(document).ready(function($){
	parameters($);
	var bod = $('body');
	var siteNav = $('#site-navigation');
	$(window).resize(function(){
		parameters($);
	});
	$('.nav-top.menu-item-has-children').click(function(){
		if ($(this).hasClass('nav-active'))
		{
			$(this).removeClass('nav-active');
		} else  {
			$('.nav-top').removeClass('nav-active');
			$(this).addClass('nav-active');
		}

	});
	$('li.nav-back').click(function(){
		$(this).removeClass('nav-active');
	});
	$('#top-mobile-menu').click(function(){
		if (wide < 801 || listenLive === true )
		{
			if (bod.hasClass('nav-active-menu'))
			{
				bod.removeClass('nav-active-menu').removeAttr('style').removeAttr('height');
				if ($(this).hasClass('dc-top-menu')) {
					$(this).html('<span class="genericons-neue genericons-neue-menu" aria-hidden="true"></span><br /><span class="top-mobile-text">Menu</span>');
				} else {
					$(this).html('<span class="fa fa-bars" aria-hidden="true"></span><br /><span class="top-mobile-text">Menu</span>');
				}
				if ( listenLive === true ) {
					siteNav.addClass('screen-reader-text');
				}
			} else {
				bod.addClass('nav-active-menu');
				if ($(this).hasClass('dc-top-menu')) {
					$(this).html('<span class="genericons-neue genericons-neue-close-alt" aria-hidden="true"></span><br /><span class="top-mobile-text">Close</span>');
				} else {
					$(this).html('<span class="fa fa-times" aria-hidden="true"></span><span class="top-mobile-text">Close</span>');
				}
				if ( listenLive === true ) {
					siteNav.removeClass('screen-reader-text');
				}
			}
		} else {
			return false;
		}
	});

	$(function() {
		var $allVideos = $("iframe[src*='vimeo.com'], iframe[src*='youtube.com']," +
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
		$allVideos.each(function() {
			var iframeClass;
			var vidHigh = $(this).attr('height');
			var vidWide = $(this).attr('width');
			$(this).removeAttr('height').removeAttr('width');
			var frameSrc = $(this).attr('src');
			var ratio = vidWide/vidHigh;
			if ( frameSrc.indexOf('google.com/maps') !== -1 || frameSrc.indexOf('googleusercontent.com') !== -1 || frameSrc.indexOf('houstontranstar.org') !== -1 ) {
				iframeClass = 'iframe-embed-tall';
			} else {
				if ( frameSrc.indexOf('youtube') !== -1 ) {
					window.ytPlayers.push( $(this).attr('id') );
					youtube = true;
				}
				if ( ratio > 1 ) {
					iframeClass = 'iframe-embed';
				} else {
					iframeClass = 'iframe-embed-vert';
				}
			}
			$(this).parent().addClass(iframeClass);
		});
		if (youtube) {
			var tag = document.createElement('script');
			tag.src = "https://cdn.hpm.io/assets/js/youtube.js";
			var firstScriptTag = document.getElementsByTagName('script')[0];
			firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
		}
	});
	$(".article-share-icon a, #top-listen a, .nav-listen-live a, #top-watch a").click(function(e){
		var attr = $(this).attr('data-dialog');
		var hrefCheck = $(this).attr('href');
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
			var href = $(this).attr('href');
			var text = $(this).text();
			var myWindow = window.open(href, text, "width=" + size[0] + ",height=" + size[1]);
		}
	});
	$("a").filter(function() {
		return this.hostname && this.hostname.replace('www.','') !== location.hostname.replace('www.','');
	}).attr('target', '_blank');
	$('#top-search .fa-search').click(function(){
		if ( wide > 800 ) {
			$('#top-search .search-form').slideToggle();
			$('#top-search .search-field').focus();
		} else {
			return false;
		}
	});
	$('.jp-audio-embed').click(function(e){
		e.preventDefault();
		var parentID = $(this).parents('.jp-audio').attr('id');
		$('#'+parentID+'-popup').animate({bottom: '0'}, 500, function(){});
	});
	$('.jp-audio-embed-close').click(function(){
		var parentID = $(this).parents('.jp-audio-embed-popup').attr('id');
		$('#'+parentID).animate({bottom: '-20em'}, 500, function(){});
	});
	var share = $('#article-share');
	if ( share.length ) {
		var e = share.offset();
		var eHigh = share.height();
		var p = share.parent('.entry-content').height();
		var o = p - eHigh;
		$(window).scroll(function () {
			if (wide > 800) {
				var a = $(window).scrollTop();
				if (a > e.top) {
					var k = a - e.top;
					if (k < o) {
						share.css('top', k + 10);
					}
					else if (k >= o) {
						share.css('top', o);
					}
				}
				else {
					share.css('top', 0);
				}
			}
		});
	}
	$('.passport-faq').click(function(){
		if ( $(this).hasClass( 'passport-active' ) ) {
			$(this).removeClass('passport-active').next('.passport-hidden').slideUp();
		} else {
			$('.passport-hidden').slideUp();
			$('.passport-faq').removeClass('passport-active');
			$(this).addClass('passport-active').next('.passport-hidden').slideDown();
		}
	});
	$('#passport-devices li').click(function(){
		var dat = $(this).attr('data-device');
		if ( $(this).hasClass( 'passport-active' ) ) {
			return false;
		} else {
			$('#passport-devices li').removeClass('passport-active');
			$('.passport-device').hide();
			$(this).addClass('passport-active');
			$('#'+dat).fadeIn();
		}
	});
	$('#c2c-galleries li').click(function(){
		var dat = $(this).attr('data-device');
		if ( $(this).hasClass( 'c2c-active' ) ) {
			return false;
		} else {
			$('#c2c-galleries li').removeClass('c2c-active');
			$('.c2c-gallery').removeClass('c2c-gallery-active');
			$(this).addClass('c2c-active');
			$('#'+dat).addClass('c2c-gallery-active');
		}
	});
	$('#top-schedule .top-schedule-label a').click(function(e){
		e.preventDefault();
		$('#top-schedule .top-schedule-link-wrap').toggleClass('top-sched-active');
	});
});
var getJSON = function(url, callback) {
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
let stationIds = {
	'news': 'https://api.composer.nprstations.org/v1/widget/519131dee1c8f40813e79115/now?format=json&show_song=true',
	'classical': 'https://api.composer.nprstations.org/v1/widget/51913211e1c8408134a6d347/now?format=json&show_song=true',
	'mixtape': 'https://s3-us-west-2.amazonaws.com/hpmwebv2/assets/nowplay/mixtape.json',
	'tv8.1': 'https://s3-us-west-2.amazonaws.com/hpmwebv2/assets/nowplay/tv8.1.json',
	'tv8.2': 'https://s3-us-west-2.amazonaws.com/hpmwebv2/assets/nowplay/tv8.2.json',
	'tv8.3': 'https://s3-us-west-2.amazonaws.com/hpmwebv2/assets/nowplay/tv8.3.json',
	'tv8.4': 'https://s3-us-west-2.amazonaws.com/hpmwebv2/assets/nowplay/tv8.4.json'
}
function updateData( data, station, next) {
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
	document.getElementById('np-'+station).innerHTML = output;
}
function updateStations(station, next) {
	if ( station !== 'all' ) {
		getJSON( stationIds[station], function(err, data) {
			if (err !== null) {
				console.log(err);
			} else {
				updateData(data,station,next);
			}
		});
	} else {
		getJSON( stationIds['news'], function(err, data) {
			if (err !== null) {
				console.log(err);
			} else {
				updateData(data,'news',next);
			}
		});
		getJSON( stationIds['classical'], function(err, data) {
			if (err !== null) {
				console.log(err);
			} else {
				updateData(data,'classical',next);
			}
		});
		getJSON( stationIds['mixtape'], function(err, data) {
			if (err !== null) {
				console.log(err);
			} else {
				updateData(data,'mixtape',next);
			}
		});
		getJSON( stationIds['tv8.1'], function(err, data) {
			if (err !== null) {
				console.log(err);
			} else {
				updateData(data,'tv8.1',next);
			}
		});
		getJSON( stationIds['tv8.2'], function(err, data) {
			if (err !== null) {
				console.log(err);
			} else {
				updateData(data,'tv8.2',next);
			}
		});
		getJSON( stationIds['tv8.3'], function(err, data) {
			if (err !== null) {
				console.log(err);
			} else {
				updateData(data,'tv8.3',next);
			}
		});
		getJSON( stationIds['tv8.4'], function(err, data) {
			if (err !== null) {
				console.log(err);
			} else {
				updateData(data,'tv8.4',next);
			}
		});
	}
	masonLoad();
}
function hpmNowPlaying(station,next) {
	document.addEventListener("DOMContentLoaded", function() {
		updateStations(station,next);
		setInterval("updateStations(station,next)", 60000);
	});
}
function amPm(timeString) {
	var hourEnd = timeString.indexOf(":");
	var H = +timeString.substr(0, hourEnd);
	var h = H % 12 || 12;
	var ampm = (H < 12 || H === 24) ? " AM" : " PM";
	return timeString = h + timeString.substr(hourEnd, 3) + ampm;
}
function masonLoad() {
	var isActive = false;
	if ( window.wide > 800 )
	{
		imagesLoaded( '#float-wrap', function() {
			var msnry = new Masonry( '#float-wrap', {
				itemSelector: '.grid-item',
				stamp: '.stamp',
				columnWidth: '.grid-sizer'
			});
			isActive = true;
		});
		if (document.getElementsByTagName("BODY")[0].classList.contains('home')) {
			var topSched = document.querySelector('#top-schedule-wrap').getBoundingClientRect().height;
			document.getElementById('npr-side').style.cssText += 'top: '+topSched+'px';
		}
	} else {
		if ( isActive ) {
			msnry.destroy();
			isActive = !isActive;
		}
		var gridItem = document.querySelectorAll('.grid-item');
		for ( i = 0; i < gridItem.length; ++i ) {
			gridItem[i].removeAttribute('style');
		}
	}
}
function parameters() {
	var siteNav = document.getElementById('site-navigation');
	window.wide = window.innerWidth;
	window.high = window.innerHeight;
	window.mastHigh = document.getElementById('masthead').getBoundingClientRect().height;
	window.bodyHigh = document.getElementById('page').getBoundingClientRect().height;
	window.mobileMenuWide = document.getElementById('top-mobile-menu').getBoundingClientRect().width;
	window.move = wide - mobileMenuWide;
	window.totalHigh = mastHigh + bodyHigh;
	if ( document.body.classList.contains('page-template-page-listen') ) {
		move = 400;
		window.listenLive = true;
	} else {
		window.listenLive = false;
	}

	if ( wide < 801 || listenLive === true )
	{
		siteNav.style.height = totalHigh+'px';
		siteNav.style.width = move+'px';
	}
	else
	{
		document.body.classList.remove('nav-active-menu');
		document.body.removeAttribute('style');
		siteNav.removeAttribute('style');
	}
	if ( navigator.userAgent.toLowerCase().indexOf('firefox') > -1 ) {
		var wraps = document.querySelectorAll('.felix-type-b .thumbnail-wrap'), i;
		for (i = 0; i < wraps.length; ++i) {
			var wrapWide = wraps[i].getBoundingClientRect().width;
			wraps[i].style.cssText += "padding-bottom: "+wrapWide/1.5+"px";
		}
		var nats = document.querySelectorAll('.national-image, .related-image');
		for (i = 0; i < nats.length; ++i) {
			var natWide = nats[i].getBoundingClientRect().width;
			nats[i].style.cssText += "padding-bottom: "+natWide/1.5+"px";
		}
	}
}
function getCookie(cname) {
	var name = cname + "=";
	var decodedCookie = decodeURIComponent(document.cookie);
	var ca = decodedCookie.split(';');
	for(var i = 0; i <ca.length; i++) {
		var c = ca[i];
		while (c.charAt(0) == ' ') {
			c = c.substring(1);
		}
		if (c.indexOf(name) == 0) {
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
document.addEventListener('resize', function(){
	parameters();
});
(function(){
	parameters();
	var navKids = document.querySelectorAll('.nav-top.menu-item-has-children');
	for (i = 0; i < navKids.length; ++i) {
		navKids[i].addEventListener('click', function () {
			if (this.classList.contains('nav-active')) {
				this.classList.remove('nav-active');
			}
			else {
				var navNoKids = document.querySelectorAll('.nav-top'), f;
				for (f = 0; f < navNoKids.length; ++f) {
					navNoKids[f].classList.remove('nav-active');
				}
				this.classList.add('nav-active');
			}
		});
	}
	var navBack = document.querySelectorAll('li.nav-back');
	for (i = 0; i < navBack.length; ++i) {
		navBack[i].addEventListener('click', function(){
			this.classList.remove('nav-active');
		});
	}
	var topMobile = document.getElementById('top-mobile-menu');
	var topNav = document.getElementById('site-navigation');
	topMobile.addEventListener('click', function() {
		if ( wide < 801 || listenLive === true )
		{
			if ( document.body.classList.contains('nav-active-menu') ) {
				document.body.classList.remove('nav-active-menu');
				document.body.style.cssText = 'transform: translate3d(0,0,0)';
				document.body.removeAttribute('height');
				if (topMobile.classList.contains('dc-top-menu')) {
					topMobile.innerHTML = '<span class="genericons-neue genericons-neue-menu" aria-hidden="true"></span>';
				} else {
					topMobile.innerHTML = '<span class="fa fa-bars" aria-hidden="true"></span>';
				}
				if ( listenLive === true ) {
					topNav.classList.add('screen-reader-text');
				}
			} else {
				document.body.classList.add('nav-active-menu');
				document.body.style.cssText = 'transform: translate3d(-'+move+'px,0,0)';
				if (topMobile.classList.contains('dc-top-menu')) {
					topMobile.innerHTML = '<span class="genericons-neue genericons-neue-close-alt" aria-hidden="true"></span>';
				} else {
					topMobile.innerHTML = '<span class="fa fa-close" aria-hidden="true"></span>';
				}
				if ( listenLive === true ) {
					topNav.classList.remove('screen-reader-text');
				}
			}
		} else {
			return false;
		}
	});
	var shareLink = document.querySelectorAll('.article-share-icon a, #top-listen a, .nav-listen-live a');
	for (i = 0; i < shareLink.length; ++i) {
		var hrefCheck = shareLink[i].getAttribute('href');
		if ( hrefCheck.includes('mailto:') ) {
			return true;
		} else {
			shareLink[i].addEventListener('click', function(event){
				var attr = this.getAttribute('data-dialog');
				if (typeof attr !== typeof undefined && attr !== false)
				{
					event.preventDefault();
				}
				else
				{
					event.preventDefault();
					attr = '576:730';
				}
				var size = attr.split(':');
				var href = this.getAttribute('href');
				var text = this.innerText;
				var myWindow = window.open(href,text,"width="+size[0]+",height="+size[1]);
			});
		}
	}
	var allVideos = document.querySelectorAll("iframe[src*='vimeo.com'], iframe[src*='youtube.com'], iframe[src*='ustream.tv'], iframe[src*='google.com/maps'], iframe[src*='drive.google.com'], iframe[src*='vuhaus.com'], object, embed, .videoarchive, iframe[src*='googleusercontent.com'], iframe[src*='player.pbs.org'], iframe[src*='facebook.com'], iframe[src*='houstontranstar.org']"), v;
	for (v = 0; v < allVideos.length; v++) {
		allVideos[v].removeAttribute('height');
		allVideos[v].removeAttribute('width');
		var frameSrc = allVideos[v].getAttribute('src');
		if ( frameSrc.indexOf('google.com/maps') !== -1 || frameSrc.indexOf('googleusercontent.com') !== -1 || frameSrc.indexOf('houstontranstar.org') !== -1 ) {
			var iframeClass = 'iframe-embed-tall';
		} else {
			var iframeClass = 'iframe-embed';
		}
		allVideos[v].parentNode.classList.add(iframeClass);
	}

	var extLink = document.querySelectorAll('a');
	for (i = 0; i < extLink.length; ++i) {
		extLink[i].addEventListener('click', function(){
			var attr = this.getAttribute('href');
			if (attr.indexOf('www.houstonpublicmedia.org') === -1 ) {
				ga('send', 'event', 'External', 'Click-Exit Link', attr);
			}
		});
	}
	var audioEmbed = document.querySelectorAll('.jp-audio-embed');
	for (i = 0; i < audioEmbed.length; ++i) {
		audioEmbed[i].addEventListener('click', function(event){
			event.preventDefault();
			var postId = this.getAttribute('data-post-id');
			var attachId = this.getAttribute('data-attach-id');
			alert('To embed this piece of audio in your site, please use this code:\n\n<iframe src="https://embed.hpm.io/'+attachId+'/'+postId+'" style="height: 125px; width: 100%;"></iframe>');

		});
	}
	var searchHead = document.querySelector('#top-search .fa-search');
	searchHead.addEventListener('click', function(event){
		if ( wide > 800 ) {
			var searchField = document.querySelector('#top-search .search-form');
			if ( searchField.classList.contains('search-active') ) {
				searchField.classList.remove('search-active');
			} else {
				searchField.classList.add('search-active');
			}
		} else {
			return false;
		}
	});
}());
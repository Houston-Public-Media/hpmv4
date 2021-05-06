var tag = document.createElement('script');
tag.src = "https://www.youtube.com/iframe_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
function parseURL(url) {
	var parser = document.createElement('a'),
		searchObject = {},
		queries, split, i;
	// Let the browser do the work
	parser.href = url;
	// Convert query string to object
	queries = parser.search.replace(/^\?/, '').split('&');
	for( i = 0; i < queries.length; i++ ) {
		split = queries[i].split('=');
		searchObject[split[0]] = split[1];
	}
	return {
		protocol: parser.protocol,
		host: parser.host,
		hostname: parser.hostname,
		port: parser.port,
		pathname: parser.pathname,
		search: parser.search,
		searchObject: searchObject,
		hash: parser.hash
	};
}
function ytdimensions() {
	var youtube = document.getElementById('youtube-player');
	window.ytwide = youtube.getBoundingClientRect().width;
	window.ythigh = ytwide/1.77777777777778;
	youtube.style.height = ythigh+'px';
}
function onYouTubeIframeAPIReady() {
	window.ytPlayers.forEach(function(data){
		window['ytPlay_'+data] = new YT.Player( data, {
			events: {
			  'onReady': onPlayerReady,
			  'onStateChange': onPlayerStateChange
			}
		});
	});
}
function onPlayerReady(event) {
	var vidId = event.target.getIframe().id;
	if (navigator.userAgent.match(/(iPad|iPhone|iPod touch)/i) == null && vidId == 'youtube-player') {
		event.target.playVideo();
		window.ytPlayers.forEach(function(data){
			if ( data !== vidId ) {
				window['ytPlay_'+data].pauseVideo();
			}
		});
	}
	return true;
}
function onPlayerStateChange(event) {
	var vidId = event.target.getIframe().id;
	if (event.data == YT.PlayerState.ENDED && vidId == 'youtube-player') {
		var current = parseURL(player.getVideoUrl());
		var nextVid = document.getElementById(current.searchObject.v).nextSibling();
		var newYtid = nextVid.getAttribute('data-ytid');
		if ( newYtid !== undefined )
		{
			var yttitle = nextVid.getAttribute('data-yttitle');
			var ytdesc = nextVid.getAttribute('data-ytdesc');
			var ytdate = nextVid.getAttribute('data-ytdate');
			ytid = newYtid;
			window['ytPlay_youtube-player'].stopVideo();
			window['ytPlay_youtube-player'].loadVideoById({
				videoId: ytid
			});
			var d = document.getElementById('youtube-main');
			d.querySelector('h2').innerHTML = yttitle;
			d.querySelector('.desc').innerHTML = ytdesc;
			d.querySelector('.date').innerHTML = ytdate;
			var c = document.getElementById('yt-nowplay');
			c.parentNode.removeChild(c);
			document.getElementById(newYtid).innerHTML += '<div id="yt-nowplay">Now Playing</div>';
		}
		else
		{
			return false;
		}
	} else if (event.data == YT.PlayerState.PLAYING) {
		window.ytPlayers.forEach(function(data){
			if ( data !== vidId ) {
				window['ytPlay_'+data].pauseVideo();
			}
		});
	}
}
if (document.getElementById('youtube-player') !== null) {
	ytdimensions();
	timeOuts.push(setTimeout(ytdimensions(), 500));
	document.getElementById('play-button').addEventListener('click', function(){
		window.ytid = this.parentNode.getAttribute('data-ytid');
		var f = document.getElementById('yt-nowplay');
		if ( f !== null ) {
			f.parentNode.removeChild(f);
		}
		document.getElementById(ytid).innerHTML += '<div id="yt-nowplay">Now Playing</div>';
		window.ytPlayers.push('youtube-player');
		window['ytPlay_youtube-player'] = new YT.Player('youtube-player', {
			height: ythigh,
			width: ytwide,
			videoId: ytid,
			events: {
				'onReady': onPlayerReady,
				'onStateChange': onPlayerStateChange
			}
		});
		var yttitle = this.parentNode.getAttribute('data-yttitle');
	});
	var ytc = document.querySelectorAll('.youtube');
	for ( i = 0; i < ytc.length; i++ ) {
		ytc[i].addEventListener('click', function(){
			var newYtid = this.getAttribute('data-ytid');
			var yttitle = this.getAttribute('data-yttitle');
			var ytdesc = this.getAttribute('data-ytdesc');
			var ytdate = this.getAttribute('data-ytdate');
			if ( typeof ytid === typeof undefined ) {
				var d = document.getElementById('youtube-main');
				d.querySelector('h2').innerHTML = yttitle;
				console.log(d.querySelector('.desc'));
				if ( d.querySelector('.desc') !== null ) {
					d.querySelector('.desc').innerHTML = ytdesc;
				}
				if ( d.querySelector('.date') !== null ) {
					d.querySelector('.date').innerHTML = ytdate;
				}
				var c = document.getElementById('yt-nowplay');
				if ( c !== null ) {
					c.parentNode.removeChild(c);
				}
				document.getElementById(newYtid).innerHTML += '<div id="yt-nowplay">Now Playing</div>';
				window.ytid = newYtid;
				window.ytPlayers.push('youtube-player');
				window['ytPlay_youtube-player'] = new YT.Player('youtube-player', {
					height: ythigh,
					width: ytwide,
					videoId: ytid,
					events: {
						'onReady': onPlayerReady,
						'onStateChange': onPlayerStateChange
					}
				});
			}
			else if ( typeof ytid !== typeof undefined )
			{
				if ( ytid !== newYtid )
				{
					ytid = newYtid;
					window['ytPlay_youtube-player'].stopVideo();
					window['ytPlay_youtube-player'].loadVideoById({
						videoId: ytid
					});
					var d = document.getElementById('youtube-main');
					d.querySelector('h2').innerHTML = yttitle;
					if ( d.querySelector('.desc') !== null ) {
						d.querySelector('.desc').innerHTML = ytdesc;
					}
					if ( d.querySelector('.date') !== null ) {
						d.querySelector('.date').innerHTML = ytdate;
					}
					var c = document.getElementById('yt-nowplay');
					if ( c !== null ) {
						c.parentNode.removeChild(c);
					}
					document.getElementById(newYtid).innerHTML += '<div id="yt-nowplay">Now Playing</div>';
				}
				else
				{
					return false;
				}
			}
			else
			{
				return false;
			}
		});
	}
}
document.addEventListener("DOMContentLoaded", function() {
	var dcSearch = document.querySelectorAll('#dc-search a'), i;
	for (i = 0; i < dcSearch.length; ++i) {
		dcSearch[i].addEventListener('click', function(event) {
			event.preventDefault();
			var query = document.querySelector('#top-search');
			if ( query.offsetWidth > 0 || query.offsetHeight > 0 ) {
				return false;
			} else {
				query.style.display = 'block';
			}
		});
	}
	document.querySelector('#dc-search-close').addEventListener('click', function() {
		document.querySelector('#top-search').style.display = 'none';
	});
});
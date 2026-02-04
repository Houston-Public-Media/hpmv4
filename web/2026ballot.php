<?php
$file = file_get_contents('https://cdn.houstonpublicmedia.org/projects/elections/results_FLAT_for_ballot_2026.json');
$json = json_decode($file);

/**
 * Build race structure:
 * $races[office][race_name][] = candidate
 */
$offices = [];
$races   = [];

foreach ($json as $j) {

    if (!in_array($j->office, $offices, true)) {
        $offices[] = $j->office;
    }

    $race_name = $j->division_type;
    if (!empty($j->office_division)) {
        $race_name .= ' ' . $j->office_division;
    }

    if (!isset($races[$j->office][$race_name])) {
        $races[$j->office][$race_name] = [];
    }

    foreach ($j->candidacies as $candidate) {
        $races[$j->office][$race_name][] = $candidate;
    }
}
?>
<html>
<head>
    <script src="https://assets.houstonpublicmedia.org/wp/wp-includes/js/jquery/jquery.min.js"></script>
    <script>
        let keys = 0;
        let ballot;
        let addressSearch = document.querySelector('#searchtext');
        let autoComplete = document.querySelector('#autocompletes');
        let localRaces = document.querySelector('#localized-races');
        fetch('https://cdn.houstonpublicmedia.org/projects/elections/results_FLAT_for_ballot_2026.json')
            .then((response) => response.json())
            .then((data) => {
                let output = [];
                data.forEach((d) => {
                    //console.log("data:" + JSON.stringify(data))
                    if ( d.division_type === 'district' ) {
                        //console.log(d.division_type);
                        if ( typeof output[ d.office_slug ] === typeof undefined ) {
                            output[ d.office_slug ] = {
                                'democrat': {},
                                'republican': {}
                            };
                        }
                        let party = d.party; //.toLowerCase();
                        output[ d.office_slug ][ party ] = d;
                        //console.log(output);
                    }
                });
                ballot = output;
            });

        let candidates = ( data ) => {
            let output = '';
            for ( let key in data ) {
                if ( Object.keys( data[ key ] ).length > 0 ) {
                    output += '<div class="' + data[key].party + '"><ul class="list-group">';
                    if ( typeof data[ key ].candidacies !== typeof undefined ) {
                        Array.from( data[ key ].candidacies ).forEach(( candidate ) => {
                            output += '<li class="list-group-item">' + candidate.name;
                            if ( candidate.is_incumbent ) {
                                output += ' (Incumbent)';
                            }
                            output += '</li>';
                        });
                        output += '</ul></div>';
                    }
                }
            }
            return output;
        };
        document.addEventListener('DOMContentLoaded', () => {
            addressSearch.addEventListener('keyup', function() {
                keys++;
                //console.log('KEYS: ' + keys);
                let attr = document.getElementById('searchtext').value;

                if ( keys % 4 === 0 && attr.length > 0 ) {
                    let encodedArr = encodeURIComponent(attr);
                    fetch('https://api.mapbox.com/geocoding/v5/mapbox.places/' + encodedArr + '.json?country=us&proximity=ip&types=address&access_token=pk.eyJ1Ijoiandjb3VudHMiLCJhIjoiY2xzamVod25uMjM2djJsbzZ0dDVqcGs2eCJ9.ziK1YVkXah9IepA_bwsqdw')
                        .then((response) => response.json())
                        .then((data) => {
                            if ( data.features.length > 0 ) {
                                let output = '<ul>';
                                data.features.forEach((feature) => {
                                    output += '<li><button class="autocomplete-result" data-latitude="' + feature.center[1] + '" data-longitude="' + feature.center[0] + '" data-feature-name="' + feature.place_name + '">' + feature.place_name + '</button></li>';
                                });
                                output += '</ul>';
                                autoComplete.innerHTML = output;
                                autoComplete.classList.remove('visually-hidden');
                                let results = document.querySelectorAll('.autocomplete-result');
                                Array.from(results).forEach((result) => {
                                    result.addEventListener('click', (e) => {
                                        e.preventDefault();


                                        //console.log("Ballot"+ JSON.stringify(ballot));
                                        let lat = e.currentTarget.getAttribute('data-latitude');
                                        let long = e.currentTarget.getAttribute('data-longitude');
                                        addressSearch.value = e.currentTarget.getAttribute('data-feature-name');
                                        fetch('https://dv-dev.texastribune.org/legislative-lookup-2025/?latitude=' + lat + '&longitude=' + long )
                                            .then((response) => response.json())
                                            .then((data) => {

                                                autoComplete.classList.add('visually-hidden');
                                                if ( data.response && data.status === 'OK') {
                                                    //console.log("Data is: "+ JSON.stringify(data.data));
                                                    localRaces.innerHTML = '<h3>Your Results</h3><ul class="list-group">' +
                                                        '<li class="list-group-item">County: <strong>' + data.data.county.name + '</strong></li>' +
                                                        '<li class="list-group-item">State Board of Education: <strong>' + data.data.sboe.district + '</strong></li>' +
                                                        '<li class="list-group-item">Texas House: <strong>' + data.data.tx_house.district + '</strong></li>' +
                                                        '<li class="list-group-item">Texas Senate: <strong>' + data.data.tx_senate.district + '</strong></li>' +
                                                        '<li class="list-group-item">US House: <strong>' + data.data.us_house.district + '</strong></li>' +
                                                        '</ul>';
                                                    let sboe = 'state-board-of-education-district-' + data.data.sboe.district;
                                                    let txHouse = 'texas-house-district-' + data.data.tx_house.district;
                                                    let txSenate = 'texas-senate-district' + data.data.tx_senate.district;
                                                    //console.log("txSenate: "+txSenate);
                                                    let usHouse = 'us-house-district-' + data.data.us_house.district;
                                                    if ( typeof ballot[ sboe ] !== typeof undefined ) {
                                                        localRaces.innerHTML += '<section><div><h3>State Board of Education District ' + data.data.sboe.district + '</h3><div class="grid-wrap">' + candidates( ballot[ sboe ] ) + '</div></div></section>';
                                                    }
                                                    if ( typeof ballot[ txHouse ] !== typeof undefined ) {
                                                        localRaces.innerHTML += '<section><div><h3>Texas House District ' + data.data.tx_house.district + '</h3><div class="grid-wrap">' + candidates( ballot[ txHouse ] ) + '</div></div></section>';
                                                    }
                                                    if ( typeof ballot[ txSenate ] !== typeof undefined ) {
                                                        localRaces.innerHTML += '<section><div><h3>Texas Senate District ' + data.data.tx_senate.district + '</h3><div class="grid-wrap">' + candidates( ballot[ txSenate ] ) + '</div></div></section>';
                                                    }
                                                    if ( typeof ballot[ usHouse ] !== typeof undefined ) {
                                                        localRaces.innerHTML += '<section><div><h3>U.S House District ' + data.data.us_house.district + '</h3><div class="grid-wrap">' + candidates( ballot[ usHouse ] ) + '</div></div></section>';
                                                    }
                                                } else {
                                                    localRaces.innerHTML = '<p>Sorry, data not available.</p>';
                                                }
                                            });
                                    });
                                });
                            }
                        });
                }
            });
        });
    </script>
</head>
<body>
<section class="section">
    <div class="card">
        <form id="search-form" role="form" action="" method="GET">
            <div class="card-header">ENTER YOUR ADDRESS</div>
            <div class="card-body"><label class="visually-hidden" for="searchtext">Search</label><input id="searchtext" class="inputtextclass" name="q" type="text" value="" placeholder="Address Search" /></div>
        </form>
    </div>
    <div id="autocompletes"> </div>
    <div id="localized-races" class="grid-wrap"> </div>
</section>

</body>
</html>


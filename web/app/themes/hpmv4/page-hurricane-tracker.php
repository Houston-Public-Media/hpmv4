<?php
/*
Template Name: Hurricane Tracker Page
*/
get_header(); ?>
    <style>
        #mapcontainer {
            height: 100%;
            width: 100%;
            min-height: 80vh;
            border: 1px solid #404040;
            margin-bottom: 4vh;
        }
        #map {
            width: 100%;
            height: 100%;
            min-height: 100%;
            min-width: 100%;
            display: block;

        }
        #storm-controls {
            padding: 10px;
            background: #fff;
            display: inline-flex;
            flex-direction: column;
            accent-color: var(--secondary);
        }
        #storm-controls div {
            margin-right: 20px;
        }
        .inActiveLayer
        {
            display: none;
        }
        .nostormslayer {
            position: absolute;
            top: 50%;
            left: 50%;
            z-index: 2;
            background-color: #fff;
            padding: 5px;
            border: 1px solid #999;
            text-align: center;
            line-height: 30px;
            padding-left: 10px;
        }

    </style>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBdmtbN8nCvd1OitCluzgGgiQCTdpMnMJg"></script>
    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">
            <div class="entry-content">
                <section class="section">
                    <div class="row">
                        <div class="col-md-12">
                                <div id="storm-controls" class="d-flex flex-row"></div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div id="mapcontainer">
                                <div id="map" class="container border-solid-black" style="z-index: 1;"></div>
                                <div class="nostormslayer" id="nostormslayer">No active storms</div>

                            </div>

                        </div>
                    </div>
                </section>
            </div>
        </main>
    </div>
    <script>
        var map;
        var stormLayers = {};
        var stromKey = ['forecastTrack', 'bestTrackGIS', 'trackCone'];
        let noStormLayerID = document.querySelector('#nostormslayer');
        noStormLayerID.classList.add('visually-hidden');
        function initialize() {
            map = new google.maps.Map(document.getElementById('map'), {
                center: new google.maps.LatLng(43.6424359, -79.37448849999998),
                zoom: 1,
                disableDefaultUI: true,
            });
            fetchAndProcessStormData();
            map.set('styles', [
                {
                    elementType: "geometry",
                    stylers: [{ color: "#f5f5f5" }],
                },
                {
                    elementType: "labels.icon",
                    stylers: [{ visibility: "off" }],
                },
                {
                    elementType: "labels.text.fill",
                    stylers: [{ color: "#616161" }],
                },
                {
                    elementType: "labels.text.stroke",
                    stylers: [{ color: "#f5f5f5" }],
                },
                {
                    featureType: "administrative.land_parcel",
                    elementType: "labels.text.fill",
                    stylers: [{ color: "#bdbdbd" }],
                },
                {
                    featureType: "poi",
                    elementType: "geometry",
                    stylers: [{ color: "#eeeeee" }],
                },
                {
                    featureType: "poi",
                    elementType: "labels.text.fill",
                    stylers: [{ color: "#757575" }],
                },
                {
                    featureType: "poi.park",
                    elementType: "geometry",
                    stylers: [{ color: "#e5e5e5" }],
                },
                {
                    featureType: "poi.park",
                    elementType: "labels.text.fill",
                    stylers: [{ color: "#9e9e9e" }],
                },
                {
                    featureType: "road",
                    elementType: "geometry",
                    stylers: [{ color: "#ffffff" }],
                },
                {
                    featureType: "road.arterial",
                    elementType: "labels.text.fill",
                    stylers: [{ color: "#757575" }],
                },
                {
                    featureType: "road.highway",
                    elementType: "geometry",
                    stylers: [{ color: "#dadada" }],
                },
                {
                    featureType: "road.highway",
                    elementType: "labels.text.fill",
                    stylers: [{ color: "#616161" }],
                },
                {
                    featureType: "road.local",
                    elementType: "labels.text.fill",
                    stylers: [{ color: "#9e9e9e" }],
                },
                {
                    featureType: "transit.line",
                    elementType: "geometry",
                    stylers: [{ color: "#e5e5e5" }],
                },
                {
                    featureType: "transit.station",
                    elementType: "geometry",
                    stylers: [{ color: "#eeeeee" }],
                },
                {
                    featureType: "water",
                    elementType: "geometry",
                    stylers: [{ color: "#c9c9c9" }],
                },
                {
                    featureType: "water",
                    elementType: "labels.text.fill",
                    stylers: [{ color: "#9e9e9e" }],
                }
            ]);
        }
        async function fetchAndProcessStormData() {
            try {
                const response = await fetch('http://localhost/NHC_JSON_Sample.json'); // Path to your JSON file NHC_JSON_Sample.json CurrentStorms
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const data = await response.json();
                if(data.activeStorms.length>0) {
                    createStormControls(data.activeStorms);
                    processStormData(data.activeStorms);
                }
                else
                {
                    noStormsLayer(true);
                }
            } catch (error) {
                console.error('Error fetching or processing data:', error);
            }
        }
        function noStormsLayer()
        {
           // document.getElementById("inactivelayer").style.display= display;
            noStormLayerID.classList.remove('visually-hidden');
        }

        function createStormControls(storms) {
            const controlsDiv = document.getElementById('storm-controls');
            storms.forEach((storm, index) => {
                const stormControl = document.createElement('div');
                const checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.id = `storm-${index}`;
                checkbox.checked = true;
                checkbox.addEventListener('change', () => toggleStormLayer(index));
                stormControl.appendChild(checkbox);
                const label = document.createElement('label');
                label.htmlFor = `storm-${index}`;
                label.textContent = storm.name;
                stormControl.appendChild(label);
                controlsDiv.appendChild(stormControl);
            });
        }
        function processStormData(storms) {
            storms.forEach((storm, index) => {
                stromKey.forEach(key => {
                    const stormTrack = storm[key]?.kmzFile;
                    if (stormTrack) {
                        const trackLayer = new google.maps.KmlLayer(stormTrack, {
                            preserveViewport: false,
                            map: map
                        });
                        if (!stormLayers[index]) {
                            stormLayers[index] = [];
                        }
                        stormLayers[index].push(trackLayer);
                    }
                });
            });
        }
        function toggleStormLayer(index) {
            stormLayers[index].forEach(layer => {
                layer.setMap(layer.getMap() ? null : map);
            });
        }
        google.maps.event.addDomListener(window, 'load', initialize);
    </script>
<?php get_footer(); ?>
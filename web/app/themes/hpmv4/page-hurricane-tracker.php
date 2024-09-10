<?php
/*
Template Name: Hurricane Tracker Page
*/
get_header(); ?>
    <style>
        #mapcontainer {
            height: 100%;
            width: 100%;
            min-height: 50vh;
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
        .legends {
            display: flex;
            font-family: Marr Sans,Arial,Helvetica,sans-serif;
            font-size: 14px;
            font-weight: 400;
            justify-content: center;
            line-height: 1.2;
            margin: 0 auto;
            max-width: 1220px;
            padding: 0 10px 1em;
            width: 100%
        }

        .legends.inactive {
            max-width: 700px
        }

        @media (max-width: 1024px) {
            .legends {
                align-items:center;
                flex-direction: column;
                justify-content: center;
                line-height: 1.7
            }
        }

        .legends div.legend-group {
            align-items: flex-end;
            display: flex
        }

        .legends div.info-group {
            display: flex;
            justify-content: flex-end
        }

        @media (max-width: 1024px) {
            .legends div.info-group {
                align-items:center;
                flex-direction: column;
                justify-content: center;
                line-height: 1.7
            }
        }

        .legends h4 {
            font-family: Marr Sans,Arial,Helvetica,sans-serif;
            font-weight: 700;
            margin: 0;
            padding: 0 1em 0 0;
            width: 7ch
        }

        .inline-item {
            padding-right: 1em
        }

        .inline-info {
            align-items: flex-end;
            display: flex;
            font-family: Marr Sans,Arial,Helvetica,sans-serif;
            font-weight: 400;
            justify-content: space-between;
            max-width: 100%
        }

        @media (max-width: 480px) {
            .inline-info.long {
                align-items:center;
                flex-direction: column;
                justify-content: center
            }
        }

        .inline-info .bold {
            font-weight: 700
        }

        .inline-info .chiclet,.inline-info .path-dashed,.inline-info .path-solid {
            display: inline-block;
            margin-right: .5ch
        }

        .inline-info .path-solid {
            border-top: 2px solid #3e3e3e;
            height: .35em;
            width: 20px
        }

        .inline-info .path-dashed {
            border-top: 2px dashed #909090;
            height: .35em;
            width: 20px
        }

        .inline-info .chiclet {
            height: .8em;
            opacity: .5;
            width: 20px
        }

        .inline-info .chiclet.winds {
            background-color: #d6c1d3
        }

        .inline-info .chiclet.uncertainty {
            background-color: #f1dead
        }


        .circlelegend-module--circleLegend--75594 {
            grid-column-gap: 10px;
            align-items: center;
            display: grid;
            font-family: Marr Sans,Arial,Helvetica,sans-serif;
            font-family: 400;
            font-size: 14px;
            grid-template-areas: "num5 num4 num3 num2 num1 . storm" "circle5 circle4 circle3 circle2 circle1 circle0 storm";
            grid-template-columns: 15px 14px 13px 12px 11px 10px 12ch;
            grid-template-rows: auto auto;
            justify-items: center;
            margin-left: 1ch;
            max-width: 720px
        }

        .circlelegend-module--category2--6bd54 {
            grid-area: storm;
            justify-self: start;
            line-height: 1.2;
            padding-left: 4px;
            position: relative;
            text-align: left
        }

        .circlelegend-module--category2--6bd54:before {
            border-top: 1.5px solid #676767;
            content: "";
            height: 1.5px;
            left: -9px;
            position: absolute;
            top: 78%;
            width: 10px;
            z-index: -1
        }

        .circlelegend-module--circle--0cc03 {
            border: 1px solid #676767;
            border-radius: 50%;
            height: 10px;
            width: 10px
        }

        .circlelegend-module--circle--0cc03:first-child {
            grid-area: circle5
        }

        .circlelegend-module--circle--0cc03:nth-child(2) {
            grid-area: circle4
        }

        .circlelegend-module--circle--0cc03:nth-child(3) {
            grid-area: circle3
        }

        .circlelegend-module--circle--0cc03:nth-child(4) {
            grid-area: circle2
        }

        .circlelegend-module--circle--0cc03:nth-child(5) {
            grid-area: circle1
        }

        .circlelegend-module--circle--0cc03:nth-child(6) {
            grid-area: circle0
        }

        .circlelegend-module--label--d658d:nth-child(8) {
            grid-area: num5
        }

        .circlelegend-module--label--d658d:nth-child(9) {
            grid-area: num4
        }

        .circlelegend-module--label--d658d:nth-child(10) {
            grid-area: num3
        }

        .circlelegend-module--label--d658d:nth-child(11) {
            grid-area: num2
        }

        .circlelegend-module--label--d658d:nth-child(12) {
            grid-area: num1
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
                    <div class="row">
                        <div class="col-12">
                            <div class="legends">
                                <div class="info-group">
                                    <div class="inline-info"><span class="inline-item"><span class="path-solid"></span>Previous path</span><span
                                                class="inline-item"><span class="path-dashed"></span>Forecast</span></div>
                                    <div class="inline-info long"><span class="inline-item"><span class="chiclet winds"></span>Area of tropical
                    storm winds</span><span class="inline-item"><span class="chiclet uncertainty"></span>Cone of
                    uncertainty</span></div>
                                </div>
                                <div class="legend-group">
                                    <h4>Category</h4>
                                    <div class="circlelegend-module--circleLegend--75594">
                                        <div class="circlelegend-module--circle--0cc03"
                                             style="background-color: rgb(186, 20, 45); opacity: 1; width: 12px; height: 12px;"></div>
                                        <div class="circlelegend-module--circle--0cc03"
                                             style="background-color: rgb(241, 77, 119); opacity: 1; width: 11px; height: 11px;"></div>
                                        <div class="circlelegend-module--circle--0cc03"
                                             style="background-color: rgb(255, 117, 0); opacity: 1; width: 10px; height: 10px;"></div>
                                        <div class="circlelegend-module--circle--0cc03"
                                             style="background-color: rgb(255, 188, 48); opacity: 1; width: 9px; height: 9px;"></div>
                                        <div class="circlelegend-module--circle--0cc03"
                                             style="background-color: rgb(124, 167, 53); opacity: 1; width: 8px; height: 8px;"></div>
                                        <div class="circlelegend-module--circle--0cc03"
                                             style="background-color: rgb(44, 185, 191); opacity: 1; width: 5px; height: 5px;"></div>
                                        <div class="circlelegend-module--category2--6bd54">Tropical storm or less</div><span
                                                class="circlelegend-module--label--d658d">5</span><span
                                                class="circlelegend-module--label--d658d">4</span><span
                                                class="circlelegend-module--label--d658d">3</span><span
                                                class="circlelegend-module--label--d658d">2</span><span
                                                class="circlelegend-module--label--d658d">1</span>
                                    </div>
                                </div>
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
        var stormKey = ['forecastTrack', 'bestTrackGIS', 'trackCone', 'windSpeedProbabilitiesGIS'];
        var stormClassification = ['HU','TD','STD'];
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
                const response = await fetch('https://hpmwebv2.s3-us-west-2.amazonaws.com/assets/NHC_JSON_Sample.json');
                // https://hpmwebv2.s3-us-west-2.amazonaws.com/assets/NHC_JSON_Sample.json  https://hpmwebv2.s3-us-west-2.amazonaws.com/assets/noaa-current-storms.json
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const data = await response.json();
                console.log("strom data :", data);


                const filteredStorms = data.activeStorms.filter(storm => stormClassification.includes(storm.classification))

                createStormControls(filteredStorms);
                processStormData(filteredStorms);
            } catch (error) {
                console.error('Error fetching or processing data:', error);
            }
        }

        function noStormsLayer()
        {
            noStormLayerID.classList.remove('visually-hidden');
        }

        function createStormControls(storms) {
            const controlsDiv = document.getElementById('storm-controls');
            storms.forEach((storm, index) => {
               //if(stormClassification.includes(storm.classification))
                //{
                    console.log(storm.name+" - "+storm.classification);
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
            //}
        }
        function processStormData(storms) {
            storms.forEach((storm, index) => {
                stormKey.forEach(key => {
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
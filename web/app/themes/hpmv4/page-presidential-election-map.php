<?php
/*
Template Name: Presidential Election Map
*/
	get_header(); ?>

	<style>

		section {
			display: grid;
			grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
			gap: 1rem;
			padding: 1rem;
			margin-bottom: 1rem;
			/*border: 1px solid black;*/
		&#vote-bar {
			 display: block;
		> div {
			display: flex;
			gap: 0;
			background-color: #808080;
			width: 100%;
			padding: 0;
			justify-content: space-between;
		> div {
			padding: 1rem 0;
			transition: width 200ms ease-in-out;
		}
		}
		> div.vote-bar-num {
			background-color: transparent;
			color: black;
		> div {
			padding: 0;
			font-size: 0.85rem;
		}
		}
		}
		&#vote-totals {
		.vote-none {
			text-align: center;
		}
		.vote-repub {
			text-align: right;
		}
		}
		&#vote-buttons button {
			 width: 100%;
		 }
		> div {
			font-weight: bolder;
			color: white;
			padding: 0.5rem;
		> button {
			font-weight: bolder;
			color: white;
			padding: 0.5rem;
		&:hover {
			 cursor: pointer;
		 }
		}
		}
		}
		section#vote-buttons {
			display: grid;
			gap: 1rem;
			padding: 1rem;
			margin-bottom: 1rem;
		}
		section#vote-buttons  div{
			min-width: 60px !important;
			font-size: 12px !important;
		}
		.vote-none {
			background-color: #808080;
		}
		.vote-dem {
			background-color: var(--main-blue);
		}
		.vote-repub {
			background-color: var(--main-red);
		}
		.states :hover {
			fill: red;
		}
		.states{
			fill:#808080;
			color:#fff;
		}
		svg {
			fill: none;
		}
		.state-borders {
			fill: none;
			stroke: #fff;
			stroke-width: 0.5px;
			stroke-linejoin: round;
			stroke-linecap: round;
			pointer-events: none;
		}
		.state-boundary {
			fill: #13afbb;
			stroke: #fff;
		}
		.vote-dem {
			fill: #0044c9 !important;
		}
		.vote-repub {
			fill: #da1333 !important;
		}
		.vote-none {
			fill: #808080 !important; /*13afbb*/
		}
		.vote-dbar
		{
			background-color: blue;
		}
		.collagemap-block {
			background: #f5f5f5;

			padding: 10px 15px 10px;
			overflow: hidden;
			margin-bottom: 0px;
			height: 100%;
			position: relative;
			font-size: 14px;
		}
		.noneblock
		{
			border-top: solid 7px #808080;
		}
		.demblock
		{
			border-top: solid 7px #0044c9;
		}
		.repblock
		{
			border-top: solid 7px #da1333;
		}
		.collagemap-block h5 {
			font-size: 16px;
			color: #00566c;
			font-weight: bold;
		}
		.collagemap-tiles {
			display: grid;
			gap: 1rem;
		}
		@media screen and (min-width: 52.5em) {
			.collagemap-tiles {
				grid-template-columns: 1fr 1fr 1fr;
			}
		}
			@media screen and (min-width: 34em) {
				.collagemap-tiles {
					grid-template-columns: 1fr 1fr 1fr;
				}
			}

	</style>
	<script src="https://d3js.org/d3.v3.min.js" charset="utf-8"></script>
	<script src="https://d3js.org/topojson.v1.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<div class="page-banner"><picture> <source srcset="https://cdn.houstonpublicmedia.org/assets/images/ELECTION-BANNER-Mobile-600x400.png.webp" type="image/webp" media="(max-width: 34em)" /></picture><br />
			<picture><source srcset="https://cdn.houstonpublicmedia.org/assets/images/ELECTION-BANNER-Mobile-600x400.png" media="(max-width: 34em)" /></picture><br />
			<picture><source srcset="https://cdn.houstonpublicmedia.org/assets/images/ELECTION-BANNER-Tablet-1600x400.png.webp" type="image/webp" media="(max-width: 52.5em)" /></picture><br />
			<picture><source srcset="https://cdn.houstonpublicmedia.org/assets/images/ELECTION-BANNER-Tablet-1600x400.png" media="(max-width: 52.5em)" /></picture><br />
			<picture><source srcset="https://cdn.houstonpublicmedia.org/assets/images/ELECTION-BANNER-Desktop-2400x400-1.png.webp" type="image/webp" /></picture><br />
			<picture><source srcset="https://cdn.houstonpublicmedia.org/assets/images/ELECTION-BANNER-Desktop-2400x400-1.png" /></picture><br />
			<picture><img src="https://cdn.houstonpublicmedia.org/assets/images/ELECTION-BANNER-Desktop-2400x400-1.png" alt="Harris County Results from Election 2023" /></picture></div>

		<section class="section opportunities">

			<div class="collagemap-tiles">
				<div class="collagemap-block demblock">
					<h5> Democrats</h5>
					<p> Need 270</p>
				</div>
				<div class="collagemap-block noneblock">
					<h5> Total</h5>
					<p> 538</p>
				</div>
				<div class="collagemap-block repblock">
					<h5> Republicans</h5>
					<p> Need 270</p>
				</div>

			</div>
		</section>

		<div class="row">
			<div class="col-md-12">
				<section id="vote-totals">
					<div class="vote-dem">Democratic: <span id="democrat-total">0</span></div>
					<div class="vote-none">None: <span id="none-total">538</span></div>
					<div class="vote-repub">Republican: <span id="republican-total">0</span></div>
				</section>
				<section id="vote-bar">
					<div>
						<div id="dem-bar" class="vote-dem" style="width: 0"></div>
						<div id="repub-bar" class="vote-repub" style="width: 0"></div>
					</div>
				</section>
			</div>

		</div>
		<div class="row">
			<div class="col-md-11">
				<div id="map"></div>
			</div>
			<div class="col-md-1">
				<section id="vote-buttons"></section>
			</div>

		</div>

	</main>
</div>
	<script>

		let states = {"AL": {"Abbr": "AL","code": "AL 9","name": "Alabama", "affiliation": 0, "votes": 9 },
			"AK": {"Abbr": "AK","code": "AK 3","name": "Alaska", "affiliation": 0, "votes": 3 },
			"AZ": {"Abbr": "AZ","code": "AZ 11","name": "Arizona", "affiliation": 0, "votes": 11 },
			"AR": {"Abbr": "AK","code": "AK 6","name": "Arkansas", "affiliation": 0, "votes": 6 },
			"CA": {"Abbr": "CA","code": "CA 54","name": "California", "affiliation": 0, "votes": 54 },
			"CO": {"Abbr": "CO","code": "CO 10","name": "Colorado", "affiliation": 0, "votes": 10 },
			"CT": {"Abbr": "CT","code": "CT 7","name": "Connecticut", "affiliation": 0, "votes": 7 },
			"DC": {"Abbr": "DC","code": "DC 3","name": "District of Columbia", "affiliation": 0, "votes": 3 },
			"DE": {"Abbr": "DE","code": "DE 3","name": "Delaware", "affiliation": 0, "votes": 3 },
			"FL": {"Abbr": "FL","code": "FL 30","name": "Florida", "affiliation": 0, "votes": 30 },
			"GA": {"Abbr": "GA","code": "GA 16","name": "Georgia", "affiliation": 0, "votes": 16 },
			"HI": {"Abbr": "HI","code": "HI 4","name": "Hawaii", "affiliation": 0, "votes": 4 },
			"ID": {"Abbr": "ID","code": "ID 4","name": "Idaho", "affiliation": 0, "votes": 4 },
			"IL": {"Abbr": "IL","code": "IL 19","name": "Illinois", "affiliation": 0, "votes": 19 },
			"IN": {"Abbr": "IN","code": "IN 11","name": "Indiana", "affiliation": 0, "votes": 11 },
			"IA": {"Abbr": "IA","code": "IA 6","name": "Iowa", "affiliation": 0, "votes": 6 },
			"KS": {"Abbr": "KS","code": "KS 6","name": "Kansas", "affiliation": 0, "votes": 6 },
			"KY": {"Abbr": "KY","code": "KY 8","name": "Kentucky", "affiliation": 0, "votes": 8 },
			"LA": {"Abbr": "LA","code": "LA 8","name": "Louisiana", "affiliation": 0, "votes": 8 },
			"ME": {"Abbr": "ME","code": "ME 4","name": "Maine", "affiliation": 0, "votes": 4 },
			"MD": {"Abbr": "MD","code": "MD 10","name": "Maryland", "affiliation": 0, "votes": 10 },
			"MA": {"Abbr": "MA","code": "MA 11","name": "Massachusetts", "affiliation": 0, "votes": 11 },
			"MI": {"Abbr": "MI","code": "MI 15","name": "Michigan", "affiliation": 0, "votes": 15 },
			"MN": {"Abbr": "MN","code": "MN 10","name": "Minnesota", "affiliation": 0, "votes": 10 },
			"MS": {"Abbr": "MS","code": "MS 6","name": "Mississippi", "affiliation": 0, "votes": 6 },
			"MO": {"Abbr": "MO","code": "MO 10","name": "Missouri", "affiliation": 0, "votes": 10 },
			"MT": {"Abbr": "MT","code": "MT 4","name": "Montana", "affiliation": 0, "votes": 4 },
			"NE": {"Abbr": "NE","code": "NE 5","name": "Nebraska", "affiliation": 0, "votes": 5 },
			"NV": {"Abbr": "NV","code": "NV 6","name": "Nevada", "affiliation": 0, "votes": 6 },
			"NH": {"Abbr": "NH","code": "NH 4","name": "New Hampshire", "affiliation": 0, "votes": 4 },
			"NJ": {"Abbr": "NJ","code": "NJ 14","name": "New Jersey", "affiliation": 0, "votes": 14 },
			"NM": {"Abbr": "NM","code": "NM 5","name": "New Mexico", "affiliation": 0, "votes": 5 },
			"NY": {"Abbr": "NW","code": "NW 28","name": "New York", "affiliation": 0, "votes": 28 },
			"NC": {"Abbr": "NC","code": "NC 16","name": "North Carolina", "affiliation": 0, "votes": 16 },
			"ND": {"Abbr": "ND","code": "ND 3","name": "North Dakota", "affiliation": 0, "votes": 3 },
			"OH": {"Abbr": "OH","code": "OH 17","name": "Ohio", "affiliation": 0, "votes": 17 },
			"OK": {"Abbr": "OK","code": "OK 7","name": "Oklahoma", "affiliation": 0, "votes": 7 },
			"OR": {"Abbr": "OR","code": "OR 8","name": "Oregon", "affiliation": 0, "votes": 8 },
			"PA": {"Abbr": "PA","code": "PA 19","name": "Pennsylvania", "affiliation": 0, "votes": 19 },
			"RI": {"Abbr": "RI","code": "RI 4","name": "Rhode Island", "affiliation": 0, "votes": 4 },
			"SC": {"Abbr": "SC","code": "SC 9","name": "South Carolina", "affiliation": 0, "votes": 9 },
			"SD": {"Abbr": "SD","code": "SD 3","name": "South Dakota", "affiliation": 0, "votes": 3 },
			"TN": {"Abbr": "TN","code": "TN 11","name": "Tennessee", "affiliation": 0, "votes": 11 },
			"TX": {"Abbr": "TX","code": "TX 40","name": "Texas", "affiliation": 0, "votes": 40 },
			"UT": {"Abbr": "UT","code": "UT 6","name": "Utah", "affiliation": 0, "votes": 6 },
			"VT": {"Abbr": "VT","code": "VT 3","name": "Vermont", "affiliation": 0, "votes": 3 },
			"VA": {"Abbr": "VA","code": "VA 13","name": "Virginia", "affiliation": 0, "votes": 13 },
			"WA": {"Abbr": "WA","code": "WA 12","name": "Washington", "affiliation": 0, "votes": 12 },
			"WV": {"Abbr": "WV","code": "WV 4","name": "West Virginia", "affiliation": 0, "votes": 4 },
			"WI": {"Abbr": "WI","code": "WI 10","name": "Wisconsin", "affiliation": 0, "votes": 10 },
			"WY": {"Abbr": "WY","code": "WY 3","name": "Wyoming", "affiliation": 0, "votes": 3 }
		};
		let skippedStates ={
			"NJ": { "Abbr": "NJ","code": "NJ 3","name": "New Jersey", "affiliation": 0, "votes": 14 },
			"CT": { "Abbr": "CT","code": "CT 3","name": "Connecticut", "affiliation": 0, "votes": 7 },
			"MA": { "Abbr": "MA","code": "MA 3","name": "Massachusetts", "affiliation": 0, "votes": 11 },
			"RI": { "Abbr": "RI","code": "RI 3","name": "Rhode Island", "affiliation": 0, "votes": 4 },
			"DE": { "Abbr": "DE","code": "DE 3","name": "Delaware", "affiliation": 0, "votes": 3 },
			"MD": { "Abbr": "MD","code": "MD 3","name": "Maryland", "affiliation": 0, "votes": 10 },
			"DC": { "Abbr": "DC","code": "DC 3","name": "District of Columbia", "affiliation": 0, "votes": 3 },
		};
		let skippedAbbr = ["NJ", "CT", "MA", "RI","DE","MD","DC"];
		let voteButtons = document.querySelector('#vote-buttons');
		let voteTotals = [
			document.querySelector('#none-total'),
			document.querySelector('#democrat-total'),
			document.querySelector('#republican-total')
		];
		let voteBars = [
			document.querySelector('#none-bar'),
			document.querySelector('#dem-bar'),
			document.querySelector('#repub-bar')
		];
		let affiliations = [
			{ "name": 'None', "votes": 538, "class": 'vote-none' },
			{ "name": 'Democrat', "votes": 0, "class": 'vote-dem' },
			{ "name": 'Republican', "votes": 0, "class": 'vote-repub'}
		];

		document.addEventListener('DOMContentLoaded', () => {
			for ( let key in skippedStates ) {
				voteButtons.innerHTML += '<div><button data-state="' + key +'" data-affiliation="' + states[key].affiliation + '" data-votes="' + states[key].votes + '" class="states vote-none">' + states[key].Abbr + ' (' + states[key].votes + ')</button></div>';
			}
			let vButtonList = document.querySelectorAll('#vote-buttons button');
			Array.from(vButtonList).forEach((vb) => {
				vb.addEventListener('click', function(e) {

					let aff = this.getAttribute('data-affiliation');
					let stateAb = this.getAttribute('data-state');
					this.classList.remove(affiliations[aff].class);
					aff++;
					if (aff > 2) {
						aff = 0;
					}
					this.classList.add(affiliations[aff].class);
					states[stateAb].affiliation = aff;
					this.setAttribute('data-affiliation', aff);
					let d = '';
					updateStateVotes(d, stateAb);
					updateVotes();
				});
			});
		});
		const updateStateVotes = (d, Abbr) => {
		   // console.log(d3.selectAll("path").filter((d) => d.properties.ABBR));

		  //  alert(d3.selectAll("path").filter(d => d.properties.STATE === "48"));

		  //  console.log("d.properties: "+JSON.stringify(d3.selectAll("path").filter((d) => d.properties.ABBR)));
			let paths = document.querySelector("path[data-state=" + Abbr + "]");
			let aff = paths.getAttribute("data-affiliation");
			let stateAb = paths.getAttribute('data-state');

			paths.classList.toggle(affiliations[aff].class);
			aff++;
			if (aff > 2) {
				aff = 0;
			}
			paths.classList.toggle(affiliations[aff].class);
			paths.setAttribute('data-affiliation', aff);
			states[stateAb].affiliation = aff;
			updateVotes();
		};

		const updateVotes = () => {
			let parties = [ 0, 0, 0 ];
			for ( let key in states ) {
				parties[ states[key].affiliation ] += states[key].votes;
			}
			for (let i = 0; i < affiliations.length; i++) {
				affiliations[i].votes = parties[i];
				//alert("voteTotals[i]: "+voteTotals[i]);
				voteTotals[i].innerHTML = parties[i].toString();
				let percent = (( parties[i] / 538 ) * 100).toFixed(1);
				if (i > 0) {
					voteBars[i].style.width = percent + "%";
				}
			}
		};

		let stateLabelPositions = {

			// AL
			"01x": "-10",
			"01y": "-15",

			// AK
			"02x": "-10",
			"02y": "-15",

			// AZ
			"04x": "-10",
			"04y": "-15",

			// AR
			"05x": "-10",
			"05y": "-10",

			// CA
			"06x": "-20",
			"06y": "-15",

			// CO
			"08x": "-10",
			"08y": "-15",

			// CT
			"09x": "-10",
			"09y": "-15",

			// DE
			"10x": "-10",
			"10y": "-15",

			// FL
			"12x": "-2",
			"12y": "-15",

			// GA
			"13x": "-15",
			"13y": "-15",

			// HI
			"15x": "-15",
			"15y": "-0",

			// ID
			"16x": "-15",
			"16y": "-10",

			// IL
			"17x": "-10",
			"17y": "-15",

			// IN
			"18x": "-10",
			"18y": "-15",

			// IA
			"19x": "-10",
			"19y": "-10",

			// KS
			"20x": "-10",
			"20y": "-10",

			// KY
			"21x": "-0",
			"21y": "-12",

			// LA
			"22x": "-20",
			"22y": "-15",

			// ME
			"23x": "-10",
			"23y": "-10",

			// MD
			"24x": "-10",
			"24y": "-15",

			// MA
			"25x": "-10",
			"25y": "-15",

			// MI
			"26x": "-5",
			"26y": "-0",

			// MN
			"27x": "-15",
			"27y": "-15",

			// MS
			"28x": "-10",
			"28y": "-15",

			// MO
			"29x": "-15",
			"29y": "-15",

			// MT
			"30x": "-10",
			"30y": "-15",

			// NE
			"31x": "-10",
			"31y": "-10",

			// NV
			"32x": "-10",
			"32y": "-15",

			// NH
			"33x": "-10",
			"33y": "-0",

			// NJ
			"34x": "-10",
			"34y": "-15",

			// MN
			"35x": "-10",
			"35y": "-15",

			// NY
			"36x": "-5",
			"36y": "-15",

			// NC
			"37x": "-0",
			"37y": "-10",

			// ND
			"38x": "-10",
			"38y": "-12",

			// OH
			"39x": "-10",
			"39y": "-10",

			// OK
			"40x": "-10",
			"40y": "-10",

			// OR
			"41x": "-10",
			"41y": "-10",

			// PA
			"42x": "-10",
			"42y": "-10",

			// RI
			"44x": "-10",
			"44y": "-15",

			// SC
			"45x": "-10",
			"45y": "-8",

			// SD
			"46x": "-10",
			"46y": "-12",

			// TN
			"47x": "-10",
			"47y": "-8",

			// TX
			"48x": "-10",
			"48y": "-15",

			// UT
			"49x": "-10",
			"49y": "-12",

			// VT
			"50x": "-12",
			"50y": "-15",

			// VA
			"51x": "-5",
			"51y": "-15",

			// WA
			"53x": "-8",
			"53y": "-10",

			// WV
			"54x": "-13",
			"54y": "-5",

			// WI
			"55x": "-10",
			"55y": "-10",

			// WY
			"56x": "-10",
			"56y": "-15",
		};
		let smallStates = ["11", "25", "44", "09", "34", "10", "24","28", "33"];

		// Override State Positions
		stateLabelPositions["12x"] = "10";
		stateLabelPositions["15y"] = "16";
		stateLabelPositions["15x"] = "20";
		stateLabelPositions["12y"] = "0";
		stateLabelPositions["13x"] = "-12";
		stateLabelPositions["23x"] = "-12";
		stateLabelPositions["28y"] = "-55";
		stateLabelPositions["25y"] = "-6";
		stateLabelPositions["33x"] = "1";
		stateLabelPositions["33y"] = "-6";
		stateLabelPositions["45x"] = "-6";
		stateLabelPositions["47y"] = "-10";
		stateLabelPositions["42y"] = "-12";
		let electMap = document.querySelector("#map");
		let width = electMap.getBoundingClientRect().width;
		let height = width * .68;
		electMap.style.height = height;

		let projection = d3.geo.albersUsa()
			.scale(width * 1.38) //width * 1.38
			.translate([width / 2, height / 2]);

		let path = d3.geo.path()
			.projection(projection);
		let svg = d3.select("#map").append("svg")
			.style("width", width)
			.style("height", height);
		svg.append("rect")
			.attr("class", "background")
			.attr("width", width)
			.attr("height", height);

		d3.json("https://hpmwebv2.s3-us-west-2.amazonaws.com/projects/elections/us_states_topo.json", function(error, us) {
			let data = topojson.feature(us, us.objects.layer1).features;
			let g = svg.append("g");
			g.attr("class", "states")
				.selectAll("path")
				.data(topojson.feature(us, us.objects.layer1).features)
				.enter()
				.append("path")
				.attr("d", path)
				.attr("id", function (d) {
					return d.properties.STATE;
				})
				.attr("data-state", function (d) {
					return d.properties.ABBR;
				})
				.attr("stroke", "white")
				.attr("data-affiliation", 0)
				.attr("class", "states vote-none")
				.on("click", function (d) {
					updateStateVotes(d, d.properties.ABBR);
				})
				.attr("fill", function (d) {
					return '#808080';
				});

			g.selectAll("text")
				.data(topojson.feature(us, us.objects.layer1).features)
				.enter()
				.append("svg:text")
				.each(function(d) {
					if(d.properties.ABBR != "PR" && !skippedAbbr.includes(d.properties.ABBR)) {
						let lines = states[d.properties.ABBR].code.split(' ');

						for (let i = 0; i < lines.length; i++) {
							d3.select(this).append("tspan")
								.text(lines[i]).attr("class","maptext")
								.attr("x", function(d) {
									let factor = 1.2;
									let full_size = 820;
									if (width < 520) {
										factor = width / full_size;
									}

									let new_x = path.centroid(d)[0] + (parseInt(stateLabelPositions[d.properties.STATE + "x"]) * factor);

									if (!isNaN(new_x)) {
										return new_x;
									}
									//return path.centroid(d)[0];
									return new_x;
								})
								.attr("y", function (d) {
									let new_y = path.centroid(d)[1] + parseInt(stateLabelPositions[d.properties.STATE + "y"]);
									if (!isNaN(new_y)) {
										if (width <= 576) {
											new_y -= 2;
										}
										if (width <= 576 &&  (d.properties.STATE == "12" || d.properties.STATE == "15" || d.properties.STATE == "33"  || d.properties.STATE == "25"  || d.properties.STATE == "28")) {
											new_y += 4;
										}
										if (width <= 576 && (d.properties.STATE != "33")) {
											new_y += 4;
										}
										if (width <= 400) {
											new_y += 2;
										}
										// console.log("new_y"+new_y);
										return new_y;
									}
								})
								.attr("dy", i ? "1em" : 0) // Line spacing
						}
					}
				})
				.attr("x", function (d) {
					let factor = 1;
					let full_size = 820;
					if (width < 520) {
						factor = width / full_size;
					}
					let new_x = path.centroid(d)[0] + (parseInt(stateLabelPositions[d.properties.STATE + "x"]) * factor);
					if ( d.properties.ABBR == "FL" ) {
						//console.log(new_x);
					}
					if (!isNaN(new_x)) {
						return new_x;
					}
				})
				.attr("y", function (d) {
					let new_y = path.centroid(d)[1] + parseInt(stateLabelPositions[d.properties.STATE + "y"]);

					if (!isNaN(new_y)) {
						if (width <= 576) {
							new_y -= 2;
						}
						if (width <= 576 &&  (d.properties.STATE == "33" || d.properties.STATE == "42" || d.properties.STATE == "15" || d.properties.STATE == "25" || d.properties.STATE == "12" || d.properties.STATE == "28")) {
							new_y += 4;
						}
						if (width <= 576 && (d.properties.STATE != "33")) {
							new_y += 4;
						}
						if (width <= 400) {
							new_y += 2;
						}
						if(d.properties.ABBR == "MS") {
							//console.log(new_y);
							//console.log(d.properties.STATE+"hi: "+parseInt(stateLabelPositions[d.properties.STATE + "y"]));
						}
						return new_y;
					}
				})
				/*.attr("x", function(d){
					/!*if(d.properties.ABBR == "FL")
					{
						return 1260.2086982131382;
					}*!/
					/!*if(d.properties.ABBR == "HI")
					{
						return 627.59366077239565;
					}*!/
					return path.centroid(d)[0];
				})
				.attr("y", function(d){
					/!*if(d.properties.ABBR == "FL")
					{
						return 876.661656418421;
					}*!/
					/!*if(d.properties.ABBR == "HI")
					{
						return 992.0623450835138;
					}
					if(d.properties.ABBR == "LA")
					{
						return 805.2209808581275;
					}
					if(d.properties.ABBR == "MI")
					{
						return 430.6417691128858;
					}*!/
					return  path.centroid(d)[1];
				})*/
				.attr("text-anchor","middle")
				.attr('fill', '#fff')
				.attr("font-weight", "bold")
				.attr("font-size", "13px")
				.attr("id", function (d) {
					return d.properties.STATE + "_text";
				})
				.on("click", function (d) {
					updateStateVotes(d, d.properties.ABBR);
				});
		});
	</script>

<?php get_footer(); ?>
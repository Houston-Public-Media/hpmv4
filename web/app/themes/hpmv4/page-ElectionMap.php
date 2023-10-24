<?php
/*
Template Name: Election Map Page
*/
	get_header();
	?>


<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/lmap.json" type="text/javascript"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/electionData.json" type="text/javascript"></script>
    <link rel="stylesheet" href=https://kendo.cdn.telerik.com/themes/6.6.0/default/default-ocean-blue.css />

    <script src=https://code.jquery.com/jquery-3.4.1.min.js></script>
    <script src=https://kendo.cdn.telerik.com/2023.2.718/js/kendo.all.min.js></script>


<style>
	#map
	{
		width: 100%;
		height: 100%;
		min-height: 100%;
		min-width: 100%;
		display: block;
	}
	#mapcontainer {
	height: 50vh;
	width: 80%;
	margin-top: 4vh;
	margin-left: 10%;
	border: 1px solid #404040;
	margin-bottom: 4vh;
	}
	.leaflet-container {
			height: 100%;
			width: 100%;
			max-width: 100%;
			max-height: 100%;
			
	}
    .leaflet-tile-pane {
            -webkit-filter: grayscale(100%);
            filter: grayscale(100%);
    }
    .leaflet-popup-content-wrapper{
        padding: 2px;
        text-align: center;
        background: #E5E5E5 !important;
    }
    .leaflet-popup-content{
        margin: 20px 5px 5px 5px !important;
    }
    .disablelayer{
        cursor: default !important;
    }
    .leaflet-interactive{
        cursor: default !important;        
    }
    .progress { width:100%; height: 12px; background: #cdcdcd; border-radius: 20px; overflow: hidden;}
    .progress .percentBar {height:100%; background:#88abc6; transition: width ease-in-out .2s;}


    #GridContainer {
        height: 300px;
        width: 100%;
    }

    .k-grid .k-grid-header .k-table-th {
        background-color: lightblue;
        font-family: Arial, Helvetica, sans-serif;
        font-weight: bold;
        font-size: 14px;
    }

    .k-grid td,
    .k-grid .k-table-td {
        font-family: Arial, Helvetica, sans-serif;
        font-weight: normal;
        font-size: 14px;
    }

    .k-grid td:first-child,
    .k-grid .k-table-tr:first-child:first {
        border-left: 5px solid blue !important;
    }


	</style>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<header class="page-header">
				<h1 class="page-title"><?php echo get_the_title(); ?></h1>
			</header>
			<div class="page-content">
					<?php the_content(); ?>
					<div id="mapcontainer">
						<div id="map" class="container border-solid-black"></div>
				</div>
				<section>
					<div id="chartcontainer">

                        <div class="row">
                            <div class="col-6">
                                <div id="grid1"></div>
                            </div>
                            <br />
                            <div class="col-6">
                                <div id="grid2"></div>
                            </div>

                        </div>

				</div>

				</section>
			</div>

		</main>
	</div>
	<script>

        var dataSourceTemplate = {
            transport: {
                read: function (operation) {
                    var data = operation.data.data || [];
                    operation.success(data);
                },
            },
            schema: {
                model: {
                    id: "county",
                    fields: {
                        Image: { type: "string", title: "Image" },
                        Name: { type: "string", title: "Name" },
                        Total: { type: "number" },
                        PercentBar: { type: "text", title:"" },
                        Percent: { type: "text" },
                    },
                },
            },
        };


        var columns = [
            { field: "Image", title: "Image", encoded: false },
            { field: "Name", title: "Name" },
            { field: "Total", title: "Total Votes" },
            { field: "PercentBar", title: " ", encoded: false },
            { field: "Percent", title: "Percentage" },
        ]

        $(function () {
            function fetchCSV(csvURL, dataSource) {
                $.ajax({
                    url: csvURL,
                    type: "GET",
                    dataType: "text",
                    contentType: "application/json;charset=utf-8",
                    success: function (data) {
                        //console.log("success " + data);
                        var countyData = CSVToJSON(data);
                        data2 = countyData.map((item) => {
                            const name = item[0];
                            const totalVote = item[4] || "";
                            const percent = item[5]?.split("%");
                            const percentValue = percent?.length ? Number(percent[0]) : 0;
                            return {
                                Image: '<img src="#:data.Image#" style="width:50px;height:50px;" />',
                                Name: name,
                                Total: totalVote,
                                PercentBar: `<div class="progress"> <div class="percentBar" style="width:${percentValue}%"></div> </progress>`,
                                Percent: `${percentValue}%`,
                            };
                        });
                        dataSource.read({ data: data2 });
                    },
                    error: function (data) {
                        console.log("error " + data);
                    },
                });
            }

            function CSVToJSON(csvData) {
                return csvData
                    .slice(csvData.indexOf("\n") + 1)
                    .split("\n")
                    .map((rows) => rows.split(","))
                    .filter((row) => row.length > 1);
            }


            function StartInterval(csv, dataSource, fetchCSV) {
                this.intervalTime = 3000;
                this.csv = csv;
                this.dataSource = dataSource;
                this.interval = setInterval(function () {
                    fetchCSV(csv, dataSource);
                }, this.intervalTime);
                this.clearInterval = function () {
                    clearInterval(this.interval);
                };
                fetchCSV(csv, dataSource);
            }

            var dataSource1 = new kendo.data.DataSource(dataSourceTemplate);
            $("#grid1").kendoGrid({
                columns: columns,
                dataSource: dataSource1,
            });

            var dataSource2 = new kendo.data.DataSource(dataSourceTemplate);
            $("#grid2").kendoGrid({
                columns: columns,
                dataSource: dataSource2,
            });

            // $("#start").on("click", function () {

            const interval1 = new StartInterval('../assets/county.csv', dataSource1,fetchCSV);
            // interval1.clearInterval();
            const interval2 = new StartInterval('../assets/4647.csv', dataSource2,fetchCSV);
            //  interval2.clearInterval();

            // });
        });


	const map = L.map('map').setView([29.803240, -95.358566], 10);
    const tiles = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
		maxZoom: 19,
		attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
	}).addTo(map);

    //console.log(ElectionData);
	/*var bounds = L.latLngBounds([[29.523624, -95.78808690000001], [30.1107319, -95.01449599999999]]);
    map.setMaxBounds(bounds);
    map.on('drag', function() {
        map.panInsideBounds(bounds, { animate: false });
    });*/
   
 	function onEachFeature(feature, layer) {
        if (feature.properties.Precinct) {
            layer.bindPopup(feature.properties.Precinct);
        }         
        layer.on({
            mouseover: openPopupInfo,
            mouseout: resetHighlight,
        });
	}

    function openPopupInfo(e) {
        var PopupContent = null;
        var layer = e.target;
        var props = layer.feature.properties;
        PopupContent = GetElectionResultfromJSONVariable(props.Precinct);
        var result = checkIfElementPresentinElectionData(layer.feature.properties.Precinct);
        if(result)
        {
            layer.setStyle({
                weight: 1,
                fillColor: '#222054',
                fillOpacity: 1
            });
        }        
        if(PopupContent !== "" && PopupContent !== null)
        {
            layer.bindPopup(PopupContent).openPopup();
        }
    }
    
    function GetElectionResultfromJSONVariable(pID)
    {
        var htmlcontentString = null;
        let PrecinctID = pID;      
        var htmlStr = "";
        var clickedArray = new Array();
        for(let i = 0, len = ElectionData.length; i < len; i++)
        {
            if(PrecinctID == ElectionData[i].id) 
            {
               clickedArray = ElectionData[i];
            }
        }
        var candidatesArray = clickedArray['Candidates'];
        var TotalVotes = clickedArray['TotalVotes'];
        if(TotalVotes>0)
        {
            var TotalVotesperPrecint = 0;
            for(var j=0; j<candidatesArray.length; j++)
            {
                TotalVotesperPrecint = (candidatesArray[j].Votes/TotalVotes)*100;
                htmlStr += "<div style='padding: 2px; font-size: 12px; font-weight: bold; color: #fff;background-color: #222054;'>"+candidatesArray[j].Candidatename+": <span>"+TotalVotesperPrecint.toFixed(2)+"%</span></div>";
            }     
            htmlcontentString = '<div class="iw-content" style="background-color:#237bbd; border-radius:8px;">' + '<div class="iw-subTitle" style="height: 22px;background: #237bbd;font-weight: bold; margin: 10px;color: #fff;font-size: 14px;padding-top: 5px;">2019 Election Results </div><div style="padding: 2px;font-size: 12px;font-weight: bold;color: #fff;background-color: #222054;""><span">Precinct: '+PrecinctID+'</span></div>' + htmlStr+'<div style="padding: 2px;font-size: 12px;font-weight: bold;color: #fff;background-color: #222054;"">&nbsp;</div></div>';
        }
        return htmlcontentString;
    }
    
    function resetHighlight(e) 
    {
        var layer = e.target;
        geojson.resetStyle(e.target);
        var result = checkIfElementPresentinElectionData(layer.feature.properties.Precinct);
        if(result)
        {
            layer.setStyle({
                weight: 1,
                opacity: 1,
                color: '#808080',
                fillOpacity: 0.4,
                fillColor: '#237bbd'
            });
        }
        layer.closePopup();      
    }

    function style(feature) {
		return {
			weight: 1,
			opacity: 1,
			color: '#808080',
			fillOpacity: 0.2,
			fillColor: '#cccccc'
		};
	}

    const geojson = L.geoJson(stateData, {style, onEachFeature	}).addTo(map);

    function checkIfElementPresentinElectionData(element) {
        for(let i = 0, len = ElectionData.length; i < len; i++){
            if(ElectionData[i].id == element && ElectionData[i].TotalVotes>0)
            {
                return true;
            }
        }
        return false;
    }
    
    geojson.eachLayer(function(layer){
        var result = checkIfElementPresentinElectionData(layer.feature.properties.Precinct);
        let container = layer._path;
        if(!result)
        {
            layer.removeEventListener('click');
            layer.removeEventListener('mouseover');     
            container.classList.add("disablelayer"); 
            layer.setStyle({
			weight: 1,
			opacity: 1,
			color: '#808080',
			fillOpacity: 0.4,
			fillColor: '#cccccc'
		});
        }
        if(result){
            layer.setStyle({
                fillColor: "#237bbd",
                weight: 1,
                opacity: 1,
                color: '#808080',
                fillOpacity: 0.4,
                clickable:false,
            });  
            container.classList.add("enablelayer");          
        }
    });   
</script>

<?php get_footer(); ?>
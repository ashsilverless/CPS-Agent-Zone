<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Toggle Migration</title>
<meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no">
<link href="https://api.mapbox.com/mapbox-gl-js/v2.1.1/mapbox-gl.css" rel="stylesheet">
<script src="https://api.mapbox.com/mapbox-gl-js/v2.1.1/mapbox-gl.js"></script>
<style>
    body { margin: 0; padding: 0; }
    #map { position: absolute; top: 0; bottom: 0; width: 100%; }
</style>
</head>
<body>
<style>
    #menu {
        background: #fff;
        position: absolute;
        z-index: 1;
        top: 10px;
        right: 10px;
        border-radius: 3px;
        width: 120px;
        border: 1px solid rgba(0, 0, 0, 0.4);
        font-family: 'Open Sans', sans-serif;
    }

    #menu a {
        font-size: 13px;
        color: #404040;
        display: block;
        margin: 0;
        padding: 0;
        padding: 10px;
        text-decoration: none;
        border-bottom: 1px solid rgba(0, 0, 0, 0.25);
        text-align: center;
    }

    #menu a:last-child {
        border: none;
    }

    #menu a:hover {
        background-color: #f8f8f8;
        color: #404040;
    }

    #menu a.active {
        background-color: #3887be;
        color: #ffffff;
    }

    #menu a.active:hover {
        background: #3074a4;
    }
</style>
    
    <nav id="menu"></nav>
<div id="map"></div>
    
    
<script>

	mapboxgl.accessToken = 'pk.eyJ1IjoiYXNobG91ZG9uIiwiYSI6ImNqdDZ6cW56bDA1bHE0OXJ6ZDVncmk3NXcifQ.-mRWjwYb1nkTRgSSaVMSbw';

    var map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/streets-v11',
        zoom: 4,
        center: [37.433069, 0.250820]
    });
    
    map.addControl(new mapboxgl.NavigationControl());

    map.on('load', function () {
 
// add source and layer for migration1
        map.addSource('migration1', {
            type: 'vector',
            url: 'mapbox://ashloudon.cklgtnsgr065s27k4uhgz9ej7-9jfpg'
        });
        map.addLayer({
            'id': 'migration1',
            'type': 'line',
            'source': 'migration1',
            'source-layer': 'Test_Migration',
            'layout': {
// make layer visible by default
                'visibility': 'visible',
                'line-join': 'round',
                'line-cap': 'round'
            },
            'paint': {
                'line-color': '#FF0000',
                'line-width': 1
            }
        });
    });
 

    var toggleableLayerIds = ['migration1'];
 
// toggle button for each layer
    for (var i = 0; i < toggleableLayerIds.length; i++) {
        var id = toggleableLayerIds[i];
 
        var link = document.createElement('a');
        link.href = '#';
        link.className = 'active';
        link.textContent = id;
 
        link.onclick = function (e) {
            var clickedLayer = this.textContent;
            e.preventDefault();
            e.stopPropagation();
 
            var visibility = map.getLayoutProperty(clickedLayer, 'visibility');

            if (visibility === 'visible') {
                map.setLayoutProperty(clickedLayer, 'visibility', 'none');
                this.className = '';
            } else {
                this.className = 'active';
                map.setLayoutProperty(clickedLayer, 'visibility', 'visible');
            }
        };
 
        var layers = document.getElementById('menu');
        layers.appendChild(link);
    }
</script>
 
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>GIS</title>

    {{-- css & script leflet --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
    integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
    crossorigin=""/>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="crossorigin=""></script>

      <!-- Load Esri Leaflet from CDN -->
  <script src="https://unpkg.com/esri-leaflet@3.0.7/dist/esri-leaflet.js"
  integrity="sha512-ciMHuVIB6ijbjTyEdmy1lfLtBwt0tEHZGhKVXDzW7v7hXOe+Fo3UA1zfydjCLZ0/vLacHkwSARXB5DmtNaoL/g=="
  crossorigin=""></script>

<!-- Load Esri Leaflet Vector from CDN -->
<script src="https://unpkg.com/esri-leaflet-vector@3.1.2/dist/esri-leaflet-vector.js"
  integrity="sha512-SnA/TobYvMdLwGPv48bsO+9ROk7kqKu/tI9TelKQsDe+KZL0TUUWml56TZIMGcpHcVctpaU6Mz4bvboUQDuU3w=="
  crossorigin=""></script>

  <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>

  <script src="{{ asset('assets/leaflet/js/leaflet.textpath.js') }}"></script>

  <link rel="stylesheet" href="{{ asset('assets/leaflet/js/MarkerCluster.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/leaflet/js/MarkerCluster.Default.css') }}">

  <script src="{{ asset('assets/leaflet/js/leaflet.markercluster.js') }}"></script>

  <link rel="stylesheet" href="{{ asset('assets/leaflet/miniMap/Control.MiniMap.min.css') }}">
  <script src="{{ asset('assets/leaflet/miniMap/Control.MiniMap.min.js') }}"></script>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/gokertanrisever/leaflet-ruler@master/src/leaflet-ruler.css" integrity="sha384-P9DABSdtEY/XDbEInD3q+PlL+BjqPCXGcF8EkhtKSfSTr/dS5PBKa9+/PMkW2xsY" crossorigin="anonymous">  
  <script src="https://cdn.jsdelivr.net/gh/gokertanrisever/leaflet-ruler@master/src/leaflet-ruler.js" integrity="sha384-N2S8y7hRzXUPiepaSiUvBH1ZZ7Tc/ZfchhbPdvOE5v3aBBCIepq9l+dBJPFdo1ZJ" crossorigin="anonymous"></script>

  <script src="{{ asset('assets/leaflet/hash/leaflet-hash.js') }}"></script>

  <script type="text/javascript" src="{{ asset('assets/leaflet/mouseCoordinate/Leaflet.Coordinates-0.1.5.min.js') }}"></script>
	<link rel="stylesheet" href="{{ asset('assets/leaflet/mouseCoordinate/Leaflet.Coordinates-0.1.5.css') }}"/>

    <style>
        #map { height: 500px; }

        .lable-icon {
          font-size: 10pt;
          color: red;
          text-align: center;
        }
        .legend{
          background: #ffffff;
          padding: 10px;
        }
    </style>

</head>
<body>

    <div style="padding: 5px; margin-bottom: 5px">
        Cari Lokasi:
        <select onchange="cari(this.value)">
            @foreach($datas as $item)
            <option value="{{ $item->id }}">
                {{ $item->nama }}
            </option>
            @endforeach
        </select>
    </div>
    <br>

      <div>
        <input onclick="pilihSungai(this)" type="checkbox">Sungai</input>
        <input onclick="pilihLokasi(this)" type="checkbox">Lokasi</input>
      </div>

    <br>
    <div id="map"></div>
    
    
    <script>
      var geoLayer;
      var lyr_sungai;
      var lyr_lokasi;

        var map = L.map('map').setView([-1.885948, 106.0528198], 10);

        // var tiles = L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
		// maxZoom: 18,
		// attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, ' +
		// 	'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
		// id: 'mapbox/streets-v11',
		// tileSize: 512,
		// zoomOffset: -1
        // }).addTo(map);

        // L.esri.basemapLayer('Topographic').addTo(map);

        // Hybrid: s,h;
        // Satellite: s;
        // Streets: m;
        // Terrain: p;
        
        L.tileLayer('http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}',{
            maxZoom: 18,
            subdomains:['mt0','mt1','mt2','mt3']
        }).addTo(map);

        // var marker = L.marker([-1.885948, 106.0528198]).addTo(map);


        //marker
        var embassyIcon = L.icon({
        iconUrl: "{{ asset('assets/gis/marker-icon/embassy.png')}}",
        // iconUrl: 'assets/gis/marker-icon/embassy.png',

        iconSize:     [39, 60], // size of the icon
        shadowSize:   [50, 64], // size of the shadow
        iconAnchor:   [22, 94], // point of the icon which will correspond to marker's location
        shadowAnchor: [4, 62],  // the same for the shadow
        popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
        });

        const marker = L.marker([-1.885948, 106.0528198], {icon: embassyIcon}).addTo(map);

        let latlong = marker.on('click', function(ev) {
            alert(ev.latlng); // ev is an event object (MouseEvent in this case)
        });

        
        // create a red polyline from an array of LatLng points
        var latlngs = [
            [
            -1.8834511929474003,
            106.05293512344359
            ],
            [
            -1.8808991076040962,
            106.05435132980347
             ],
            [ 
            -1.8811350148092931,
            106.05512380599976
            ]
        ];

        var polyline = L.polyline(latlngs, {color: 'red'}).addTo(map);

        polyline.setStyle(
            {
                weight: 9,
                lineCap: 'round',
            }
        );

        // zoom the map to the polyline
        map.fitBounds(polyline.getBounds());

        polyline.on('click', function(ev) {
            alert(ev.latlng); // ev is an event object (MouseEvent in this case)
            polyline.setStyle(
            {
                color: 'blue'
            }
        );
        });


        // create a red polygon from an array of LatLng points
        var latlngs = [
            [
              -1.8819982204472954,
              106.05312287807465
             
            ],
            [
              -1.8821108124555386,
              106.0526132583618
            ],
            [
              -1.8830222713021545,
              106.0528439283371
            ],
            [
              -1.8830222713021545,
              106.05351984500884
            ],
            [
              -1.8825719034610457,
              106.05391681194305
            ],
            [
              -1.882014305020356,
              106.0537987947464
            ],
            [ 
              -1.8819982204472954,
              106.05312287807465
            ]
        ];

        var polygon = L.polygon(latlngs, {color: 'red'}).addTo(map);

        polygon.setStyle(
            {
                weight: 5,
                color: 'red',
                fillColor: 'orange',
                fillOpacity: 0.5
            }
        );


        // zoom the map to the polygon
        map.fitBounds(polygon.getBounds());

        polygon.on('click', function(ev) {
            alert("oh oh my jisoo"); // ev is an event object (MouseEvent in this case)
            polygon.setStyle(
            {
                color: '#e6e6e6',
                fillColor: 'grey'
            }
        );
        });

        
        $(document).ready(function() {
            $.getJSON('data-geo', function(data) {

                  $.each(data, function(index) {

                      var embassyIcon = L.icon({
                      iconUrl: "{{ asset('assets/gis/marker-icon/embassy.png')}}",
                      // iconUrl: 'assets/gis/marker-icon/embassy.png',

                      iconSize:     [39, 60], // size of the icon
                      shadowSize:   [50, 64], // size of the shadow
                      iconAnchor:   [22, 94], // point of the icon which will correspond to marker's location
                      shadowAnchor: [4, 62],  // the same for the shadow
                      popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
                      });

                    L.marker([parseFloat(data[index].long), parseFloat(data[index].lat)], {icon: embassyIcon}).addTo(map);

                      
                  });
            });



            
            // $.getJSON('assets/geojson/map.geojson', function(json) {

            //     geoLayer = L.geoJson(json, {

            //       style: function(feature) {
            //         return {
            //             fillOpacity:0,
            //             weight: 5,
            //             opacity: 1,
            //             color: "#008cff",
            //             dashArray: "30 10",
            //             lineCap: 'square'
            //         };
            //       },

            //       onEachFeature: function(feature, layer) {

            //         // console.log(feature.properties.name);
            //         //divIcon untuk custum label
            //         const iconLable = L.divIcon({
            //               className: 'lable-icon',
            //               html: '<b>'+feature.properties.name+'</b>',
            //               iconSize: [100, 30],
            //         });

            //         L.marker(layer.getBounds().getCenter(), {icon: iconLable}).addTo(map);

            //         layer.on('click', (e)=> {
            //             // console.log(feature.properties.id)
            //             $.getJSON('data-geo/detail/'+feature.properties.id, function(detail) {
            //                 $.each(detail, function(index) {
            //                     // console.log(detail[index].nama)
            //                     // console.log(detail[index].gambar);
            //                     let html = '<div style="text-align: center"><h4>Nama Lokasi: '+detail[index].nama+'</h4>';
            //                         html+='<img height="100px" width="100%" src="assets/gambar/'+detail[index].gambar+'"></div>';
            //                         html+='<h5>Alamat: '+detail[index].alamat+'</h5>';

            //                     L.popup()
            //                         .setLatLng(layer.getBounds().getCenter())
            //                         .setContent(html)
            //                         .openOn(map);
            //                 });
            //             });
            //         });

                    

            //         layer.addTo(map);
            //       }
                  
            //     })
            // });



            //     $.getJSON('assets/geojson/map.geojson', function(json) {

            //     geoLayer = L.geoJson(json, {

            //       style: function(feature) {
            //         return {
            //             fillOpacity:0,
            //             weight: 5,
            //             opacity: 1,
            //             color: "#008cff",
            //             dashArray: "30 10",
            //             lineCap: 'square'
            //         };
            //       },

            //       onEachFeature: function(feature, layer) {

            //         // console.log(feature.properties.name);
            //         //divIcon untuk custum label
            //         const iconLable = L.divIcon({
            //               className: 'lable-icon',
            //               html: '<b>'+feature.properties.name+'</b>',
            //               iconSize: [100, 30],
            //         });

            //         L.marker(layer.getBounds().getCenter(), {icon: iconLable}).addTo(map);

            //         layer.on('click', (e)=> {
            //             // console.log(feature.properties.id)
            //             $.getJSON('data-geo/detail/'+feature.properties.id, function(detail) {
            //                 $.each(detail, function(index) {
            //                     // console.log(detail[index].nama)
            //                     // console.log(detail[index].gambar);
            //                     let html = '<div style="text-align: center"><h4>Nama Lokasi: '+detail[index].nama+'</h4>';
            //                         html+='<img height="100px" width="100%" src="assets/gambar/'+detail[index].gambar+'"></div>';
            //                         html+='<h5>Alamat: '+detail[index].alamat+'</h5>';

            //                     L.popup()
            //                         .setLatLng(layer.getBounds().getCenter())
            //                         .setContent(html)
            //                         .openOn(map);
            //                 });
            //             });
            //         });

            //         layer.addTo(map);
            //       }
                  
            //     })
            // });


            lyr_lokasi = L.markerClusterGroup();
            $.getJSON('assets/geojson/map.geojson', function(json) {

                geoLayer = L.geoJson(json, {

                  style: function(feature) {
                    return {
                        fillOpacity:0,
                        weight: 2,
                        opacity: 1,
                        color: "#008cff",
                        dashArray: "30 10",
                        lineCap: 'square'
                    };
                  },

                  onEachFeature: function(feature, layer) {

                    // console.log(feature.properties.name);
                    //divIcon untuk custum label
                    const iconLable = L.divIcon({
                          className: 'lable-icon',
                          html: '<b>'+feature.properties.name+'</b>',
                          iconSize: [100, 30],
                    });

                    var marker = L.marker(layer.getBounds().getCenter(), {icon: iconLable})//.addTo(map);

                    layer.on('click', (e)=> {
                        // console.log(feature.properties.id)
                        $.getJSON('data-geo/detail/'+feature.properties.id, function(detail) {
                            $.each(detail, function(index) {
                                // console.log(detail[index].nama)
                                // console.log(detail[index].gambar);
                                let html = '<div style="text-align: center"><h4>Nama Lokasi: '+detail[index].nama+'</h4>';
                                    html+='<img height="100px" width="100%" src="assets/gambar/'+detail[index].gambar+'"></div>';
                                    html+='<h5>Alamat: '+detail[index].alamat+'</h5>';

                                L.popup()
                                    .setLatLng(layer.getBounds().getCenter())
                                    .setContent(html)
                                    .openOn(map);
                            });
                        });
                    });

                    lyr_lokasi.addLayer(layer);
                    lyr_lokasi.addLayer(marker);
                    // layer.addTo(map);
                  }
                  
                })
            });

            lyr_sungai = L.markerClusterGroup();
            $.getJSON('assets/geojson/mapSungai.geojson', function(json) {

                geoLayer = L.geoJson(json, {

                  style: function(feature) {
                    return {
                        weight: 5,
                        opacity: 1,
                        color: "purple",
                        dashArray: "15 5",
                        lineCap: 'square'
                    };
                  },

                  onEachFeature: function(feature, layer) {

                    layer.setText(feature.properties.nama, {repeat:false, offset:-9, attributes: {style: "font-size:16pt", fill: "#05ff0d"}});

                    layer.on('click', (e)=> {
                        
                    });

                      lyr_sungai.addLayer(layer);

                    // layer.addTo(map);
                  }
                  
                })
            });

      });

  //pencarian
  function cari($id) {
    geoLayer.eachLayer(function(layer) {

        if (layer.feature.properties.id == $id) {

          map.flyTo(layer.getBounds().getCenter(), 19);
          layer.bindPopup(layer.feature.properties.nama);

        }
    });
  } 

  //LEGEND
  // let legend = L.control({position:'bottomright'});

  // legend.onAdd = function (map) {
  //   let div = L.DomUtil.create('div', 'legend');

  //   labels = ['<strong>Keterangan :</strong>'],

  //   catagories = ['Gedung Pemerintah','Sekolah','Rumah Sakit'];

  //   for (let i = 0; i < catagories.length; i++) {
  //       if(i == 0) {

  //         div.innerHTML += labels.push('<img widht="20" height="23" src="{{ asset('assets/gis/marker-icon/embassy.png')}}"><i class="circle" style="background:#000000"></i> ' + (catagories[i] ? catagories[i] : '+')); 

  //       } else if (i == 1) {

  //         div.innerHTML += labels.push('<img widht="20" height="23" src="{{ asset('assets/gis/marker-icon/embassy.png')}}"><i class="circle" style="background:#000000"></i> ' + (catagories[i] ? catagories[i] : '+')); 

  //       }
  //     // console.log(catagories.length);
  //   }
  // }

  var legend = L.control({position: 'bottomright'});
    legend.onAdd = function (map) {

    var div = L.DomUtil.create('div', 'legend');
    labels = ['<strong>Keterangan :</strong>'],
    categories = ['Gedung Pemerintah','Sekolah','Rumah Sakit'];

    for (var i = 0; i < categories.length; i++) {
        if (i==0) {

            div.innerHTML += 
            labels.push( 
                '<img widht="20" height="23" src="assets/gis/marker-icon/embassy.png"><i class="circle" style="background:#000000"></i> ' +
            (categories[i] ? categories[i] : '+'));

        } else if (i==1) {

            div.innerHTML += 
              labels.push( 
                  '<img widht="20" height="23" src="assets/gis/marker-icon/embassy.png"><i class="circle" style="background:#000000"></i> ' +
              (categories[i] ? categories[i] : '+'));

        } else {
            div.innerHTML += 
                labels.push( 
                    '<img widht="20" height="23" src="assets/gis/marker-icon/embassy.png"><i class="circle" style="background:#000000"></i> ' +
                (categories[i] ? categories[i] : '+'));
          }

    }
        div.innerHTML = labels.join('<br>');
    return div;
    };
    legend.addTo(map);

    //chekbox
    function pilihSungai (v) {
        if (v.checked) {
          map.addLayer(lyr_sungai);
        } else {
          map.removeLayer(lyr_sungai);
        }
    }

    function pilihLokasi (v) {
        if (v.checked) {
          map.addLayer(lyr_lokasi);
        } else {
          map.removeLayer(lyr_lokasi);
        }
    }

    // Mini map

    let petaToMiniMap = L.tileLayer('http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}',{
            maxZoom: 18,
            subdomains:['mt0','mt1','mt2','mt3']
    })

    var miniMap = new L.Control.MiniMap(petaToMiniMap, {
      toggleDisplay:true,
      minimized: true
    }).addTo(map);

    //Distance
    L.control.ruler({
      position: 'topleft',
      lengthUnit:{
        display: 'km', 
        decimal: 2,
        factor: null,
        label: 'Jarak:'
      },
      angleUnit: {
        display: '&deg;',
        label: 'Kemiringan:'
      }
      
    }).addTo(map);

    //Hash
    var hash = new L.Hash(map);

    //mouse coordinate
    L.control.coordinates({
			position:"bottomleft",
			decimals:2,
			decimalSeperator:",",
			labelTemplateLat:"Latitude: {y}",
			labelTemplateLng:"Longitude: {x}"
		}).addTo(map);

		L.control.coordinates({
			position:"topright",
			useDMS:true,
			labelTemplateLat:"N {y}",
			labelTemplateLng:"E {x}",
			useLatLngOrder:true
		}).addTo(map);


</script>

</body>
</html>
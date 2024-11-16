<script>
    function refreshMap(response) {
            map.remove();
            map = L.map('map', {
                center: [-6.967512300523178, 107.65906856904034],
                zoom: 15,
                layers: [google_satellite]
            });

            //Menambahkan beberapa layer ke dalam peta/map
            L.control.layers(baseLayers, overlays).addTo(map);
            map.attributionControl.setPrefix(false);
            map.addLayer(markersLayer);

            // looping variabel datas utuk menampilkan data marker
            var datas = []

            // create a red polyline from an array of LatLng points
            var loc = [];
            console.log(response);
            if(response.telemetriLogs)
            {
                response.telemetriLogs.forEach((telemetriLog, index, array) => {
                    //datas untuk marker
                    datas.push({
                        "loc": [telemetriLog.lat, telemetriLog.long],
                        "klasifikasi": telemetriLog.klasifikasi,
                        "garden_profile": telemetriLog.garden_profile ? telemetriLog.garden_profile.name : '-'
                    });
                    loc.push([telemetriLog.lat, telemetriLog.long]);

                    if(index === (array.length - 1)) {
                        altmeter.value = ((telemetriLog.alt) ? telemetriLog.alt : 0) / 100;
                        gauge.value = (telemetriLog.sog) ? telemetriLog.sog : 0;
                        compas.value = Math.atan2( telemetriLog.my ? telemetriLog.my : 0,  telemetriLog.mx ? telemetriLog.mx : 0 ) * 180 / Math.PI;
                        temperature.value =  telemetriLog.suhu ? telemetriLog.suhu : 0;
                        humidity.value =  telemetriLog.humidity ? telemetriLog.humidity : 0;

                        panel.dataset.rotateX =  telemetriLog.pitch;
                        panel.dataset.rotateY =  telemetriLog.yaw;
                        panel.dataset.rotateZ =  telemetriLog.roll;
                        updatePanelTransform();
                    }
                });
            }

            // create a red polyline from an array of LatLng points
            var latlngs = [];
            response.gardenProfiles.forEach(function (gardenProfile) {
                idx = latlngs.push([])-1;
                gardenProfile.polygon.forEach(function (coor) {
                    latlngs[idx].push([coor['lat'], coor['lng']]);
                });

                polygon = L.polygon(latlngs[idx], {color: 'green'}).bindPopup("<div class='my-2'><strong>Nama: </strong> <br>"+ gardenProfile.name +"</div>").addTo(map);
            });

            for (i in datas) {
                var title = datas[i].title,
                    location = datas[i].loc,
                    klasifikasi = datas[i].klasifikasi
                    garden_profile = datas[i].garden_profile
                    marker = new L.Marker(new L.latLng(location), {
                        icon: (klasifikasi == 1) ? greenIcon : redIcon,
                        klasifikasi: klasifikasi,
                        garden_profile: garden_profile
                    }).bindPopup(
                        "<div class='my-2'><strong>Koordinat:</strong> <br>"+location+"</div>"+
                        "<div class='my-2'><strong>Klasifikasi:</strong> <br>"+klasifikasi+"</div>"+
                        "<div class='my-2'><strong>Kebun:</strong> <br>"+garden_profile+"</div>"
                    );
                // if (klasifikasi == 1) {
                    markersLayer.addLayer(marker);
                // }
            }

            var polyline = L.polyline(loc, {color: 'red'}).addTo(map);

            var tsp = [];
            if(response.traveling_salesmen) {
                response.traveling_salesmen.forEach((travel, index, array) => {
                    console.log("Hello?");
                    tsp.push([travel.lat, travel.long]);
                });
                
                var polyline = L.polyline(tsp, {color: 'blue'}).addTo(map);
            }
            
            if(loc.length > 0){
                // zoom the map to the polyline
                map.fitBounds(polyline.getBounds());
            }
        }
</script>
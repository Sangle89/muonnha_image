var map = undefined;
var marker = null;
var geocoder;
var infowindow = null;
var addressReturn;
var latlngReturn;

function initialize(lat, lng) {
    try {
        infowindow = new google.maps.InfoWindow();

        var myOptions = {
            scrollwheel: false, 
            zoom: 14,
            center: new google.maps.LatLng(lat, lng),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        map = new google.maps.Map(document.getElementById('map_canvas'), myOptions);
        if (lat == "" || lng == "" || lat == null || lng == null) {
            lat = 21.0287974;
            lng = 105.8523542;
        }
        var myLatlng = new google.maps.LatLng(lat, lng);
        marker = new google.maps.Marker({
            map: map,
            position: myLatlng,
            draggable: true
        });
        map.setCenter(myLatlng);

        /*bds*/
        var bdstrananh = new google.maps.Marker({
            icon: {
                path: 'M -3,0 0,-3 3,0 0,3 z',
                strokeColor: "#cec9c1",
                scale: 3
            },
            map: map,
            position: new google.maps.LatLng(10.871692, 106.535366)
        });

        google.maps.event.addListener(map, 'zoom_changed', function () {
            var zoom = map.getZoom();
            if (zoom <= 17) {
                if (zoom == 15) bdstrananh.setMap(map);
                else bdstrananh.setMap(null);
            } else {
                bdstrananh.setMap(map);
            }
        });

        var contentString = '<style>a{text-decoration: none;color: blue}</style><div id="content">' +
	          '<div id="siteNotice">' +
	          '</div>' +
	          '<strong id="firstHeading" class="firstHeading">Công ty bất động sản Trần Anh</strong>' +
	          '<div id="bodyContent">' +
	          'phan văn hớn quận 12, 58a cầu Lớn, Xuân Thới Thượng, Hóc Môn, Ho Chi Minh City, Vietnam' +
	          '<p><a href="http://datnengiatot.net" target="_blank" rel="nofollow">datnengiatot.net</a></p>' +
	          '<p><a href="https://plus.google.com/111846113810994069762/about?socpid=238&socfid=maps_api_v3:smartmapsiw">more info</a></p>' +
	          '</div>' +
	          '</div>';

        var infowindowbdstrananh = new google.maps.InfoWindow({
            content: contentString
        });

        google.maps.event.addListener(bdstrananh, 'click', function () {
            if (infowindowbdstrananh != null)
                infowindowbdstrananh.open(map, bdstrananh);
        });
        /*end bds*/

        google.maps.event.addListener(map, 'click', function (event) {
            placeMarker(event.latLng);
        });
        google.maps.event.addListener(marker, 'dragstart', function () {
            if (infowindow != null)
                infowindow.close();
        });
        google.maps.event.addListener(marker, 'dragend', getAddress);



        geocoder = new google.maps.Geocoder();
        getAddress();

    } catch (ex) {

    }
}
function initializeAddress(lat, lng, address) {
    try {
        if (lat != "0" && lng != "0") {
            infowindow = new google.maps.InfoWindow();

            var myOptions = {
                scrollwheel: false,
                zoom: 14,
                center: new google.maps.LatLng(lat, lng),
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            map = new google.maps.Map(document.getElementById('map_canvas'), myOptions);
            if (lat == "" || lng == "" || lat == null || lng == null) {
                lat = 21.0287974;
                lng = 105.8523542;
            }
            var myLatlng = new google.maps.LatLng(lat, lng);
            marker = new google.maps.Marker({
                map: map,
                position: myLatlng,
                draggable: true
            });
            map.setCenter(myLatlng);
            google.maps.event.addListener(map, 'click', function (event) {
                placeMarker(event.latLng);
            });
            google.maps.event.addListener(marker, 'dragstart', function () {
                if (infowindow != null)
                    infowindow.close();
            });
            google.maps.event.addListener(marker, 'dragend', getAddress);


            /*bds*/
            var bdstrananh = new google.maps.Marker({
                icon: {
                    path: 'M -3,0 0,-3 3,0 0,3 z',
                    strokeColor: "#cec9c1",
                    scale: 3
                },
                map: map,
                position: new google.maps.LatLng(10.871692, 106.535366)
            });

            google.maps.event.addListener(map, 'zoom_changed', function () {
                var zoom = map.getZoom();
                if (zoom <= 17) {
                    if (zoom == 15) bdstrananh.setMap(map);
                    else bdstrananh.setMap(null);
                } else {
                    bdstrananh.setMap(map);
                }
            });

            var contentString = '<style>a{text-decoration: none; color: blue}</style><div id="content">' +
	          '<div id="siteNotice">' +
	          '</div>' +
	          '<strong id="firstHeading" class="firstHeading">Công ty bất động sản Trần Anh</strong>' +
	          '<div id="bodyContent">' +
	          'phan văn hớn quận 12, 58a cầu Lớn, Xuân Thới Thượng, Hóc Môn, Ho Chi Minh City, Vietnam' +
	          '<p><a href="http://datnengiatot.net" target="_blank" rel="nofollow">datnengiatot.net</a></p>' +
	          '<p><a href="https://plus.google.com/111846113810994069762/about?socpid=238&socfid=maps_api_v3:smartmapsiw">more info</a></p>' +
	          '</div>' +
	          '</div>';

            var infowindowbdstrananh = new google.maps.InfoWindow({
                content: contentString
            });

            google.maps.event.addListener(bdstrananh, 'click', function () {
                if (infowindowbdstrananh != null)
                    infowindowbdstrananh.open(map, bdstrananh);
            });
            /*end bds*/


            geocoder = new google.maps.Geocoder();
            showAdd(address);
        } else {
            $("#map_canvas").css('display', 'none');
        }
    } catch (ex) {
        console.log(ex);
    }
}

function placeMarker(location) {

    try {

        marker.setMap(null);
        marker = new google.maps.Marker({
            position: location,
            map: map,
            draggable: true
        });
        google.maps.event.addListener(marker, 'dragstart', function () {
            if (infowindow != null)
                infowindow.close();
        });
        google.maps.event.addListener(marker, 'dragend', getAddress);
        map.setCenter(location);
        getAddress();
    } catch (ex) {
        console.log(ex);
    }
}
function showProjectLocation(lat, lng, name) {
    marker.setMap(null);
    map.setCenter(new google.maps.LatLng(lat, lng));
    document.getElementById('txtPositionX').value = lat;
    document.getElementById('txtPositionY').value = lng;
    marker = new google.maps.Marker({
        map: map,
        position: new google.maps.LatLng(lat, lng),
        draggable: true
    });
    if (lat != '' && lng != '' && lat != 0 && lng != 0) {
        geocoder.geocode({ 'latLng': new google.maps.LatLng(lat, lng) }, function (results2, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results2 != null && results2[0] != null) {
                    addressReturn = results2[0].formatted_address;
                    if (infowindow != null) {
                        infowindow.setContent("<span id='address'><b>Địa chỉ : </b>" + name + "</span>");
                        infowindow.open(map, marker);
                    }
                }
            } else {
               
            }
        });
    }
    else {
        showLocation(name);
    }


    google.maps.event.addListener(marker, 'dragstart', function () {
        if (infowindow != null)
            infowindow.close();
    });
    google.maps.event.addListener(marker, 'dragend', getAddress);
}

function showLocation(address) {
    if (address != null && address != '') {
        var add = address.split(',');
        if (add.length >= 3) {
            if ($.trim(add[add.length - 3]).toLowerCase() == "thanh xuân") {
                add[add.length - 3] = "Thanh Xuân Bắc";
            }
        }
        address = add.join(',');

        if (marker != null) marker.setMap(null);
        geocoder = new google.maps.Geocoder();
        geocoder.geocode({ 'address': address }, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                map.setCenter(results[0].geometry.location);
                marker = new google.maps.Marker({
                    map: map,
                    position: results[0].geometry.location,
                    draggable: true
                });
                document.getElementById('txtPositionX').value = results[0].geometry.location.lat();
                document.getElementById('txtPositionY').value = results[0].geometry.location.lng();
                addressReturn = results[0].formatted_address;

                if (infowindow != null) {
                    infowindow.setContent("<span id='address'><b>Địa chỉ : </b>" + address + "</span>");
                    infowindow.open(map, marker);
                }
            } else {
            }
            google.maps.event.addListener(marker, 'dragstart', function () {
                if (infowindow != null)
                    infowindow.close();
            });
            google.maps.event.addListener(marker, 'dragend', getAddress);
        });

    } else {
        alert("Địa chỉ không hợp lệ");
    }
}
function getAddress() {

    try {

        var point = marker.getPosition();
 
        var lat = point.lat();
        var lng = point.lng();
        document.getElementById('txtPositionX').value = lat;
        document.getElementById('txtPositionY').value = lng;
        var latlng = new google.maps.LatLng(lat, lng);
 
        geocoder.geocode({ 'latLng': latlng }, function (results2, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results2 != null && results2[0] != null) {
                    addressReturn = results2[0].formatted_address;
                    if (infowindow != null) {
                        infowindow.setContent("<span id='address'><b>Địa chỉ : </b>" + results2[0].formatted_address + "</span>");
                        infowindow.open(map, marker);
                    }
                }
            } else {
            }
        });
        map.setCenter(point);
    } catch (ex) {
        console.log(ex);
    }
}


function showAdd(address) {
    try {
        var point = marker.getPosition();
 
        var lat = point.lat();
        var lng = point.lng();
        document.getElementById('txtPositionX').value = lat;
        document.getElementById('txtPositionY').value = lng;
        var latlng = new google.maps.LatLng(lat, lng);
 
        geocoder.geocode({ 'latLng': latlng }, function (results2, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results2[0]) {
                    if (infowindow != null) {
                        if (address != '') {
                            infowindow.setContent("<span id='address'><b>Địa chỉ : </b>" + address + "</span>");
                        } else {
                            infowindow.setContent("<span id='address'><b>Địa chỉ : </b>" + results2[0].formatted_address + "</span>");
                        }
                        infowindow.open(map, marker);
                    }
                    addressReturn = results2[0].formatted_address;
                }
            }
            else {
            }
        });
        map.setCenter(point);
    } catch (ex) {
        console.log(ex);
    }
}

function strAddress() {
    return addressReturn;
}

function strLatLng() {

    try {
        var lat = $('#txtPositionX').val();
        var lng = $('#txtPositionY').val();
        return lat + "," + lng;
    } catch (ex) {
        console.log(ex);
    }
}
$(function () { 
    initializeAddress($('#hddLatitude').val(), $('#hddLongtitude').val(), $('#hddDiadiem').val());
});
function ShowLocation() {

    var address = "";

    var cityCode = $("#listCity2 option:selected").val();
    var distId = $('#listDistrict2 option:selected').val();
    var street = $('#listStreet2 option:selected').val();
    var ward = $('#listWard2 option:selected').val();

    if ($('#listProject2').val() <= 0) {

        if (street != '' && street > 0) {
            address = $('#listStreet2 option:selected').text() + ", ";
        } else if (ward != '' && ward > 0) {
            address = address + 'phường ' + $('#listWard2 option:selected').text() + ", ";
        }
    } else {
        address = $('#listProject2 option:selected').text() + ", " + address;
    }
    if (distId != '' && distId > 0) {
        address = address + $('#listDistrict2 option:selected').text() + ", ";
    }
    if (cityCode != '-1') {
        address = address + $('#listCity2 option:selected').text() + " ";
    }

    address = address + ", Việt Nam"; 

    var mySplitResult = strLatLng().split(",");
    $("#hddLatitude").val(mySplitResult[0]);
    $("#hddLongtitude").val(mySplitResult[1]);

    showLocation(address);

    $('#mapinfo').show();
}

function loadProjectMap(projectId) {
    var address = "";
    var cityCode = $("#hddcboCityP").val();
    var distId = $('#hddcboDistP').val();
    var street = $('#hddcboStreetP').val();
    var ward = $('#hddcboWardP').val();
    if (projectId != '' && projectId != -1) {
        address = $('#cboProjectP .pncontainer li[rel="' + projectId + '"]').html() + ", " + address;
    }
    else if (street != '' && street > 0) {
        address = $('#cboStreetP .pncontainer li[rel="' + street + '"]').html() + ", ";
    }
    else if (ward != '' && ward > 0) {
        address = address + $('#cboWardP .pncontainer li[rel="' + ward + '"]').html() + ", ";
    }
    if (distId != '' && distId > 0) {
        address = address + $('#cboDistP .pncontainer li[rel="' + distId + '"]').html() + ", ";
    }
    if (cityCode != '-1') {
        address = address + $('#cboCityP .pncontainer li[rel="' + cityCode + '"]').html() + " ";
    }
    address = address + ", Việt Nam";

    initialize('', '');
    var lat = $('#cboProjectP .pncontainer li[rel="' + projectId + '"]').attr('lat');
    var lgn = $('#cboProjectP .pncontainer li[rel="' + projectId + '"]').attr('lng');
    if (projectId != '' && projectId != -1 && lat != 'null' && lgn != 'null') {
        showProjectLocation(lat, lgn, address);
    } else {
        showLocation(address);
    }

}
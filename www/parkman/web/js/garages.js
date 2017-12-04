var baseUrl = "http://parkman.local/";

function getByLatLng()
{
    var lat = document.getElementById('latitude').value;
    var lng = document.getElementById('longitude').value;
    var proximity = document.getElementById('proximity_geo').value;

    var country = document.getElementById('country').value;
    getCall(baseUrl + "garage/by-geo?lat=" + lat + "&lng=" + lng + "&proximity=" + proximity).always(function(data){
        var map = initMap(parseFloat(data.centerLng), parseFloat(data.centerLat), parseInt(data.zoom));
        addMarkers(data.data,map);
    });
}


function getByOwner()
{
    var owner = document.getElementById('owner').value;

    getCall(baseUrl + "garage/by-owner?owner=" + owner).always(function(data){
        var map = initMap(parseFloat(data.centerLng), parseFloat(data.centerLat), parseInt(data.zoom));
        addMarkers(data.data,map);
    });
}


function getByCountry()
{
    var country = document.getElementById('country').value;
    var proximity = document.getElementById('proximity_country').value;

    getCall(baseUrl + "garage/by-country?country=" + country + "&proximity=" + proximity).always(function(data){
        var map = initMap(parseFloat(data.centerLng), parseFloat(data.centerLat), parseInt(data.zoom));
        addMarkers(data.data,map);
    });
}


function getCall(url, params){
    return $.get( url, params )
        .done(function(data) {

        })
        .fail(function() {
            alert( "error" );
        })
        .always(function(data) {
            document.getElementById("display-json").innerHTML = JSON.stringify(data.data, undefined, 2);
            return data;
        });
}

function initMap(centerLng, centerLat, zoom) {
    var coord = {lat: centerLat, lng: centerLng};

    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: zoom,
        //center: new google.maps.LatLng(centerLat, centerLng),
        center: coord,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var marker = new google.maps.Marker({
        position: new google.maps.LatLng(centerLat, centerLng),
        map: map,
/*        icon : 'http://cs.anoraks.ru/CwABAIQAHgEe_8P-ww/ML0QA7qS1KxAxMNEi3f-cw/sv/image/88/de/96/313470/68/map-pin.png'*/
        icon : '/images/user_marker.png'
    });

    google.maps.event.addListener(marker, 'click', (function(marker) {
        return function() {
            infowindow.setContent(
                "<p>You are here</p>"
            );
            infowindow.open(map, marker);
        }
    })(marker));

    return map;
}


function addMarkers(data, map)
{
    var infowindow = new google.maps.InfoWindow();

    var marker, i;

    for (i = 0; i < data.length; i++) {
        marker = new google.maps.Marker({
            position: new google.maps.LatLng(data[i]['lat'], data[i]['lng']),
            map: map,
            icon : '/images/garage_marker.png'
        });

        google.maps.event.addListener(marker, 'click', (function(marker, i) {
            return function() {
                infowindow.setContent(
                    "<p>" + data[i]['owner'] + "</p>" +
                    "<p>" + parseInt(data[i]['distance']) + "km away </p>" +
                    "<p> Hourly Price : " + data[i]['hourly'] + "€ </p>"
                );
                infowindow.open(map, marker);
            }
        })(marker, i));
    }
}
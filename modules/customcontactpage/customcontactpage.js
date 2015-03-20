$(document).ready(function(){
    map = new google.maps.Map(document.getElementById('mapcontact'), {
        center: new google.maps.LatLng(customcontactpage_latitude, customcontactpage_longitude),
        zoom: 10,
        mapTypeId: 'roadmap',
        mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU}
    });


var myLatlng = new google.maps.LatLng(customcontactpage_latitude,customcontactpage_longitude);

var marker = new google.maps.Marker({
    position: myLatlng
});

// To add the marker to the map, call setMap();
marker.setMap(map);

   
});
setTimeout(function () {
    jQuery("div#google").html('<script>function initAutocomplete(){for(var address=$(".addr"),i=0;i<address.length;i++)new google.maps.places.Autocomplete(address[i],{types:["geocode"]});}</script><script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDqgw69yW-Mry8DkQ9VI1gcD90BxKvSiSQ&libraries=places&callback=initAutocomplete" async defer></script>');
}, 2000);

$(document).off("click", ".array-add-button").on("click", ".array-add-button", function () {
    setTimeout(function () {
        jQuery("div#google").html('<script>function initAutocomplete(){for(var address=$(".addr"),i=0;i<address.length;i++)new google.maps.places.Autocomplete(address[i],{types:["geocode"]});}</script><script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDqgw69yW-Mry8DkQ9VI1gcD90BxKvSiSQ&libraries=places&callback=initAutocomplete" async defer></script>');
    }, 1000);
});

$(document).off("click", ".array-remove-button").on("click", ".array-remove-button", function () {
    setTimeout(function () {
        jQuery("div#google").html('<script>function initAutocomplete(){for(var address=$(".addr"),i=0;i<address.length;i++)new google.maps.places.Autocomplete(address[i],{types:["geocode"]});}</script><script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDqgw69yW-Mry8DkQ9VI1gcD90BxKvSiSQ&libraries=places&callback=initAutocomplete" async defer></script>');
    }, 1000);
});
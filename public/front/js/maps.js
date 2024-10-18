(g => {
    var h, a, k, p = "The Google Maps JavaScript API",
        c = "google",
        l = "importLibrary",
        q = "__ib__",
        m = document,
        b = window;
    b = b[c] || (b[c] = {});
    var d = b.maps || (b.maps = {}),
        r = new Set,
        e = new URLSearchParams,
        u = () => h || (h = new Promise(async (f, n) => {
            await (a = m.createElement("script"));
            e.set("libraries", [...r] + "geometry,places");
            for (k in g) e.set(k.replace(/[A-Z]/g, t => "_" + t[0].toLowerCase()), g[k]);
            e.set("callback", c + ".maps." + q);
            e.set("region", "SA");
            e.set("language", "{{ app()->getLocale() }}");
            a.src = `https://maps.${c}apis.com/maps/api/js?` + e;
            d[q] = f;
            a.onerror = () => h = n(Error(p + " could not load."));
            a.nonce = m.querySelector("script[nonce]")?.nonce || "";
            m.head.append(a)
        }));
    d[l] ? console.warn(p + " only loads once. Ignoring:", g) : d[l] = (f, ...n) => r.add(f) && u().then(() =>
        d[l](f, ...n))
})({
    key: "AIzaSyDRUymjllb-UATe6qC5VzBWaPSjzrkEsx0",
    v: "weekly",
    // Use the 'v' parameter to indicate the version to use (weekly, beta, alpha, etc.).
    // Add other bootstrap parameters as needed, using camel case.
});

//
// $(document).ready(function () {
//
//     let map;
//
//     async function initMap() {
//         const {
//             Map
//         } = await google.maps.importLibrary("maps");
//         var center = {
//             lat: 24.704265,
//             lng: 46.738586
//         }; // Replace with your desired center coordinates
//         var radius = 30000; // Radius in meters (adjust as needed)
//         var location = "";
//         var locaitonTitle = "";
//         map = new Map(document.getElementById("ms-map"), {
//             center: {
//                 lat: 24.774265,
//                 lng: 46.738586
//             },
//             zoom: 9,
//             streetViewControl: false,
//             componentRestrictions: {
//                 country: 'sa',
//                 administrativeArea: 'Riyadh'
//             },
//             //current location controller
//
//         });
//
//         var polygonCoords = [{
//             lat: 24.950,
//             lng: 46.68
//         },
//             {
//                 lat: 24.90,
//                 lng: 46.6
//             },
//             {
//                 lat: 24.8,
//                 lng: 46.55
//             },
//             {
//                 lat: 24.7,
//                 lng: 46.5
//             },
//             {
//                 lat: 24.528404218323402,
//                 lng: 46.44312304070009
//             },
//             {
//                 lat: 24.497846305789974,
//                 lng: 46.49749807848963
//             },
//             {
//                 lat: 24.475898977752042,
//                 lng: 46.55799823144531
//             },
//             {
//                 lat: 24.459524780194982,
//                 lng: 46.70408712613028
//             },
//             {
//                 lat: 24.470899354139874,
//                 lng: 46.87110858300781
//             },
//             {
//                 lat: 24.51167321078985,
//                 lng: 46.92089191525416
//             },
//             {
//                 lat: 24.535878962556094,
//                 lng: 46.93702655175781
//             },
//             {
//                 lat: 24.595830298529606,
//                 lng: 46.930160096679685
//             },
//             {
//                 lat: 24.646568603936636,
//                 lng: 46.931288076365384
//             },
//             {
//                 lat: 24.73186312153594,
//                 lng: 46.913680604492185
//             },
//             {
//                 lat: 24.879639850621846,
//                 lng: 46.863368801346475
//             },
//             {
//                 lat: 24.927605540261304,
//                 lng: 46.82561904124119
//             },
//             {
//                 lat: 24.964894032517954,
//                 lng: 46.759872010742185
//             },
//
//         ];
//
//
//         var polygon = new google.maps.Polygon({
//             strokeColor: '#136e82', // Red border
//             strokeOpacity: 0,
//             fillColor: '#136e82', // Light red fill
//             fillOpacity: 0,
//             map: map,
//             paths: polygonCoords,
//         });
//
//         var marker = new google.maps.Marker({
//             map: map,
//             draggable: false,
//
//         });
//
//         var notAvailableLabel = new google.maps.InfoWindow({
//             content: "{{ __('admin.Not Available') }}",
//             'fillColor': 'red',
//         });
//
//         var yourLocatoinLabel = new google.maps.InfoWindow({
//             content: "{{ __('admin.Your Location') }}",
//         });
//
//         polygon.addListener('click', function (event) {
//
//             notAvailableLabel.close();
//             marker.setPosition(event.latLng);
//             location = event.latLng.lat() + ',' + event.latLng.lng();
//             sessionStorage.setItem('coordinates', JSON.stringify({
//                 lat: event.latLng.lat(),
//                 lng: event.latLng.lng()
//             }));
//             $('#ms-map-select').prop('disabled', false);
//             var geocoder = new google.maps.Geocoder();
//             geocoder.geocode({
//                 'latLng': event.latLng
//             }, function (results, status) {
//                 if (status == google.maps.GeocoderStatus.OK) {
//                     if (results[0]) {
//
//                         // Extract the location name from the address components
//                         var locationName = results[0].formatted_address;
//                         $('#ms-map-search').val(locationName);
//                         locaitonTitle = locationName;
//                         $('#ms-map-selected').text(locationName);
//                         $('#ms-map-selected').attr('style', 'color: black;');
//
//
//                     } else {
//                         console.log('No results found');
//                     }
//                 } else {
//                     console.log('Geocoder failed due to: ' + status);
//                 }
//             });
//         });
//
//
//         var bounds = new google.maps.LatLngBounds(polygonCoords[0], polygonCoords[2]);
//         var locationAutoComplete = new google.maps.places.Autocomplete(
//             document.getElementById("ms-map-search"), {
//                 bounds: bounds,
//             }
//         );
//
//
//         locationAutoComplete.addListener("place_changed", function () {
//             var place = locationAutoComplete.getPlace();
//             if (!place.geometry || !place.geometry.location) {
//
//                 window.alert("No details available for input: '" + place.name + "'");
//                 return;
//             }
//
//             if (google.maps.geometry.poly.containsLocation(place.geometry.location, polygon)) {
//                 $('#ms-map-select').prop('disabled', false);
//                 location = place.geometry.location.lat() + ',' + place.geometry.location.lng();
//                 locaitonTitle = place.formatted_address;
//                 marker.setPosition(place.geometry.location);
//                 $('#ms-map-selected').text(place.formatted_address);
//                 $('#ms-map-selected').attr('style', 'color: black;');
//                 // Save coordinates to sessionStorage
//
//             } else {
//
//                 marker.setPosition(place.geometry.location);
//                 notAvailableLabel.open(map, marker);
//                 location = '';
//                 $('#ms-map-selected').text("{{ __('admin.This location is not available.') }}");
//                 $('#ms-map-selected').attr('style', 'color: red;');
//                 $('#ms-map-select').prop('disabled', true);
//             }
//             map.setCenter(place.geometry.location);
//             map.setZoom(15);
//         });
//
//         // Add a click event listener to the map
//         google.maps.event.addListener(map, 'click', function (event) {
//
//
//             var clickedLocation = event.latLng;
//             if (google.maps.geometry.poly.containsLocation(clickedLocation, polygon)) {
//                 $('#ms-map-select').prop('disabled', false);
//                 location = event.latLng.lat() + ',' + event.latLng.lng();
//
//                 sessionStorage.setItem('coordinates', JSON.stringify({
//                     lat: event.latLng.lat(),
//                     lng: event.latLng.lng()
//                 }));
//             } else {
//
//                 var geocoder = new google.maps.Geocoder();
//
//                 geocoder.geocode({
//                     'location': event.latLng
//                 }, function (results, status) {
//                     if (status == 'OK') {
//                         if (results[0]) {
//                             $('#ms-map-search').val(results[0].formatted_address);
//                             locaitonTitle = results[0].formatted_address;
//                         }
//                     }
//                 });
//                 marker.setPosition(clickedLocation);
//                 notAvailableLabel.open(map, marker);
//                 location = '';
//                 locaitonTitle = '';
//                 $('#ms-map-selected').text("{{ __('admin.This location is not available.') }}");
//                 $('#ms-map-selected').attr('style', 'color: red;');
//                 $('#ms-map-select').prop('disabled', true);
//             }
//
//         });
//
//
//         var getLocationButton = document.createElement('button');
//         //add type button
//         getLocationButton.setAttribute('type', 'button');
//         getLocationButton.setAttribute('title', 'Get Location');
//         getLocationButton.setAttribute('class', 'ms-current-location-btn');
//         getLocationButton.innerHTML = `<i class="fa-solid fa-crosshairs"></i>`;
//         map.controls[google.maps.ControlPosition.RIGHT_CENTER].push(getLocationButton);
//
//         getLocationButton.addEventListener('click', function () {
//             // Get the user's current location using the browser's geolocation API
//             if (navigator.geolocation) {
//                 navigator.geolocation.getCurrentPosition(function (position) {
//
//                     var userLocation = {
//                         lat: position.coords.latitude,
//                         lng: position.coords.longitude
//                     };
//
//                     var geocoder = new google.maps.Geocoder();
//
//                     geocoder.geocode({
//                         'location': userLocation
//                     }, function (results, status) {
//                         if (status == 'OK') {
//                             if (results[0]) {
//                                 $('#ms-map-search').val(results[0].formatted_address);
//                                 locaitonTitle = results[0].formatted_address;
//                             }
//                         }
//                     });
//
//
//                     if (google.maps.geometry.poly.containsLocation(userLocation, polygon)) {
//                         marker.setPosition(userLocation);
//
//                         location = event.latLng.lat() + ',' + event.latLng.lng();
//                         yourLocatoinLabel.open(map, marker);
//                         $('#ms-map-select').prop('disabled', false);
//                         sessionStorage.setItem('coordinates', JSON.stringify({
//                             lat: event.latLng.lat(),
//                             lng: event.latLng.lng()
//                         }));
//                     } else {
//                         // Display an error message or prevent further actions
//                         marker.setPosition(userLocation);
//                         notAvailableLabel.open(map, marker);
//                         $('#ms-map-selected').text(
//                             "{{ __('admin.This location is not available.') }}");
//                         $('#ms-map-selected').attr('style', 'color: red;');
//                         location = '';
//                         $('#ms-map-select').prop('disabled', true);
//                     }
//
//                     // Center the map on the user's current location
//                     map.setCenter(userLocation);
//                 });
//             } else {
//                 alert('Geolocation is not supported by your browser.');
//             }
//         });
//
//         if (navigator.geolocation) {
//             navigator.geolocation.getCurrentPosition(function (position) {
//
//                 var userLocation = {
//                     lat: position.coords.latitude,
//                     lng: position.coords.longitude
//                 };
//
//                 console.log(userLocation);
//
//
//                 var geocoder = new google.maps.Geocoder();
//
//                 geocoder.geocode({
//                     'location': userLocation
//                 }, function (results, status) {
//                     console.log('status', status);
//
//                     if (status == 'OK') {
//                         if (results[0]) {
//                             $('#ms-map-search').val(results[0].formatted_address);
//                             locaitonTitle = results[0].formatted_address;
//                             if (google.maps.geometry.poly.containsLocation(userLocation, polygon)) {
//                                 marker.setPosition(userLocation);
//
//                                 location = userLocation.lat + ',' + userLocation.lng;
//                                 ;
//
//                                 console.log('location', locaitonTitle);
//
//
//                                 $('#ms-map-select').prop('disabled', false);
//
//                                 $('#ms-map-selected').text(locaitonTitle);
//                                 $('#ms-map-selected').attr('style', 'color: black;');
//                                 $.ajax({
//                                     url: "{{ route(service_type() . '.check-point') }}",
//                                     type: "POST",
//                                     data: {
//                                         service_id: "{{ $service->id }}",
//                                         location: location,
//                                         _token: "{{ csrf_token() }}",
//                                         type: '2',
//                                     },
//                                     success: function (response) {
//
//                                         var currentDate = new Date();
//                                         currentYear = currentDate.getFullYear();
//                                         currentMonth = currentDate.getMonth();
//                                         currentDay = currentDate.getDate();
//
//
//                                         var datetime = new Date(response.date);
//
//
//                                         selectedDate = datetime.getDate() + "/" + (datetime.getMonth() + 1) + "/" + datetime.getFullYear();
//                                         selectedTime = getTimeFormatted(datetime);
//
//                                         $('#employees').html('');
//                                         if (response.employees.length > 0) {
//                                             response.employees[0].forEach(function (employee) {
//
//                                                 $('#employees').append('<option value="' + employee.id + '">' +
//                                                     ('{{ app()->getLocale() }}' === 'en' ? employee.name_english : employee.name_arabic) +
//                                                     '</option>');
//                                             })
//                                         } else {
//                                             $('#employees').html(
//                                                 '<option value="" >Please choose another date or time</option>'
//                                             );
//                                         }
//
//
//                                         Dtpcalendar(input = "#ms-datetime-picker", year = currentYear, month = currentMonth, day = currentDay,
//                                             selectedDate = selectedDate, selectedTime = selectedTime, timeTable = response.times, ajaxData = {
//                                                 url: "{{ route('getEmp') }}",
//                                                 token: "{{ csrf_token() }}",
//                                                 location: location,
//                                                 id: "{{ $service->id }}",
//                                                 type: '2',
//                                             }, lang = "{{ app()->getLocale() }}", d = 1);
//                                     }
//                                 });
//
//                             } else {
//                                 // Display an error message or prevent further actions
//                                 marker.setPosition(userLocation);
//                                 notAvailableLabel.open(map, marker);
//                                 $('#ms-map-selected').text(
//                                     "{{ __('admin.This location is not available.') }}");
//                                 $('#ms-map-selected').attr('style', 'color: red;');
//                                 location = '';
//                                 $('#ms-map-select').prop('disabled', true);
//                             }
//
//                         }
//                     }
//                 });
//
//                 // Center the map on the user's current location
//                 map.setCenter(userLocation);
//             });
//         } else {
//             alert('Geolocation is not supported by your browser.');
//         }
//
//         $('#ms-map-select').on('click', function () {
//             $('#ms-map-selected-location').val(location);
//             $('#ms-map-modal-container').removeClass('show');
//             $('#ms-map-modal-show').css('display', 'none');
//             $('#selected-location-input').addClass('show');
//             $('#selected-location-input .location-title').text(locaitonTitle);
//             $('body').removeClass('disable-scroll');
//
//             $.ajax({
//                 url: "{{ route(service_type() . '.check-point') }}",
//                 type: "POST",
//                 data: {
//                     service_id: "{{ $service->id }}",
//                     location: location,
//                     _token: "{{ csrf_token() }}",
//                     type: '1',
//                 },
//                 success: function (response) {
//
//
//                     var currentDate = new Date();
//                     currentYear = currentDate.getFullYear();
//                     currentMonth = currentDate.getMonth();
//                     currentDay = currentDate.getDate();
//
//
//                     var datetime = new Date(response.date);
//
//
//                     selectedDate = datetime.getDate() + "/" + (datetime.getMonth() + 1) + "/" + datetime.getFullYear();
//                     selectedTime = getTimeFormatted(datetime);
//
//
//                     $('#employees').html('');
//                     if (response.employees.length > 0) {
//                         response.employees[0].forEach(function (employee) {
//
//                             $('#employees').append('<option value="' + employee.id + '">' +
//                                 ('{{ app()->getLocale() }}' === 'en' ? employee.name_english : employee.name_arabic) +
//                                 '</option>');
//                         })
//                     } else {
//                         $('#employees').html(
//                             '<option value="" >Please choose another date or time</option>'
//                         );
//                     }
//
//
//                     Dtpcalendar(input = "#ms-datetime-picker", year = currentYear, month = currentMonth, day = currentDay,
//                         selectedDate = selectedDate, selectedTime = selectedTime, timeTable = response.times, ajaxData = {
//                             url: "{{ route('getEmp') }}",
//                             token: "{{ csrf_token() }}",
//                             location: location,
//                             id: "{{ $service->id }}",
//                             type: '1',
//                         }, lang = "{{ app()->getLocale() }}", d = 1);
//                 }
//             });
//         });
//
//
//     }
//
//
//     initMap();
//
//     // Handle Save Address button click
//     $('#save-address').on('click', function () {
//         // Retrieve coordinates from sessionStorage
//         var coordinates = JSON.parse(sessionStorage.getItem('coordinates'));
//
//         if (coordinates) {
//             // Set the values of latitude and longitude inputs
//             $('#latitude').val(coordinates.lat);
//             $('#longitude').val(coordinates.lng);
//
//             // Close the modal
//             $('#address-list-modal').hide();
//         } else {
//             alert('No location selected!');
//         }
//     });
// });

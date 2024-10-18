@extends('Dashboard.layouts.master')

@section('css_dashboard')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <!-- Leaflet Search CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-search/dist/leaflet-search.min.css" />

@endsection

@section('title_page')
    أضافة مكان جديد
@endsection

@section('page-body')

    <div class="card">
        <div class="card-header">
            <form id="addPlaceForm" action="{{ route('admin.places.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">اسم المكان</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="coordinates">إحداثيات المكان</label>
                            <textarea name="coordinates" id="coordinates" class="form-control" readonly required></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label style="color: red">من فضلك قم بأختيار حدود المكان</label>
                    <div id="map" style="height: 500px;"></div>
                </div>
                <button type="submit" class="btn btn-primary">أضافة مكان</button>
            </form>
        </div>
    </div>
@endsection

@section('script_dashboard')
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-search/dist/leaflet-search.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pnotify/5.2.0/PNotify.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var map = L.map('map').setView([23.8859, 45.0792], 6);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© Eng. Andrew ©'
            }).addTo(map);

            var bounds = [
                [32.154, 35.633],
                [16.368, 55.666]
            ];
            map.fitBounds(bounds);

            var latlngs = [];
            var polygon;

            map.on('click', function(e) {
                latlngs.push([e.latlng.lat, e.latlng.lng]);

                if (polygon) {
                    map.removeLayer(polygon);
                }

                polygon = L.polygon(latlngs).addTo(map);
                updateCoordinates();
            });

            function updateCoordinates() {
                document.getElementById('coordinates').value = JSON.stringify(latlngs);
            }

            var searchControl = new L.Control.Search({
                url: 'https://nominatim.openstreetmap.org/search?format=json&q={s}',
                jsonpParam: 'json_callback',
                propertyName: 'display_name',
                propertyLoc: ['lat', 'lon'],
                autoCollapse: true,
                autoType: false,
                minLength: 2,
                zoom: 12,
                marker: false
            });

            map.addControl(searchControl);

            var searchBox = document.getElementById('searchBox');
            searchBox.addEventListener('input', function() {
                searchControl._input.value = this.value;
                searchControl._handleKeypress({ keyCode: 13 });
            });

            // Handle form submission with AJAX
        });
    </script>
@endsection

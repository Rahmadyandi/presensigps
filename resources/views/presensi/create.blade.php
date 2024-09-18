@extends('layout.presensi')
@section('header')
    <!-- App Header -->
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">E-Presensi</div>
        <div class="right"></div>
    </div>
    <!-- * App Header -->
    <style>
        .webcam-capture,
        .webcam-capture video {
            display: inline-block;
            width: 100% !important;
            margin: auto;
            height: auto !important;
            border-radius: 15px;
        }

        #map {
            height: 200px;
        }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@endsection
@section('content')
    <div class="row" style="margin-top: 70px">
        <div class="col">
            <input type="hidden" id="lokasi">
            <div class="webcam-capture"></div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            @if ($cek > 0)
                <button id="takeabsen" class="btn btn-danger btn-block">
                    <ion-icon name="camera-outline"></ion-icon>
                    Absen pulang
                </button>
            @else
                <button id="takeabsen" class="btn btn-primary btn-block">
                    <ion-icon name="camera-outline"></ion-icon>
                    Absen Masuk
                </button>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col">
            <a id="uploadabsensi" class="btn btn-warning btn-block" href="/presensi/halamanupload" style="margin-top: 10px">
                Upload Absensi
            </a>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col">
            <div id="map"></div>
        </div>
    </div>
@endsection

@push('myscript')
    <script>
        Webcam.set({
            height: 480,
            width: 640,
            image_format: 'jpeg',
            jpeg_quality: 80
        });
        Webcam.attach('.webcam-capture');

        var lokasi = document.getElementById('lokasi');
        var userLatitude, userLongitude;

        function successCallback(position) {
            userLatitude = position.coords.latitude;
            userLongitude = position.coords.longitude;
            lokasi.value = userLatitude + ", " + userLongitude;

            var map = L.map('map').setView([userLatitude, userLongitude], 13);
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            var userMarker = L.marker([userLatitude, userLongitude]).addTo(map);

            var officeLatArr = [0.4776452294945608, 0.4764093827343069, 0.47511431477797156, 0.47346851341025786, 0.47814438338828275,
            0.4792281830885631, 0.47910325725333697, 0.4806540970072093, 0.47336352658920894
            ];
            var officeLonArr = [101.37713247024786, 101.38064632352656, 101.37635415465718, 101.37860152317201, 101.37969108752804,
            101.37857810480386, 101.38096468481164, 101.3768085311893, 101.38135698347864
            ];
            var RadiusArr = [100, 70, 100, 100, 50, 100, 100, 100, 100];  // Example radii in meters

            for (let i = 0; i < officeLatArr.length; i++) {
                var officeMarker = L.marker([officeLatArr[i], officeLonArr[i]]).addTo(map);
                var circle = L.circle([officeLatArr[i], officeLonArr[i]], {
                    color: 'red',
                    fillColor: '#f03',
                    fillOpacity: 0.5,
                    radius: RadiusArr[i]
                }).addTo(map);
            }
        }

        function errorCallback() {
            Swal.fire({
                title: 'Gagal!',
                text: 'Tidak dapat mengambil lokasi Anda',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
        } else {
            Swal.fire({
                title: 'Error!',
                text: 'Geolocation is not supported by your browser',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }

        function calculateDistance(lat1, lon1, lat2, lon2, R) {
            var dLat = (lat2 - lat1) * Math.PI / 180;
            var dLon = (lon2 - lon1) * Math.PI / 180;
            var a = 0.5 - Math.cos(dLat) / 2 +
                Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                (1 - Math.cos(dLon)) / 2;

            return R * 2 * Math.asin(Math.sqrt(a));
        }

        $("#takeabsen").click(function(e) {
            e.preventDefault();
            Webcam.snap(function(uri) {
                var image = uri;
                var lokasi = $('#lokasi').val();
                var lokasiParts = lokasi.split(", ");
                var lat = parseFloat(lokasiParts[0]);
                var lon = parseFloat(lokasiParts[1]);

                var officeLatArr = [0.4776452294945608, 0.4764093827343069, 0.47511431477797156, 0.47346851341025786, 0.47814438338828275,
                0.4792281830885631, 0.47910325725333697, 0.4806540970072093, 0.47336352658920894
                ];
                var officeLonArr = [101.37713247024786, 101.38064632352656, 101.37635415465718, 101.37860152317201, 101.37969108752804,
                101.37857810480386, 101.38096468481164, 101.3768085311893, 101.38135698347864
                ];
                var RadiusArr = [100, 70, 100, 100, 50, 100, 100, 100, 100];
                var keterangan = "Luar UNRI";

                for (let i = 0; i < officeLatArr.length; i++) {
                    var distance = calculateDistance(lat, lon, officeLatArr[i], officeLonArr[i], 6371);

                    if (distance <= RadiusArr[i] / 1000) {
                        keterangan = "Dalam UNRI";
                        break;
                    }
                }

                $.ajax({
                    type: 'POST',
                    url: '/presensi/store',
                    data: {
                        _token: "{{ csrf_token() }}",
                        image: image,
                        lokasi: lokasi,
                        keterangan: keterangan
                    },
                    cache: false,
                    success: function(response) {
                        var status = response.split("|");
                        if (status[0] === "success") {
                            Swal.fire({
                                title: 'Sukses!',
                                text: status[1],
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });
                            setTimeout(function() {
                                location.href = '/dashboard';
                            }, 3000);
                        } else {
                            Swal.fire({
                                title: 'Gagal!',
                                text: 'Silahkan Coba lagi',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    }
                });
            });
        });
    </script>
@endpush

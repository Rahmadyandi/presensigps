@extends('layout.presensi')
@section('header')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">
    <style>
        .datepicker-modal {
            max-height: 430px !important;
        }

        .datepicker-date-display {
            background-color: #0f3a7e !important;
        }
    </style>
    <!-- App Header -->
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Izin Penelitian</div>
        <div class="right"></div>
    </div>
    <!-- * App Header -->
@endsection
@section('content')
    <div class="row" style="margin-top: 70px">
        <div class="col">
            <form method="POST" action="{{ route('post.ajukan_penelitian') }}" enctype="multipart/form-data">
                @csrf
                <!-- File Upload -->
                <div class="custom-file-upload" id="fileUpload"
                    style="position: relative; text-align: center; border: 2px dashed #ccc; padding: 20px; border-radius: 10px; cursor: pointer;">
                    <input type="file" id="fileuploadInput" name="bukti" accept=".png, .jpg, .jpeg"
                        style="opacity: 0; position: absolute; width: 100%; height: 100%; cursor: pointer;">

                    <img id="image-preview" src="#" alt="Preview Image" style="display: none;">

                    <label for="fileuploadInput" style="font-family: Arial, sans-serif; color: #333;">
                        <div class="upload-icon" style="font-size: 40px; color: #666; margin-bottom: 10px;">
                            <ion-icon name="cloud-upload-outline"></ion-icon>
                        </div>
                        <strong style="display: block; font-size: 16px;">Upload Bukti Penelitian</strong>
                    </label>
                </div>
                <!-- Display error message for 'bukti' -->
                @error('bukti')
                    <span class="text-danger" style="font-size: 14px;">{{ $message }}</span>
                @enderror

                <!-- Tanggal Input -->
                <div class="form-group">
                    <input type="date" id="tgl_izin" name="tanggal" class="form-control datepicker"
                        placeholder="Tanggal" required>
                </div>
                <!-- Display error message for 'tanggal' -->
                @error('tanggal')
                    <span class="text-danger" style="font-size: 14px;">{{ $message }}</span>
                @enderror

                <!-- Lokasi Input -->
                <div class="form-group">
                    <input type="text" class="form-control" name="lokasi" placeholder="Lokasi" required>
                </div>
                <!-- Display error message for 'lokasi' -->
                @error('lokasi')
                    <span class="text-danger" style="font-size: 14px;">{{ $message }}</span>
                @enderror

                <!-- Display any general validation errors -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Submit Button -->
                <div class="form-group">
                    <button type="submit" class="btn btn-primary w-100">Kirim</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        var currYear = (new Date()).getFullYear();

        $(document).ready(function() {
            $(".datepicker").datepicker({
                format: "yyyy/mm/dd"
            });

            $("#frmIzin").submit(function() {
                var tgl_izin = $("#tgl_izin").val();
                var status = $("#status").val();
                var keterangan = $("#keterangan").val();
                var bukti_izin = $("#bukti_izin").val();


            });
        });
    </script>
    <script>
        document.getElementById('fileuploadInput').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('image-preview');

            if (file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };

                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
            }
        });
    </script>
@endsection

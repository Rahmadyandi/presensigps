@extends('layout.presensi')
@section('header')
    <!-- App Header -->
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Upload Absensi</div>
        <div class="right"></div>
    </div>
    <!-- * App Header -->
@endsection

@section('content')
    <div class="row" style="margin-top: 5rem">
        <div class="col">
            <form action="/presensi/halamanupload/uploadabsensi" enctype="multipart/form-data" method="POST">
                @csrf
                <div class="custom-file-upload" id="fileUpload">
                    <input type="file" id="fileuploadInput" accept=".png, .jpg, .jpeg">
                    <input type="hidden" name="foto" id="image-base64">
                    <input type="hidden" name="metadata" id="image-metadata">
                    <img id="image-preview" src="#" alt="image-preview" style="display:none;">
                    <label for="fileuploadInput">
                        <span>
                            <strong>
                                <ion-icon name="cloud-upload-outline" role="img" class="md hydrated" aria-label="cloud upload outline"></ion-icon>
                                <i>Upload Bukti Kehadiran</i>
                            </strong>
                        </span>
                    </label>
                </div>
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <button id="uploadabsen" type="submit" class="btn btn-primary btn-block">
                            <ion-icon name="image-outline"></ion-icon>
                            Upload
                        </button>
                    </div>
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </form>            
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/exif-js"></script>
    <script>
        function convertDMSToDecimal(dmsArray, ref) {
            const degrees = dmsArray[0].numerator / dmsArray[0].denominator;
            const minutes = dmsArray[1].numerator / dmsArray[1].denominator / 60;
            const seconds = dmsArray[2].numerator / dmsArray[2].denominator / 3600;
    
            let decimal = degrees + minutes + seconds;
    
            if (ref === 'S' || ref === 'W') {
                decimal = -decimal;
            }
    
            return decimal;
        }
    
        document.getElementById('fileuploadInput').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const base64String = e.target.result.split(',')[1];
                    document.getElementById('image-base64').value = e.target.result;
    
                    const preview = document.getElementById('image-preview');
                    preview.src = e.target.result;
                    preview.style.display = 'block';
    
                    EXIF.getData(file, function() {
                        const allMetaData = EXIF.getAllTags(this);
                        console.log('EXIF Metadata:', allMetaData); // Debugging line
    
                        const gpsLatitudeRef = allMetaData.GPSLatitudeRef;
                        const gpsLongitudeRef = allMetaData.GPSLongitudeRef;
                        const gpsLatitude = allMetaData.GPSLatitude ? convertDMSToDecimal(allMetaData.GPSLatitude, gpsLatitudeRef) : null;
                        const gpsLongitude = allMetaData.GPSLongitude ? convertDMSToDecimal(allMetaData.GPSLongitude, gpsLongitudeRef) : null;
    
                        if (gpsLatitude !== null && gpsLongitude !== null) {
                            console.log('GPS Latitude:', gpsLatitude); // Debugging line
                            console.log('GPS Longitude:', gpsLongitude); // Debugging line
                        } else {
                            console.error('GPS data not available'); // Debugging line
                        }
    
                        const metadata = {
                            fileName: file.name,
                            fileSize: file.size,
                            fileType: file.type,
                            lastModified: file.lastModified,
                            lastModifiedDate: file.lastModifiedDate,
                            dateTimeOriginal: allMetaData.DateTimeOriginal,
                            gpsLatitude: gpsLatitude,
                            gpsLongitude: gpsLongitude
                        };
    
                        console.log('Final Metadata:', metadata); // Debugging line
    
                        document.getElementById('image-metadata').value = JSON.stringify(metadata);
                    });
                };
                reader.readAsDataURL(file);
            }
        });
    </script>    
@endsection

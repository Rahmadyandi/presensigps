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
    <div class="pageTitle">Pengajuan Izin</div>
    <div class="right"></div>
</div>
<!-- * App Header -->
@endsection
@section('content')
    <div class="row" style="margin-top: 70px">
        <div class="col">
            <form method="POST" action="/presensi/storeizin" id="frmIzin" enctype="multipart/form-data"> 
                @csrf
                <div class="form-group">
                    <input type="text" id="tgl_izin" name="tgl_izin" class="form-control datepicker" placeholder="Tanggal" required>
                </div>
                <div class="form-group">
                    <select name="status" id="status" class="form-control" required>
                        <option value="" >Izin / Sakit</option>
                        <option value="i">Izin</option>
                        <option value="s">Sakit</option>
                    </select>
                </div>
                <div class="form-group">
                    <textarea name="keterangan" id="keterangan" cols="30" rows="5" class="form-control" placeholder="Keterangan" required></textarea>
                </div>
                <div class="custom-file-upload" id="fileUpload">
                    <input type="file" id="fileuploadInput" name="bukti_izin" accept=".png, .jpg, .jpeg">
                    <img id="image-preview" src="#" alt="image-preview" style="display:none;">
                    <label for="fileuploadInput">
                        <span>
                            <strong>
                                <ion-icon name="cloud-upload-outline" role="img" class="md hydrated" aria-label="cloud upload outline" required></ion-icon>
                                <i>Upload Bukti Izin</i>
                            </strong>
                        </span>
                    </label>
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
                <div class="form-group">
                    <button class="btn btn-primary w-100">Kirim</button>
                </div>
            </form>
        </div>
    </div>
    
@endsection
@push('myscript')
<script>
    var currYear = (new Date()).getFullYear();

    $(document).ready(function() {
        $(".datepicker").datepicker({
            format: "yyyy/mm/dd"    
    });
    
    $("#frmIzin").submit(function(){
        var tgl_izin = $("#tgl_izin").val();
        var status = $("#status").val();
        var keterangan = $("#keterangan").val();
        var bukti_izin = $("#bukti_izin").val();

        
    });
    });
</script>
@endpush
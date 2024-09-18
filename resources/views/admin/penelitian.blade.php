@include('admin.layouts.head')
@include('admin.layouts.aside')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $title }}</h1>
                </div><!-- /.col -->
            </div>
        </div>
    </div>
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row d-flex justify-content-center">
                <!-- /.col-md-6 -->
                <div class="col-lg-10">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Kelola Data {{ $title }}</h3>
                        </div>
                        <div class="card-body">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead class="text-center text-capitalize">
                                    <tr>
                                        @foreach (['No', 'nip', 'nama', 'tanggal', 'bukti penelitian', 'lokasi', 'veritifikasi', 'opsi'] as $item)
                                            <th>{{ $item }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($penelitian as $i => $item)
                                        <tr>

                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center opacity-50 text-capitalize text-center">Tidak ada pengajuan penelitian oleh dosen</td>
                                        </tr>
                                    @endforelse
                                </tbody>

                                    @include('admin.layouts.footer')

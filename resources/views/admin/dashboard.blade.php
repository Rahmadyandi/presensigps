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
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-lg-12 col-6">
                                    <!-- small box -->
                                    <div class="small-box bg-secondary">
                                        <div class="inner">
                                            <h3>{{ date('d-m-Y') }}</h3>

                                            <p>Tanggal</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-6">
                                    <!-- small box -->
                                    <div class="small-box bg-secondary">
                                        <div class="inner">
                                            <h3>{{ $jumlahDosen }}</h3>

                                            <p>Jumlah Dosen</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-users"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-6">
                                    <!-- small box -->
                                    <div class="small-box bg-secondary">
                                        <div class="inner">
                                            <h3>{{ $MenungguPersetujuan }}</h3>

                                            <p>Menunggu Persetujuan</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-file"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-6">
                                    <!-- small box -->
                                    <div class="small-box bg-secondary">
                                        <div class="inner">
                                            <h3>{{ $IzinDiterima }}</h3>

                                            <p>Jumlah Izin Diterima</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-file"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-6">
                                    <!-- small box -->
                                    <div class="small-box bg-secondary">
                                        <div class="inner">
                                            <h3>{{ $IzinDitolak }}</h3>

                                            <p>Jumlah Izin Ditolak</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-file"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.layouts.footer')

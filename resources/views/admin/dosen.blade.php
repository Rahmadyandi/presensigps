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
                                <thead class="text-center">
                                    <tr>
                                        <th>No</th>
                                        <th>Nomor Induk Pegawai (NIP)</th>
                                        <th>Nama Lengkap</th>
                                        <th>Jabatan</th>
                                        <th>No Handphone</th>
                                        <th>Persetujuan</th>
                                        <th>Foto</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    @if ($dosens->isEmpty())
                                        <tr>
                                            <td colspan="7" class="text-center label label-danger">Data dosen tidak
                                                ada</td>
                                        </tr>
                                    @else
                                        @foreach ($dosens as $key => $d)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $d->nip }}</td>
                                                <td>{{ $d->nama_lengkap }}</td>
                                                <td>{{ $d->jabatan }}</td>
                                                <td>{{ $d->no_hp }}</td>
                                                <td>
                                                    @if ($d->persetujuan === 'menunggu persetujuan')
                                                        Menunggu Persetujuan
                                                    @elseif($d->persetujuan === 'ditolak')
                                                        <i class="fas fa-times-circle text-danger"></i>
                                                    @elseif($d->persetujuan === 'diterima')
                                                        <i class="fas fa-check-circle text-success"></i>
                                                    @endif
                                                </td>
                                                <td><button type="button" class="btn btn-primary btn-sm"
                                                        data-toggle="modal"
                                                        data-target="#modalBukti-{{ $d->id }}"> Lihat
                                                        Gambar</button>
                                                    <!-- Modal -->
                                                    <div class="modal fade" id="modalBukti-{{ $d->id }}"
                                                        tabindex="-1" role="dialog"
                                                        aria-labelledby="modalPendudukLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="modalPendudukLabel">
                                                                        Gambar
                                                                        {{ $title . ' ' . ucfirst($d->nama_lengkap) }}
                                                                    </h5>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">×</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <img src="{{ asset('storage/uploads/dosen/' . $d->foto) }}"
                                                                        class="img-fluid" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if ($d->persetujuan === 'menunggu persetujuan')
                                                        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-terima-{{ $d->id }}">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-tolak-{{ $d->id }}">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    @else
                                                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-hapus-{{ $d->id }}">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    @endif
                                                </td>
                                                
                                                <!-- Modal Terima -->
                                                <div class="modal fade" id="modal-terima-{{ $d->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-terimaLabel"
                                                     aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="modal-terimaLabel">Konfirmasi Terima Data</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">×</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Apakah Anda yakin ingin menerima {{ $title }} <b>{{ $d->nama_lengkap }}</b>?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                                <form action="{{ route('terima_dosen', ['id' => $d->id]) }}" method="POST">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <button type="submit" class="btn btn-success">Terima</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Modal Tolak -->
                                                <div class="modal fade" id="modal-tolak-{{ $d->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-tolakLabel"
                                                     aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="modal-tolakLabel">Konfirmasi Tolak Data</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">×</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Apakah Anda yakin ingin menolak {{ $title }} <b>{{ $d->nama_lengkap }}</b>?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                                <form action="{{ route('tolak_dosen', ['id' => $d->id]) }}" method="POST">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <button type="submit" class="btn btn-danger">Tolak</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Modal Hapus -->
                                                <div class="modal fade" id="modal-hapus-{{ $d->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-hapusLabel"
                                                     aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="modal-hapusLabel">Konfirmasi Hapus Data</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">×</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Apakah Anda yakin ingin menghapus {{ $title }} <b>{{ $d->nama_lengkap }}</b>?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                                <form action="{{ route('hapus_dosen', ['id' => $d->id]) }}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>                                                
                                            </tr>
                                        @endforeach
                                    @endif

                                    @include('admin.layouts.footer')

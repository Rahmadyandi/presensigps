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
                                        @foreach (['No', 'nip', 'nama', 'tanggal', 'bukti penelitian', 'lokasi', 'verifikasi', 'opsi'] as $item)
                                            <th>{{ $item }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($penelitian as $i => $item)
                                        <tr>
                                            <td class="text-capitalize text-center">{{ $i + 1 }}</td>
                                            <td class="text-capitalize text-center">{{ $item->dosen->nip }}</td>
                                            <td class="text-capitalize text-center">{{ $item->dosen->nama_lengkap }}
                                            </td>
                                            <td class="text-capitalize text-center">{{ $item->tanggal }}</td>
                                            <td class="text-capitalize text-center"><button type="button"
                                                    class="btn btn-primary btn-sm" data-toggle="modal"
                                                    data-target="#modalBukti-{{ $item->id_penelitian }}">Lihat
                                                    Gambar</button>
                                                <!-- Modal -->
                                                <div class="modal fade" id="modalBukti-{{ $item->id_penelitian }}"
                                                    tabindex="-1" role="dialog" aria-labelledby="modalPendudukLabel"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="modalPendudukLabel">
                                                                    Gambar
                                                                    {{ $title . ' ' . ucfirst($item->dosen->nama_lengkap) }}
                                                                </h5>
                                                                <button type="button" class="close"
                                                                    data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">×</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <img src="{{ asset('storage/' . $item->bukti) }}"
                                                                    class="img-fluid" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-capitalize text-center">{{ $item->lokasi }}</td>
                                            @if ($item->verifikasi === 'menunggu')
                                                <td class="text-capitalize text-center">
                                                    <span
                                                        class="badge badge-warning">{{ ucfirst($item->verifikasi) }}</span>
                                                </td>
                                            @elseif ($item->verifikasi === 'diterima')
                                                <td class="text-capitalize text-center">
                                                    <span
                                                        class="badge badge-success">{{ ucfirst($item->verifikasi) }}</span>
                                                </td>
                                            @elseif ($item->verifikasi === 'ditolak')
                                                <td class="text-capitalize text-center">
                                                    <span
                                                        class="badge badge-danger">{{ ucfirst($item->verifikasi) }}</span>
                                                </td>
                                            @endif
                                            @if ($item->verifikasi === 'diterima')
                                                <td class="text-capitalize text-center">
                                                    <i class="fas fa-check-circle text-success"></i>
                                                </td>
                                            @elseif ($item->verifikasi === 'ditolak')
                                                <td class="text-capitalize text-center">
                                                    <i class="fas fa-times-circle text-danger"></i>
                                                </td>
                                            @elseif ($item->verifikasi === 'menunggu')
                                                <td class="text-capitalize text-center">
                                                    <button class="btn btn-success btn-sm" data-toggle="modal"
                                                        data-target="#Modal-terima-{{ $item->id_penelitian }}">Terima</button>

                                                    <div class="modal fade"
                                                        id="Modal-terima-{{ $item->id_penelitian }}" tabindex="-1"
                                                        aria-labelledby="ModalTerimaLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="ModalTerimaLabel">
                                                                        Terima Izin Penelitian</h5>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    Apakah Anda yakin ingin menerima izin penelitian
                                                                    dari
                                                                    {{ $item->dosen->nama_lengkap }}
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">Tutup</button>
                                                                    <form
                                                                        action="{{ route('terima_penelitian', ['id_penelitian' => $item->id_penelitian]) }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        @method('PUT')
                                                                        <button type="submit"
                                                                            class="btn btn-success">Ya</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    |
                                                    <button class="btn btn-danger btn-sm" data-toggle="modal"
                                                        data-target="#Modal-tolak-{{ $item->id_penelitian }}">Tolak</button>

                                                    <div class="modal fade" id="Modal-tolak-{{ $item->id_penelitian }}"
                                                        tabindex="-1" aria-labelledby="ModalTolakLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="ModalTolakLabel">
                                                                        Tolak Izin penelitian</h5>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    Apakah Anda yakin ingin menolak izin dari
                                                                    {{ $item->dosen->nama_lengkap }}
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">Tutup</button>
                                                                    <form
                                                                        action="{{ route('tolak_penelitian', ['id_penelitian' => $item->id_penelitian]) }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        @method('PUT')
                                                                        <button type="submit"
                                                                            class="btn btn-danger">Ya</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            @endif
                                        </tr>
                                    @empty
                                        <tr class="opacity-50">
                                            <td colspan="8" class="text-center text-capitalize text-center">Tidak
                                                ada
                                                pengajuan penelitian oleh dosen</td>
                                        </tr>
                                    @endforelse
                                </tbody>

                                @include('admin.layouts.footer')

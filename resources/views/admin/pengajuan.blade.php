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
                            <h3 class="card-title">{{ $title }}</h3>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ url()->current() }}">
                                <div class="form-group">
                                    <label for="month">Pilih Bulan:</label>
                                    <select name="month" id="month" class="form-control"
                                        onchange="this.form.submit()">
                                        @foreach ($months as $month)
                                            <option value="{{ $month->month }}"
                                                {{ $month->month == $selectedMonth ? 'selected' : '' }}>
                                                {{ \Carbon\Carbon::parse($month->month)->format('F Y') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>
                            
                            <table id="example2" class="table table-bordered table-hover">
                                <thead class="text-center">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Tanggal Izin</th>
                                        <th>Status</th>
                                        <th>Keterangan</th>
                                        <th>Bukti Izin</th>
                                        <th>Persetujuan</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($pengajuan->isEmpty())
                                        <tr>
                                            <td colspan="9" class="text-center label label-danger">History Pengajuan
                                                tidak ada</td>
                                        </tr>
                                    @else
                                        @foreach ($pengajuan as $key => $p)
                                            <tr class="text-center">
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $p->nama_lengkap }}</td>
                                                <td>{{ $p->tgl_izin }}</td>
                                                <td>
                                                    @if ($p->status == 's')
                                                        Sakit
                                                    @elseif($p->status == 'i')
                                                        Izin
                                                    @else
                                                        {{ $p->status }}
                                                    @endif
                                                </td>
                                                <td>{{ $p->keterangan }}</td>
                                                <td>
                                                    @if ($p->bukti_izin === null)
                                                        -
                                                    @else
                                                        <button type="button" class="btn btn-primary btn-sm"
                                                            data-toggle="modal"
                                                            data-target="#modalpulang-{{ $p->id }}">Lihat
                                                            Gambar</button>
                                                        <!-- Modal -->
                                                        <div class="modal fade" id="modalpulang-{{ $p->id }}"
                                                            tabindex="-1" role="dialog"
                                                            aria-labelledby="modalPendudukLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="modalPendudukLabel">
                                                                            Gambar
                                                                            {{ $title . ' ' . ucfirst($p->nip) }}</h5>
                                                                        <button type="button" class="close"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">×</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <img src="{{ asset('storage/bukti_izin/' . $p->bukti_izin) }}"
                                                                            class="img-fluid" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($p->status_approved === '0')
                                                        Pending
                                                    @elseif($p->status_approved === '1')
                                                        Diterima
                                                    @elseif($p->status_approved === '2')
                                                        Ditolak
                                                    @else
                                                        {{ $p->status_approved }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($p->status_approved === '1')
                                                        <i class="fas fa-check-circle text-success"></i>
                                                    @elseif ($p->status_approved === '0')
                                                        <button class="btn btn-success btn-sm" data-toggle="modal"
                                                            data-target="#Modal-terima-{{ $p->id }}">Terima</button>

                                                        <div class="modal fade" id="Modal-terima-{{ $p->id }}"
                                                            tabindex="-1" aria-labelledby="ModalTerimaLabel"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="ModalTerimaLabel">
                                                                            Terima Izin</h5>
                                                                        <button type="button" class="close"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        Apakah Anda yakin ingin menerima izin dari
                                                                        {{ $p->nama_lengkap }}
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-dismiss="modal">Tutup</button>
                                                                        <form
                                                                            action="{{ route('terima_izin', ['id' => $p->id]) }}"
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
                                                            data-target="#Modal-tolak-{{ $p->id }}">Tolak</button>

                                                        <div class="modal fade" id="Modal-tolak-{{ $p->id }}"
                                                            tabindex="-1" aria-labelledby="ModalTolakLabel"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="ModalTolakLabel">
                                                                            Tolak Izin</h5>
                                                                        <button type="button" class="close"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        Apakah Anda yakin ingin menolak izin dari
                                                                        {{ $p->nama_lengkap }}
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-dismiss="modal">Tutup</button>
                                                                        <form
                                                                            action="{{ route('tolak_izin', ['id' => $p->id]) }}"
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
                                                    @elseif($p->status_approved === '2')
                                                        <i class="fas fa-times-circle text-danger"></i>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@include('admin.layouts.footer')

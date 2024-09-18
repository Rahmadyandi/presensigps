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
                            <h3 class="card-title">History {{ $title }}</h3>
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
                                        {{-- <th>Nomor Induk Pegawai (NIP)</th> --}}
                                        <th>Tanggal Presensi</th>
                                        <th>Jam Masuk</th>
                                        <th>Foto Masuk</th>
                                        <th>Lokasi Masuk</th>
                                        <th>Keterangan Masuk</th>
                                        <th>Jam Pulang</th>
                                        <th>Foto Pulang</th>
                                        <th>Lokasi Pulang</th>
                                        <th>Keterangan Pulang</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    @if ($presensi->isEmpty())
                                        <tr>
                                            <td colspan="9" class="text-center label label-danger">History Presensi
                                                tidak ada</td>
                                        </tr>
                                    @else
                                        @foreach ($presensi as $key => $p)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $p->nama_lengkap }}</td>
                                                <td>{{ $p->tgl_presensi }}</td>
                                                <td>{{ $p->jam_in ?? '-' }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-primary btn-sm"
                                                        data-toggle="modal"
                                                        data-target="#modalBukti-{{ $p->id }}">Lihat
                                                        Gambar</button>
                                                    <!-- Modal -->
                                                    <div class="modal fade" id="modalBukti-{{ $p->id }}"
                                                        tabindex="-1" role="dialog"
                                                        aria-labelledby="modalPendudukLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="modalPendudukLabel">
                                                                        Gambar {{ $title . ' ' . ucfirst($p->nip) }}
                                                                    </h5>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">×</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    @if (Str::startsWith($p->foto_in, 'uploads/absensi/'))
                                                                        <img src="{{ asset('storage/' . $p->foto_in) }}"
                                                                            class="img-fluid" />
                                                                    @else
                                                                        <img src="{{ asset('storage/uploads/absensi/' . $p->foto_in) }}"
                                                                            class="img-fluid" />
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a type="button" class="btn btn-primary btn-sm" href="#"
                                                        onclick="openMapModal('{{ $p->lokasi_in }}', 'modallokasi-{{ $p->id }}')">Lihat
                                                        Lokasi</a>
                                                </td>
                                                <td>
                                                    {{ $p->keterangan_in ?? '-' }}
                                                </td>

                                                <td>{{ $p->jam_out ?? '-' }}</td>
                                                <td>
                                                    @if ($p->foto_out === null)
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
                                                                        @if (Str::startsWith($p->foto_in, 'uploads/absensi/'))
                                                                            <img src="{{ asset('storage/' . $p->foto_out) }}"
                                                                                class="img-fluid" />
                                                                        @else
                                                                            <img src="{{ asset('storage/uploads/absensi/' . $p->foto_out) }}"
                                                                                class="img-fluid" />
                                                                        @endif
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($p->lokasi_out === null)
                                                        -
                                                    @else
                                                        <a type="button" class="btn btn-primary btn-sm" href="#"
                                                            onclick="openMapModal('{{ $p->lokasi_out }}', 'modallokasi-out-{{ $p->id }}')">Lihat
                                                            Lokasi</a>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $p->keterangan_out ?? '-' }}
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        data-toggle="modal"
                                                        data-target="#modal-hapus-{{ $p->id }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                    <div class="modal fade" id="modal-hapus-{{ $p->id }}"
                                                        tabindex="-1" role="dialog" aria-labelledby="modal-hapusLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="modal-hapusLabel">
                                                                        Konfirmasi Hapus Data</h5>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">×</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    Apakah Anda yakin ingin menghapus
                                                                    {{ $title }}?
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">Tutup</button>
                                                                    <form
                                                                        action="{{ route('hapus_absensi', ['id' => $p->id]) }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit"
                                                                            class="btn btn-danger">Hapus</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
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

<script>
    function openMapModal(coordinates, modalId) {
        var [latitude, longitude] = coordinates.split(',');

        var existingModal = document.getElementById(modalId);
        if (existingModal) {
            existingModal.parentNode.removeChild(existingModal);
        }

        var modal = document.createElement('div');
        modal.classList.add('modal', 'fade');
        modal.id = modalId;
        modal.tabIndex = "-1";
        modal.role = "dialog";
        modal.setAttribute('aria-labelledby', 'modalPendudukLabel');
        modal.setAttribute('aria-hidden', 'true');
        modal.innerHTML = `
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Peta Lokasi</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="map-${modalId}" style="height: 400px;"></div>
                    </div>
                </div>
            </div>
        `;

        document.body.appendChild(modal);

        $('#' + modalId).on('shown.bs.modal', function() {
            var map = L.map(`map-${modalId}`).setView([latitude, longitude], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            L.marker([latitude, longitude]).addTo(map).openPopup();
        });

        $('#' + modalId).modal('show');
    }
</script>

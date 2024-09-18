@extends('layout.presensi')
@section('header')
    <!-- App Header -->
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Izin / Sakit</div>
        <div class="right"></div>
    </div>
    <!-- * App Header -->
@endsection
@section('content')
    <div class="row" style="margin-top: 5rem">
        <div class="col">
            @php
                $messagesuccess = Session::get('success');
                $messageerror = Session::get('error');
            @endphp
            @if (Session::get('success'))
                <div class="alert alert-success">
                    {{ $messagesuccess }}
                </div>
            @endif
            @if (Session::get('error'))
                <div class="alert alert-danger">
                    {{ $messageerror }}
                </div>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col">
            @foreach ($dataizin as $d)
                <ul class="listview image-listview">
                    <li>
                        <div class="item">
                            <div class="in">
                                <div>
                                    <b>{{ date('d-m-Y', strtotime($d->tgl_izin)) }}
                                        ({{ $d->status == 's' ? 'Sakit' : 'Izin' }})
                                    </b><br>
                                    <small class="text-mutes"> {{ $d->keterangan }}</small>
                                </div>
                                @if ($d->status_approved == 0)
                                    <span class="badge bg-warning">Menunggu Persetujuan</span>
                                @elseif($d->status_approved == 1)
                                    <span class="badge bg-success">Disetujui</span>
                                @elseif($d->status_approved == 2)
                                    <span class="badge bg-danger">Ditolak</span>
                                @endif
                            </div>
                        </div>
                    </li>
                </ul>
            @endforeach
        </div>
    </div>
    <div class="fab-button bottom-right" style="margin-bottom: 70px">
        <a href="#" class="fab" id="fabDropdownButton">
            <ion-icon name="add-circle-outline"></ion-icon>
        </a>
        <div id="fabDropdownMenu" class="fab-dropdown"
            style="display: none; position: absolute; bottom: 60px; right: 0; background-color: white; border-radius: 8px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); z-index: 10;">
            <ul style="list-style: none; padding: 10px 0; margin: 0;">
                <li style="padding: 8px 16px;">
                    <a href="/presensi/pengajuanizin" class="dropdown-item">
                        <ion-icon name="document-outline"></ion-icon> Pengajuan Izin
                    </a>
                </li>
                <li style="padding: 8px 16px;">
                    <a href="{{ route('ajukan_penelitian') }}" class="dropdown-item">
                        <ion-icon name="checkmark-outline"></ion-icon> Pengajuan Penelitian
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <script>
        document.getElementById('fabDropdownButton').addEventListener('click', function(e) {
            e.preventDefault();
            var dropdownMenu = document.getElementById('fabDropdownMenu');
            dropdownMenu.style.display = (dropdownMenu.style.display === 'none' || dropdownMenu.style.display ===
                '') ? 'block' : 'none';
        });

        document.addEventListener('click', function(e) {
            var dropdownMenu = document.getElementById('fabDropdownMenu');
            if (!document.getElementById('fabDropdownButton').contains(e.target) && !dropdownMenu.contains(e
                    .target)) {
                dropdownMenu.style.display = 'none';
            }
        });
    </script>
@endsection

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="{{ route('dashboard_admin') }}" class="brand-link d-flex align-items-center">
            <img src="{{ asset('storage/uploads/dosen/' . $dosen->foto) }}" alt="{{ $dosen->nama_lengkap }}"
                 class="brand-image img-circle elevation-3" style="opacity: .8; width: 40px; height: 40px;">
            <div class="ml-2">
                <span class="d-block font-weight-light">{{ $dosen->nama_lengkap }}</span>
                <small class="text-muted">{{ $dosen->jabatan }}</small>
            </div>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">

            <!-- Sidebar Menu -->
            <nav class="mt-4">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                    data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class
                 with font-awesome or any other icon font library -->
                    <li class="nav-header">Home</li>
                    <li class="nav-item">
                        <a href="{{ route('dashboard_admin') }}"
                            class="nav-link {{ $active === 'dashboard' ? ' active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Dashboard
                            </p>
                        </a>
                    </li>
                    <li class="nav-header">Laporan</li>
                    <li class="nav-item">
                        <a href="{{ route('data_dosen') }}"
                            class="nav-link {{ $active === 'dosen' ? ' active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Kelola Data Dosen
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('data_pengajuan') }}"
                            class="nav-link {{ $active === 'pengajuan' ? ' active' : '' }}">
                            <i class="nav-icon fas fa-file"></i>
                            <p>
                                Kelola Data Pengajuan
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('penelitian') }}"
                            class="nav-link {{ $active === 'penelitian' ? ' active' : '' }}">
                            <i class="nav-icon fas fa-file"></i>
                            <p>
                                Kelola Data Penelitian
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('data_presensi') }}"
                            class="nav-link {{ $active === 'absensi' ? ' active' : '' }}">
                            <i class="nav-icon fas fa-file"></i>
                            <p>
                                Kelola Data Absensi
                            </p>
                        </a>
                    </li>
                    
                    <li class="nav-header">Navigasi</li>
                    <li class="nav-item">
                        <form id="logout-form" action="/proseslogout" method="POST">
                            @csrf
                            <a href="#" class="nav-link" onclick="event.preventDefault(); confirmLogout();">
                                <i class="nav-icon fas fa-power-off"></i>
                                <p>Log out</p>
                            </a>
                        </form>
                    </li>

                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

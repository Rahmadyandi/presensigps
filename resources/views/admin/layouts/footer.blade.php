    <!-- Main Footer -->
    <footer class="main-footer">
        <strong>Copyright &copy; {{ date('Y') }} <a href="#" target="_blank">{{ config('app.name')}}</a>.</strong>
        All rights reserved.
    </footer>
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->
    <!-- jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap -->
    <script src="{{ asset('temp/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ asset('temp/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('temp/dist/js/adminlte.js') }}"></script>

    <!-- PAGE PLUGINS -->
    <!-- jQuery Mapael -->
    <script src="{{ asset('temp/plugins/jquery-mousewheel/jquery.mousewheel.js') }}"></script>
    <script src="{{ asset('temp/plugins/raphael/raphael.min.js') }}"></script>
    <script src="{{ asset('temp/plugins/jquery-mapael/jquery.mapael.min.js') }}"></script>
    <script src="{{ asset('temp/plugins/jquery-mapael/maps/usa_states.min.js') }}"></script>

    <!-- DataTables  & Plugins -->
    <script src="{{ asset('temp/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('temp/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('temp/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('temp/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('temp/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('temp/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('temp/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('temp/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('temp/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('temp/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('temp/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('temp/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <!-- ChartJS -->
    <script src="{{ asset('temp/plugins/chart.js/Chart.min.js') }}"></script>
    <script>
        $(function() {
            var role = "{{ $role }}";
        
            var example2Buttons = [];
            if (role !== 'customer') {
                example2Buttons.push({
                    extend: 'pdf',
                    exportOptions: {
                        columns: ':not(.no-export)'
                    }
                });
            }

            $('#example2').DataTable({
                "paging": false,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "buttons": false,
            }).buttons().container().addClass('mb-3').appendTo('#example2_wrapper .col-md-6:eq(0)');
        });
    </script>     

    <script>
        function confirmLogout() {
            Swal.fire({
                title: 'Apa anda Yakin?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        }
    </script>
    </body>

    </html>

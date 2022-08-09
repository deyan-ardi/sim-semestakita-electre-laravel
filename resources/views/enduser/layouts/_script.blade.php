<script src="{{ asset('assets/enduser/js/jquery-3.5.1.min.js') }}"></script>

<script src="{{ asset('assets/enduser/js/popper.min.js') }}"></script>
<script src="{{ asset('assets/enduser/js/bootstrap.min.js') }}"></script>

<script src="{{ asset('assets/enduser/js/feather.min.js') }}"></script>

<script src="{{ asset('assets/enduser/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>

<script src="{{ asset('assets/enduser/plugins/select2/js/select2.min.js') }}"></script>

<script src="{{ asset('assets/enduser/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/enduser/plugins/datatables/datatables.min.js') }}"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

<script src="{{ asset('assets/enduser/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('assets/enduser/js/bootstrap-datetimepicker.min.js') }}"></script>

<script src="{{ asset('assets/enduser/plugins/apexchart/apexcharts.min.js') }}"></script>
{{-- <script src="{{ asset('assets/enduser/plugins/apexchart/chart-data.js') }}"></script> --}}

<script src="{{ asset('assets/enduser/js/script.js') }}"></script>

<script src="{{ asset('assets/enduser/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/sweetalert2.js') }}"></script>
<script>
    console.clear();
    var info = `
    Semesta Kita ~ Yayasan Taksu Tridatu
    ===============================
    Product Name: SEMESTA KITA
    Develop By  : PT Ganadev Multi Solusi
    Powered By  : Undiksha, Sistem Informasi Undiksha
    Develop For : Yayasan Taksu Tridatu
    `;
    console.log(info);
    $(document).ready(function() {
        const success = $(".berhasil").data("berhasil");
        if (success) {
            Swal.fire({
                position: "center",
                icon: "success",
                title: 'Berhasil',
                text: success,
                showConfirmButton: true,
                confirmButtonText: 'Tutup'
            });
        }
    });
    $(document).ready(function() {
        const gagal = $(".gagal").data("gagal");
        console.log(gagal);
        if (gagal) {
            Swal.fire({
                position: "center",
                icon: "error",
                title: 'Gagal',
                text: "Opss.." + gagal,
                showConfirmButton: true,
                confirmButtonText: 'Tutup'
            });
        }
    });
</script>
@yield('footer')

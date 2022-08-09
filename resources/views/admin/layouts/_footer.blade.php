{{-- Required --}}
<script src="{{ asset('assets/admin/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/modernizr.js') }}"></script>
<script src="{{ asset('assets/admin/js/moment.js') }}"></script>

<!-- Megamenu JS -->
<script src="{{ asset('assets/admin/vendor/megamenu/js/megamenu.js') }}"></script>
<script src="{{ asset('assets/admin/vendor/megamenu/js/custom.js') }}"></script>

<!-- Slimscroll JS -->
<script src="{{ asset('assets/admin/vendor/slimscroll/slimscroll.min.js') }}"></script>
<script src="{{ asset('assets/admin/vendor/slimscroll/custom-scrollbar.js') }}"></script>

<!-- Search Filter JS -->
<script src="{{ asset('assets/admin/vendor/search-filter/search-filter.js') }}"></script>
<script src="{{ asset('assets/admin/vendor/search-filter/custom-search-filter.js') }}"></script>

<!-- Circleful Charts -->
<script src="{{ asset('assets/admin/vendor/circliful/circliful.min.js') }}"></script>
<script src="{{ asset('assets/admin/vendor/circliful/circliful.custom.js') }}"></script>

<!-- Main Js Required -->
<script src="{{ asset('assets/admin/js/main.js') }}"></script>

<!-- Data Tables -->
<script src="{{ asset('assets/admin/vendor/datatables/dataTables.min.js') }}"></script>
<script src="{{ asset('assets/admin/vendor/datatables/dataTables.bootstrap.min.js') }}"></script>

<!-- Custom Data tables -->
<script src="{{ asset('assets/admin/vendor/datatables/custom/custom-datatables.js') }}"></script>
<script src="{{ asset('assets/admin/vendor/datatables/custom/fixedHeader.js') }}"></script>

{{-- Sweetalert --}}
<script src="{{ asset('assets/admin/js/sweetalert2.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js"
    integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
<script>
    function formatRupiah(angka) {
        var rupiah = angka.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
        return 'Rp. ' + rupiah;
    }
</script>
{{-- Other JS --}}
@yield('footer')
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
                title: "Sukses",
                text: success,
                showConfirmButton: true,
                confirmButtonText: "Tutup"
            });
        }
    });
    $(document).ready(function() {
        const gagal = $(".gagal").data("gagal");
        if (gagal) {
            Swal.fire({
                position: "center",
                icon: "error",
                title: "Gagal",
                text: gagal,
                showConfirmButton: true,
                confirmButtonText: "Tutup"
            });
        }
    });
    $(".delete-button").on("click", function(e) {
        e.preventDefault();
        var self = $(this);
        var nama = $(this).attr("data-nama");
        var formId = $(this).attr("data-formid");
        Swal.fire({
                title: 'Apakah Anda Yakin Ingin Menghapus Data ' + nama + ' ?',
                text: 'Semua file yang berkaitan dengan data ini akan terhapus secara permanen',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yakin'
            })
            .then((result) => {
                if (result.isConfirmed) {
                    $("#delete-" + formId).submit();
                }
            });
    });

    $(".confirm-cancel").on("click", function(e) {
        var href = $(this).attr("data-redirect");
        Swal.fire({
                title: 'Apakah Anda Yakin Ingin Membatalkan?',
                text: 'Data di menu kasir ini akan hilang sepenuhnya',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yakin'
            })
            .then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            });
    });

    const deleteButton = (nama, formId) => {
        Swal.fire({
                title: 'Apakah Anda Yakin Ingin Menghapus ' + nama + ' ?',
                text: 'Semua data yang terkait akan terhapus',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            })
            .then((result) => {
                if (result.isConfirmed) {
                    $("#delete-" + formId).submit();
                }
            });
    }

    $(".confirm-save").on("click", function(e) {
        e.preventDefault();
        var formId = $(this).attr("data-formid");
        Swal.fire({
                title: 'Apakah Anda Yakin Ingin Menyimpan?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yakin'
            })
            .then((result) => {
                if (result.isConfirmed) {
                    $("#save-" + formId).submit();
                }
            });
    });
</script>

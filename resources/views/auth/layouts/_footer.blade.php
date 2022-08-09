<script src="{{ asset('assets/enduser/js/jquery-3.5.1.min.js') }}"></script>
<script src="{{ asset('assets/enduser/js/popper.min.js') }}"></script>
<script src="{{ asset('assets/enduser/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/enduser/js/feather.min.js') }}"></script>
<script src="{{ asset('assets/enduser/js/script.js') }}"></script>
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
                title: "Berhasil",
                text: success,
                showConfirmButton: true,
                confirmButtonText: "Tutup"
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
                title: "Gagal",
                text: gagal,
                showConfirmButton: true,
                confirmButtonText: "Tutup"
            });
        }
    });
</script>
@yield('javascriptInclude')

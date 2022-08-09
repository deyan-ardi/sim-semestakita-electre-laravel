<link rel="stylesheet" href="{{ asset('assets/admin/vendor/datatables/dataTables.bs4.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/vendor/datatables/dataTables.bs4-custom.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/vendor/datatables/buttons.bs.css') }}">


<div class="modal-header bg-success">
    <h5 class="modal-title" id="detailLabel">Import Pemilah Aktif -
        Periode
        {{ $periode }}
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body" style="overflow-y: auto">
    <input type="hidden" name="periode" value="{{ $periode }}" required>
    <div class="table-responsive mt-4">
        <table id="highlightRowColumn" class="table custom-table">
            <thead>
                <tr>
                    <th>
                        <div class="form-check form-check-inline ms-1">
                            <input type="checkbox" class="form-check-input check-all" id=" exampleCheck1">
                            <label class="form-check-label" for="exampleCheck1"></label>
                        </div>

                    </th>
                    <th>Peringkat</th>
                    <th>No Member</th>
                    <th>Nama Nasabah/Pelanggan</th>
                    <th>Hasil Electre</th>
                    <th>Alasan (Opsional)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rekomendasi as $item)
                    <tr>
                        <td>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input check-item" value="{{ $item->id }}"
                                    multiple name="checkbox_pemilah_aktif[]" id="exampleCheck1">
                                <label class="form-check-label" for="exampleCheck1"></label>
                            </div>
                        </td>
                        <td>{{ $item->ranking }}</td>
                        <td>{{ $item->user->no_member }}</td>
                        <td>
                            {{ $item->user->name }}
                        </td>
                        <td>{{ $item->hasil_electre }}</td>
                        <td><input type="text" style="text-transform: capitalize" maxlength="255" placeholder="Default: Sesuai Rekomendasi Sistem" name="alasan[{{ $item->id }}]" multiple id="alasan"
                                class="form-control"></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
    <button type="submit" class="btn btn-primary">Import Pemenang</button>
</div>
<!-- Data Tables -->
<script src="{{ asset('assets/admin/vendor/datatables/dataTables.min.js') }}"></script>
<script src="{{ asset('assets/admin/vendor/datatables/dataTables.bootstrap.min.js') }}"></script>

<!-- Custom Data tables -->
<script src="{{ asset('assets/admin/vendor/datatables/custom/custom-datatables.js') }}"></script>
<script src="{{ asset('assets/admin/vendor/datatables/custom/fixedHeader.js') }}"></script>
<script>
    $('.check-all').on('change', function() {
        if ($(this).is(':checked')) {
            $('.check-item').each(function(i, e) {
                $(e).prop('checked', true);
            });
        } else {
            $('.check-item').each(function(i, e) {
                $(e).prop('checked', false);
            });
        }
    });


    $('.check-item').each(function(i, e) {
        $(e).prop('checked', false);
    });
</script>

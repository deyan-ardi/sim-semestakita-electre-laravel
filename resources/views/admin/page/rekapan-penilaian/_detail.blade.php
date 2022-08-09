 <div class="modal-header bg-success">
     <h5 class="modal-title" id="detailLabel">Rekapan Penilaian -
         {{ $find != false ? $find->user->name : 'Kesalahan (404) Data Tidak Ditemukan' }}
     </h5>
     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
 </div>
 <div class="modal-body" style="overflow-y: auto">
     @if ($detail != false)
         @foreach ($detail as $item)
             <div class="form-group">
                 <label
                     class="col-form-label">{{ $item->kriteria->nama_kriteria }} ({{ $item->kriteria->bobot }}%)</label>
                 <div class="row gutters mb-3">
                     <div class="input-group">
                         <input disabled class="form-control" value="{{ $item->total_nilai }}" required>
                         <span class="input-group-text">
                             Kali
                         </span>
                     </div>
                 </div>
             </div>
         @endforeach
     @else
         <div class="mb-3">
             <label for="value" class="form-label">Kesalahan (404) Data Tidak Ditemukan</label>
         </div>
     @endif
 </div>
 <div class="modal-footer">
     <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
 </div>

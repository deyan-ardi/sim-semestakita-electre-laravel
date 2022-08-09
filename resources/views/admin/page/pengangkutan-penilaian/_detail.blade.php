 <div class="modal-header bg-success">
     <h5 class="modal-title" id="detailLabel">Penilaian Harian -
         {{ $find != false ? $find->user->name : 'Kesalahan (404) Data Tidak Ditemukan' }}
     </h5>
     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
 </div>
 <div class="modal-body" style="overflow-y: auto">
     @if ($detail != false)
         @foreach ($detail as $item)
             <div class="mb-3">
                 <label for="value" class="form-label">{{ $item->kriteria->nama_kriteria }}</label>
                 <input type="text" disabled id="value" class="form-control" value="{{ $item->nilai_kriteria }}">
             </div>
         @endforeach
     @else
         <div class="mb-3">
             <label for="value" class="form-label">Kesalahan (404) Data Tidak Ditemukan</label>
         </div>
     @endif
 </div>
 <div class="modal-footer">
     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
 </div>

  @if ($status == 'enabled')
      <form id="delete-{{ $model->id }}" action="{{ route('pengangkutan-penilaian.destroy', $model->id) }}"
          method="POST">
          @method('DELETE')
          @csrf
          <div class="actions">
              <a href="javascript:void(0)" onclick="requestDetailOrder(' {{ $model->id }}')" data-bs-toggle="modal"
                  data-bs-target="#detail" data-toggle="tooltip" data-placement="top" title="Lihat Detail Peniliaian"
                  data-original-title="Lihat Detail Penilaian">
                  <i class="icon-eye text-info"></i>
              </a>
              <button type="button" onclick="deleteButton(' {{ $model->user->name }}' ,'{{ $model->id }}')"
                  data-toggle="tooltip" data-placement="top" title="Hapus Penilaian "
                  class="btn btn-link text-decoration-none ps-2 pb-2">
                  <i class="icon-trash text-danger"></i>
              </button>
          </div>
      </form>
  @else
      <div class="actions">
          <a href="javascript:void(0)" onclick="requestDetailOrder(' {{ $model->id }}')" data-bs-toggle="modal"
              data-bs-target="#detail" data-toggle="tooltip" data-placement="top" title="Lihat Detail Peniliaian"
              data-original-title="Lihat Detail Penilaian">
              <i class="icon-eye text-info"></i>
          </a>
      </div>
  @endif

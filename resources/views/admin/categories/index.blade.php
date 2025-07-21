@extends('layouts/contentNavbarLayout')

@section('title', 'Shop By Category')

@section('content')


@if(session('success'))
  <div class="alert alert-success" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h4 class="fw-bold mb-0">Category List</h4>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">+ Add Category</button>
  </div>

  <div class="card-body">
    <div class="table-responsive text-nowrap">
      <table class="table">
        <thead>
          <tr>
            <th>Sl No.</th>
            <th>Name</th>
            <th>Icon</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          @forelse ($categories as $index => $category)
            <tr>
              <td>{{ $index + 1 }}</td>
              <td>{{ $category->name }}</td>
              <td>
                @if($category->icon)
                  <img src="{{ asset('storage/'.$category->icon) }}" alt="icon" width="50">
                @else
                  No Icon
                @endif
              </td>
              <td>
                <div class="form-check form-switch" data-bs-toggle="tooltip" title="Toggle status">
                  <input class="form-check-input ms-auto" type="checkbox"
                    id="customSwitch{{$category->id}}"
                    {{ $category->status ? 'checked' : '' }}
                    onclick="statusToggle('{{ route('admin.category.status', $category->id) }}', this)">
                  <label class="form-check-label" for="customSwitch{{$category->id}}"></label>
                </div>
              </td>
              <td class="d-flex">
                <div class="btn-group">
                  <a href="javascript:void(0);" class="btn btn-sm btn-icon btn-outline-primary"
                    data-bs-toggle="modal" data-bs-target="#editModal{{ $category->id }}"
                    data-bs-toggle="tooltip" title="Edit">
                    <i class="ri-pencil-line"></i>
                  </a>
                  <a href="javascript:void(0);" class="btn btn-sm btn-icon btn-outline-danger"
                    onclick="deletePage({{ $category->id }})"
                    data-bs-toggle="tooltip" title="Delete">
                    <i class="ri-delete-bin-6-line"></i>
                  </a>
                </div>
              </td>
            </tr>
            <!-- Edit Modal -->
            <div class="modal fade" id="editModal{{ $category->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                <form class="modal-content" action="{{ route('admin.category.update', $category) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="modal-header">
                    <h5 class="modal-title">Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="name" value="{{ $category->name }}" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Icon</label>
                        <input type="file" name="icon" class="form-control">
                        @if($category->icon)
                        <img src="{{ asset('storage/'.$category->icon) }}" width="50" class="mt-2">
                        @endif
                    </div>
                    </div>
                    <div class="modal-footer">
                    <button class="btn btn-primary">Update</button>
                    </div>
                </form>
                </div>
            </div>
          @empty
            <tr>
              <td colspan="5" class="text-center">No Categories Found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>



<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" action="{{ route('admin.category.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Add Category</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label>Name</label>
          <input type="text" name="name" value="{{ old('name') }}" class="form-control">
          @error('name')
          <div class="text-danger small">{{ $message }}</div>
          @enderror
        </div>
        <div class="mb-3">
          <label>Icon</label>
          <input type="file" name="icon" class="form-control">
          @error('icon')
            <div class="text-danger small">{{ $message }}</div>
          @enderror
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary">Save</button>
      </div>
    </form>
  </div>
</div>

@endsection

@section('scripts')

<script>
// function deletePage(pageId) {
//     Swal.fire({
//         icon: 'warning',
//         title: "Are you sure you want to delete this?",
//         text: "You won't be able to revert this!",
//         showCancelButton: true,
//         confirmButtonColor: "#3085d6",
//         cancelButtonColor: "#d33",
//         confirmButtonText: "Delete",
//     }).then((result) => {
//         /* Read more about isConfirmed, isDenied below */
//         if (result.isConfirmed) {
//             $.ajax({
//                 url: "{{ route('admin.category.delete')}}",
//                 type: 'POST',
//                 data: {
//                     "id": pageId,
//                     "_token": '{{ csrf_token() }}',
//                 },
//                 success: function (data){
//                     if (data.status != 200) {
//                         toastFire('error', data.message);
//                     } else {
//                         toastFire('success', data.message);
//                         location.reload();
//                     }
//                 }
//             });
//         }
//     });
// }

function deletePage(pageId) {
    Swal.fire({
        icon: 'warning',
        title: "Are you sure you want to delete this?",
        text: "You won't be able to revert this!",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Delete",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ route('admin.category.delete') }}",
                type: 'POST',
                data: {
                    "id": pageId,
                    "_token": '{{ csrf_token() }}',
                },
                success: function (data) {
                    if (data.status != 200) {
                        toastFire('error', data.message);
                    } else {
                        toastFire('success', data.message);
                        location.reload();
                    }
                }
            });
        }
    });
}

</script>

@if ($errors->any())
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        var createModal = new bootstrap.Modal(document.getElementById('createModal'));
        createModal.show();
    });
    </script>
@endif

@endsection
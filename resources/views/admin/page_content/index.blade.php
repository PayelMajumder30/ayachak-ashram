
@extends('layouts/contentNavbarLayout')

@section('title', 'Page - Content')

@section('content')

@if(session('success'))
    <div class="alert alert-success" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
<!-- Basic Bootstrap Table -->
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h4 class="fw-bold mb-0">Page List</h4>    
      <a href="{{ route('admin.pagecontent.create')}}" class="btn btn-primary btn-sm">+ Create new page</a>
  </div>

  <div class="px-3 py-2">
    <form action="" method="get">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-9">
                <div class="d-flex justify-content-end align-items-center"> {{-- Added align-items-center for vertical alignment --}}
                    <div class="form-group me-2 mb-0">
                        <input type="search" class="form-control form-control-sm" name="keyword" id="keyword" value="{{ request()->input('keyword') }}" placeholder="Search something...">
                    </div>
                    <div class="form-group mb-0">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="tf-icons ri-filter-3-line"></i>
                            </button>
                            <a href="{{ url()->current() }}" class="btn btn-sm btn-light" data-toggle="tooltip" title="Clear filter">
                                <i class="tf-icons ri-close-line"></i>
                            </a>                        

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
  </div>



  <div class="card-body">
    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead>
            <tr>
                <th>Sl No.</th>
                <th>Page</th>
                <th>Title</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody class="table-border-bottom-0">
            @forelse ($data as $index => $item)
                    <tr>
                        <td>{{ $index + $data->firstItem() }}</td>
                        <td>{{ strtoupper($item->page) }}</td>
                        <td>{{ ucwords($item->title) }}</td>
                        <td>
                            <div class="form-check form-switch" data-bs-toggle="tooltip" title="Toggle status">
                                <input class="form-check-input ms-auto" type="checkbox" id="customSwitch{{$item->id}}"
                                {{ $item->status ? 'checked' : ''}} onclick="statusToggle('{{route('admin.pagecontent.status', $item->id)}}', this)" />
                                <label class="form-check-label" for="customSwitch{{$item->id}}"></label>
                            </div>
                        </td>
                        <td class="d-flex">
                            <div class="btn-group">
                                <a href="{{ route('admin.pagecontent.edit', $item->id) }}" class="btn btn-sm btn-icon btn-outline-dark"                     
                                    data-bs-toggle="tooltip"  title="Edit">
                                    <i class="ri-pencil-line"></i>
                                </a>
                            <div>
                                <a href="javascript:void(0);" class="btn btn-sm btn-icon btn-outline-danger" onclick="deletePage({{ $item->id }})"     
                                    data-bs-toggle="tooltip" title="Delete">
                                    <i class="ri-delete-bin-6-line"></i>
                                </a>
                            </div> 
                                    
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="100%" class="text-center">No records found</td>
                    </tr>
                @endforelse         
            </tbody>
        </table>
        <div class="pagination-container">
            {{$data->appends($_GET)->links()}}
        </div>
    </div>
  </div>

</div>
@endsection
@section('scripts')
<script>
  
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
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ route('admin.pagecontent.delete')}}",
                type: 'POST',
                data: {
                    "id": pageId,
                    "_token": '{{ csrf_token() }}',
                },
                success: function (data){
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

@endsection

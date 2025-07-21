@extends('layouts/contentNavbarLayout')

@section('title', 'Products')

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
    <h4 class="fw-bold mb-0">Product List</h4>    
      <a href="{{ route('admin.products.create')}}" class="btn btn-primary btn-sm">+ Add New Product</a>
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
                <th>Title</th>
                <th>product code</th>
                <th>Featured</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody class="table-border-bottom-0">
            @forelse ($data as $index => $item)
                <tr>
                    <td>{{ $index + $data->firstItem() }}</td>
                    <td>{{ ucwords($item->title) }}</td>
                    <td>{{ $item->product_code }}</td>
                    <td>
                        {{-- @if($item->is_featured)
                            <span class="badge bg-success">Yes</span>
                        @else
                            <span class="badge bg-secondary">No</span>
                        @endif --}}
                        <div class="form-check form-switch" data-bs-toggle="tooltip" title="Toggle status">
                            <input class="form-check-input ms-auto" type="checkbox" id="customSwitch{{$item->id}}"
                            {{ $item->is_featured ? 'checked' : ''}} onclick="statusToggle('{{route('admin.products.feature', $item->id)}}', this)" />
                            <label class="form-check-label" for="customSwitch{{$item->id}}"></label>
                        </div>
                    </td>
                    <td>
                        <div class="form-check form-switch" data-bs-toggle="tooltip" title="Toggle status">
                            <input class="form-check-input ms-auto" type="checkbox" id="customSwitch{{$item->id}}"
                            {{ $item->status ? 'checked' : ''}} onclick="featureToggle('{{route('admin.products.status', $item->id)}}', this)" />
                            <label class="form-check-label" for="customSwitch{{$item->id}}"></label>
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
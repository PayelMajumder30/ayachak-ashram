@extends('layouts/contentNavbarLayout')

@section('title', 'Page - Edit')

@section('content')

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0 text-primary">Edit Page content</h5>
        <a href="{{ route('admin.pagecontent.list') }}" class="btn btn-sm btn-danger">
            <i class="menu-icon tf-icons ri-arrow-left-line"></i> Back
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.pagecontent.update') }}" method="post" enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="id" value="{{ $data->id }}">

            <div class="row">
                <div class="form-group col-md-6">
                    <label for="page">New Page Name <span style="color: red;">*</span></label>
                    <input type="text" class="form-control" name="page" id="page" placeholder="Enter new page name"
                        value="{{ old('page', $data->page) }}">
                    @error('page')
                        <p class="small text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group col-md-6">
                    <label for="title">New Page Title <span style="color: red;">*</span></label>
                    <input type="text" class="form-control" name="title" id="title" placeholder="Enter title.."
                        value="{{ old('title', $data->title) }}">
                    @error('title')
                        <p class="small text-danger">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="description">Description <span style="color: red;">*</span></label>
                <textarea name="description" id="description" cols="4" rows="5" class="form-control ckeditor"
                    placeholder="">{{ old('description', $data->description) }}</textarea>
                @error('description')
                    <p class="small text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="text-start">
                <button type="submit" class="btn btn-primary px-4 py-2">Update</button>
            </div>
        </form>
    </div>
</div>

@endsection
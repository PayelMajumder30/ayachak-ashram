
@extends('layouts/contentNavbarLayout')

@section('title', 'Page - COntent')

@section('content')

<div class="card p-4">
  <div class="card-header d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0 text-primary">Create Page</h3>
    <a href="{{ route('admin.pagecontent.list') }}" class="btn btn-danger">
      <i class="ri-arrow-left-line"></i> Back
    </a>
  </div>

    <div class="card-body">
        <form action="{{ route('admin.pagecontent.store') }}" method="post" enctype="multipart/form-data">@csrf

            <div class="row">
                <div class="form-group col-md-6">
                    <label for="page">New Page Name <span style="color: red;">*</span></label>
                    <input type="text" class="form-control" name="page" id="page" placeholder="Enter new page name" value="{{ old('page') }}">
                    @error('page')
                        <p class="small text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group col-md-6">
                    <label for="title">New Page Title <span style="color: red;">*</span></label>
                    <input type="text" class="form-control" name="title" id="title" placeholder="Enter title.." value="{{ old('title') }}">
                    @error('title')
                        <p class="small text-danger">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="description">Description <span style="color: red;">*</span></label>
                <textarea name="description" id="description" cols="4" rows="5" class="form-control ckeditor" placeholder="">{{ old('description') }}</textarea>
                @error('description')
                    <p class="small text-danger">{{ $message }}</p>
                @enderror
            </div>

         
            <div class="text-start">
                <button type="submit" class="btn btn-primary px-4 py-2">Create</button>
            </div>
        </form>
    </div>

</div>

@endsection
@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        if (document.querySelector('#description')) {
            CKEDITOR.replace('description', {
                height: 300,
                removePlugins: 'sourcearea',
                toolbarGroups: [
                    { name: 'basicstyles', groups: ['basicstyles', 'cleanup'] },
                    { name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align'] },
                    { name: 'insert' },
                    { name: 'styles' },
                    { name: 'colors' },
                    { name: 'links' },
                    { name: 'tools' }
                ]
            });
        }
    }); 
</script>

@endsection

@extends('layouts/contentNavbarLayout')

@section('title', 'Create - Page')

@section('content')

<div class="card p-4">
  <div class="card-header d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0 text-primary">Create Page</h3>
    <a href="{{ route('admin.pagecontent.list') }}" class="btn btn-danger">
      <i class="ri-arrow-left-line"></i> Back
    </a>
  </div>

    <div class="card-body">
      
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
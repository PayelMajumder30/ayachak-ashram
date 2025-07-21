@extends('layouts/contentNavbarLayout')

@section('title', 'Create - Product')

@section('content')


<div class="card p-4">
  <div class="card-header d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0 text-primary">Create Page</h3>
    <a href="{{ route('admin.products.list') }}" class="btn btn-danger">
      <i class="ri-arrow-left-line"></i> Back
    </a>
  </div>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="card-body">
        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>

        {{-- <input type="hidden" name="product_code" value="{{ generateProductCode() }}"> --}}

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control ckeditor"></textarea>
        </div>

        <div class="form-group">
            <label>Images</label>
            <input type="file" name="images[]" class="form-control" id="imageInput" multiple>
        </div>
        <div id="imagePreview" class="d-flex flex-wrap mt-3"></div>

        <div class="form-row d-flex">
            <div class="form-group col-md-6 pe-md-3">
                <label>Category</label>
                <select name="category_id" id="category" class="form-control select2-single" required>
                    <option></option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-md-6 ps-md-3">
                <label>Related Product</label>
                <select name="related_product_id" id="product" class="form-control select2-single">
                    <option></option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>


        <button class="btn btn-primary">Create</button>
    </div>
</form>

@endsection
@section('scripts')
{{-- Select2 Script --}}
<script>
    $("#product").select2({
        placeholder: "Select a related product",
        allowClear: true
    });

    $("#category").select2({
        placeholder: "Select a category",
        allowClear: true
    });


    const input = document.getElementById('imageInput');
    const preview = document.getElementById('imagePreview');
    let files = [];

    input.addEventListener('change', function (e) {
        files = Array.from(e.target.files);
        preview.innerHTML = '';

        files.forEach((file, index) => {
            const reader = new FileReader();

            reader.onload = function (event) {
                const thumb = document.createElement('div');
                thumb.classList.add('image-thumb');

                thumb.innerHTML = `
                    <img src="${event.target.result}" alt="Image">
                    <button type="button" class="remove-btn" onclick="removeImage(${index})">&times;</button>
                `;

                preview.appendChild(thumb);
            };

            reader.readAsDataURL(file);
        });
    });

    function removeImage(index) {
        files.splice(index, 1);

        // Create a new DataTransfer object and reassign to input
        const dataTransfer = new DataTransfer();
        files.forEach(file => dataTransfer.items.add(file));
        input.files = dataTransfer.files;

        // Trigger change again to re-render preview
        const event = new Event('change');
        input.dispatchEvent(event);
    }

</script>

@endsection
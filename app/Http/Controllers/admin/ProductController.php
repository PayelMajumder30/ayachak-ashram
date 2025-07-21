<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\{Product, ProductImage, RelatedProduct, Category};

class ProductController extends Controller
{
    //

    public function index(Request $request)
    {
        $data = Product::orderBy('id', 'desc')->paginate(10); // or any desired per-page value
        return view('admin.product_management.index', compact('data'));
    }

    public function create() {
        $categories = Category::where('status', 1)->get();
        $products = Product::all();
        return view('admin.product_management.create', compact('categories','products'));
    }

    public function store(Request $request) {
        $request->validate([
            'title'               => 'required|string|max:255',
            'description'         => 'nullable|string',
            'images.*'            => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'related_product_id'  => 'nullable|exists:products,id',
            'is_featured'         => 'nullable|boolean',
            'category_id'         => 'required|exists:categories,id' // uncomment if passing from form
        ]);

        //  Generate product code (in controller, not from form)
        $productCode = Product::generateProductCode(); // or use a helper()

        //  Create the product
        $product = Product::create([
            'category_id'   => $request->category_id,
            'title'         => $request->title,
            'product_code'  => $productCode,
            'description'   => $request->description,
        ]);

        // Store uploaded images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                if ($file->isValid()) {
                    $fileName  = time() . rand(10000, 99999) . '.' . $file->extension();
                    $filePath  = 'uploads/products/' . $fileName;
                    $file->move(public_path('uploads/products'), $fileName);

                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $filePath,
                    ]);
                }
            }
        }

        // Store related product
        if ($request->related_product_id) {
            RelatedProduct::create([
                'product_id'         => $product->id,
                'related_product_id' => $request->related_product_id,
            ]);
        }

        return redirect()->route('admin.products.list')->with('success', 'Product created successfully.');
    }

    public function status($id)
    {
        $data = Product::findOrFail($id);

        $data->status = $data->status ? 0 : 1;
        $data->save();
        return response()->json([
            'status'  => 200,
            'message' => 'Status updated successfully'
        ]);
    }

    public function feature($id)
    {
        $data = Product::findOrFail($id);

        $data->is_featured = $data->is_featured ? 0 : 1;
        $data->save();
        return response()->json([
            'status'  => 200,
            'message' => 'Feature updated successfully'
        ]);
    }
}

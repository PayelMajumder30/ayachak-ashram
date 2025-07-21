<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index(){
        $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'icon' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
        ], [
            'name.required' => 'The category name is required.',
            'name.unique' => 'This category name already exists.',
            'icon.image' => 'The icon must be an image file.',
            'icon.mimes' => 'Allowed icon formats: png, jpg, jpeg, svg.',
            'icon.max' => 'The icon size must not be greater than 2MB.',
        ]);

        $iconPath = null;
        if ($request->hasFile('icon')) {
            $iconPath = $request->file('icon')->store('icons', 'public');
        }

        Category::create([
            'name' => $request->name,
            'icon' => $iconPath,
            'status' => 1,
        ]);

        return redirect()->route('admin.category.index')->with('success', 'Category created successfully.');

    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'icon' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
        ], [
            'name.required' => 'The category name is required.',
            'name.unique' => 'This category name already exists.',
            'icon.image' => 'The icon must be an image file.',
            'icon.mimes' => 'Allowed icon formats: png, jpg, jpeg, svg.',
            'icon.max' => 'The icon size must not be greater than 2MB.',
        ]);

        $iconPath = $category->icon;
        if ($request->hasFile('icon')) {
            $iconPath = $request->file('icon')->store('icons', 'public');
        }

        $category->update([
            'name' => $request->name,
            'icon' => $iconPath,
            'status' => 1,
        ]);

        return redirect()->route('admin.category.index')->with('success', 'Category updated successfully.');
    }

    public function status($category)
    {
        $data = Category::findOrFail($category);

        $data->status = $data->status ? 0 : 1;
        $data->save();
        return response()->json([
            'status'  => 200,
            'message' => 'Status updated successfully'
        ]);
    }

    public function delete(Request $request){
        $data = Category::find($request->id); 
    
        if (!$data) {
            return response()->json([
                'status'    => 404,
                'message'   => 'Page content not found.',
            ]);
        }
    
        $data->delete(); 
        return response()->json([
            'status'    => 200,
            'message'   => 'Page content deleted successfully.',
        ]);
    }

}

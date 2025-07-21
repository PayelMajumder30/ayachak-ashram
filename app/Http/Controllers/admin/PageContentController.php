<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\PageContent;

class PageContentController extends Controller
{
    //
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');

        $query = PageContent::query();

        $query->when($keyword, function ($q) use ($keyword) {
            $q->where(function ($subQuery) use ($keyword) {
                $subQuery->where('page', 'like', '%' . $keyword . '%')
                        ->orWhere('title', 'like', '%' . $keyword . '%');
            });
        });

        $data = $query->latest('id')->paginate(10);

        return view('admin.page_content.index', compact('data'));
    }


    public function create() {
        return view('admin.page_content.create');
    }

    public function store(Request $request) {
        $request->validate([
            'page'  => 'required|unique:page_contents,page',
            'title' => 'required|string|max:255',
            'description'   => 'required|string',
        ]);
        PageContent::create([
            'page'        => $request->page,
            'title'       => $request->title,
            'description' => $request->description,
            'status'      => 1,
        ]);

        return redirect()->route('admin.pagecontent.list')->with('success', 'Page content created successfully.');
    }

    public function edit($id){
        $data = PageContent::findOrFail($id);
        return view('admin.page_content.edit',compact('data'));
    }

    public function update(Request $request){
        $request->validate([
            'id'          => 'required|exists:page_contents,id',
            'page'        => 'required|string|max:255',
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $data = PageContent::findOrFail($request->id);
        $data->update([
            'page'  => $request->page,
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.pagecontent.list')->with('success', 'Page content updated successfully.');
    }

    public function status($id)
    {
        $data = PageContent::findOrFail($id);

        $data->status = $data->status ? 0 : 1;
        $data->save();
        return response()->json([
            'status'  => 200,
            'message' => 'Status updated successfully'
        ]);
    }

    
    public function delete(Request $request){
        $data = PageContent::find($request->id); 
    
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

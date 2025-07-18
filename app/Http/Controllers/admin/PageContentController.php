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

    public function status($id)
    {
        $user = PageContent::findOrFail($id);

        $user->status = $user->status ? 0 : 1;
        $user->save();
        return response()->json([
            'status'  => 200,
            'message' => 'Status updated successfully'
        ]);
    }
}

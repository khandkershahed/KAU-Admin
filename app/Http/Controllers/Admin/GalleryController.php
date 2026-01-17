<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller {

    public function __construct() {
        $this->middleware('permission:view gallery')->only('index');
        $this->middleware('permission:create gallery')->only(['create','store']);
        $this->middleware('permission:edit gallery')->only(['edit','update']);
        $this->middleware('permission:delete gallery')->only('destroy');
    }

    public function index() {
        $galleries = Gallery::orderBy('position')->get();
        return view('admin.pages.galleries.index', compact('galleries'));
    }

    public function create() {
        return view('admin.pages.galleries.create');
    }

    public function store(Request $request) {
        Gallery::create($request->validate([
            'owner_type'=>'required',
            'owner_id'=>'nullable',
            'title'=>'required',
            'slug'=>'required',
            'type'=>'required'
        ]));
        return redirect()->route('admin.galleries.index')->with('success','Gallery created');
    }
}

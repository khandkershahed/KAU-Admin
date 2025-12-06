<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NoticeCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view notice category')->only(['index']);
        $this->middleware('permission:create notice category')->only(['create', 'store']);
        $this->middleware('permission:edit notice category')->only(['edit', 'update']);
        $this->middleware('permission:delete notice category')->only(['destroy']);
    }
    //
}

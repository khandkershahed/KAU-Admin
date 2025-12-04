<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view permission')->only(['index']);
        $this->middleware('permission:create permission')->only(['create', 'store']);
        $this->middleware('permission:edit permission')->only(['edit', 'update']);
        $this->middleware('permission:delete permission')->only(['destroy']);
    }

    //
}

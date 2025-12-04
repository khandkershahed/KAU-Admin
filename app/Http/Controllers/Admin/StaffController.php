<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;

class StaffController extends Controller
{
    public function __construct()
{
    $this->middleware('permission:view staff')->only(['index']);
    $this->middleware('permission:create staff')->only(['create', 'store']);
    $this->middleware('permission:edit staff')->only(['edit', 'update']);
    $this->middleware('permission:delete staff')->only(['destroy']);
    $this->middleware('permission:show staff')->only(['show']);
}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'staffs' => Admin::latest('id')->get(),
        ];

        return view('admin.pages.staff.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'roles' => Role::latest('id')->get(),
        ];

        return view('admin.pages.staff.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

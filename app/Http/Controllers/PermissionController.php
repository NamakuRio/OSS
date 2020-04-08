<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    public function index()
    {
        if (checkPermission('permission.view')) abort(403);

        return view('permission');
    }

    public function store(Request $request, PermissionService $permissionService)
    {
        if (checkPermission('permission.create')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        $store = $permissionService->store($request);

        return response()->json($store);
    }

    public function show(Request $request, PermissionService $permissionService)
    {
        if (checkPermission('permission.view')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        $show = $permissionService->show($request);

        return response()->json($show);
    }

    public function update(Request $request, PermissionService $permissionService)
    {
        if (checkPermission('permission.update')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        $update = $permissionService->update($request);

        return response()->json($update);
    }

    public function destroy(Request $request, PermissionService $permissionService)
    {
        if (checkPermission('permission.delete')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        $destroy = $permissionService->destroy($request);

        return response()->json($destroy);
    }

    public function data()
    {
        if (checkPermission('permission.view')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        $permissions = Permission::all();

        return DataTables::of($permissions)
                    ->addColumn('action', function($permission) {
                        $action = "";

                        if(auth()->user()->can('permission.update')) $action .= "<a href='javascript:void(0)' class='btn btn-icon btn-primary' tooltip='Perbarui Izin' data-id='{$permission->id}' onclick='getUpdatePermission(this);'><i class='far fa-edit'></i></a>&nbsp;";
                        if(auth()->user()->can('permission.delete')) $action .= "<a href='javascript:void(0)' class='btn btn-icon btn-danger' tooltip='Hapus Izin' data-id='{$permission->id}' onclick='deletePermission(this);'><i class='fas fa-trash'></i></a>&nbsp;";

                        return $action;
                    })
                    ->escapeColumns([])
                    ->addIndexColumn()
                    ->make(true);
    }
}

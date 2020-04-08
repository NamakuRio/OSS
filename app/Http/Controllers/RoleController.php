<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Services\RoleService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    public function index()
    {
        if (checkPermission('role.view')) abort(403);

        return view('role');
    }

    public function store(Request $request, RoleService $roleService)
    {
        if (checkPermission('role.create')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        $store = $roleService->store($request);

        return response()->json($store);
    }

    public function show(Request $request, RoleService $roleService)
    {
        if (checkPermission('role.view')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);
        if(!checkDev()) if($request->id == 1) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        $show = $roleService->show($request);

        return response()->json($show);
    }

    public function update(Request $request, RoleService $roleService)
    {
        if (checkPermission('role.update')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);
        if(!checkDev()) if($request->id == 1) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        $update = $roleService->update($request);

        return response()->json($update);
    }

    public function setDefault(Request $request, RoleService $roleService)
    {
        if (checkPermission('role.update')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);
        if(!checkDev()) if($request->id == 1) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        $setDefault = $roleService->setDefault($request);

        return response()->json($setDefault);
    }

    public function getManage(Request $request, RoleService $roleService)
    {
        if (checkPermission('role.manage')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);
        if(!checkDev()) if($request->id == 1) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        $getManage = $roleService->getManage($request);

        return response()->json($getManage);
    }

    public function manage(Request $request, RoleService $roleService)
    {
        if (checkPermission('role.manage')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);
        if(!checkDev()) if($request->id == 1) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        $manage = $roleService->manage($request);

        return response()->json($manage);
    }

    public function destroy(Request $request, RoleService $roleService)
    {
        if (checkPermission('role.delete')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);
        if(!checkDev()) if($request->id == 1) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        $destroy = $roleService->destroy($request);

        return response()->json($destroy);
    }

    public function data()
    {
        if (checkPermission('role.view')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        if(checkDev()){
            $roles = Role::all();
        } else {
            $roles = Role::where('name', '!=', 'developer')->get();
        }

        return DataTables::of($roles)
                    ->editColumn('default_user', function($role) {
                        $default_user = "";

                        if($role->default_user == 0) $default_user = "<a href='javascript:void(0);' tooltip='Jadikan sebagai default pengguna' data-id='{$role->id}' onclick='setDefault(this)'><i class='text-danger fas fa-ban'></i></a>";
                        if($role->default_user == 1) $default_user = "<a href='javascript:void(0);' tooltip='Default pengguna ketika mendaftar'><i class='text-success fas fa-check'></i></a>";

                        return $default_user;
                    })
                    ->addColumn('action', function($role) {
                        $action = "";

                        if(auth()->user()->can('role.manage')) $action .= "<a href='javascript:void(0)' class='btn btn-icon btn-success' tooltip='Kelola Peran' data-id='{$role->id}' onclick='getManageRole(this);'><i class='fas fa-tasks'></i></a>&nbsp;";
                        if(auth()->user()->can('role.update')) $action .= "<a href='javascript:void(0)' class='btn btn-icon btn-primary' tooltip='Perbarui Peran' data-id='{$role->id}' onclick='getUpdateRole(this);'><i class='far fa-edit'></i></a>&nbsp;";
                        if(auth()->user()->can('role.delete')) $action .= "<a href='javascript:void(0)' class='btn btn-icon btn-danger' tooltip='Hapus Peran' data-id='{$role->id}' onclick='deleteRole(this);'><i class='fas fa-trash'></i></a>&nbsp;";

                        return $action;
                    })
                    ->escapeColumns([])
                    ->addIndexColumn()
                    ->make(true);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index()
    {
        if (checkPermission('user.view')) abort(403);

        return view('user');
    }


    public function store(Request $request, UserService $userService)
    {
        if (checkPermission('user.create')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);
        if(!checkDev()) if($request->role_id == 1) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        $store = $userService->store($request);

        return response()->json($store);
    }

    public function show(Request $request, UserService $userService)
    {
        if (checkPermission('user.view')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);
        if(!checkDev()) if($request->id == 1) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        $show = $userService->show($request);

        return response()->json($show);
    }

    public function update(Request $request, UserService $userService)
    {
        if (checkPermission('user.update')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);
        if(!checkDev()) if($request->id == 1) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);
        if(!checkDev()) if($request->role_id == 1) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        $update = $userService->update($request);

        return response()->json($update);
    }

    public function getManage(Request $request, UserService $userService)
    {
        if (checkPermission('user.manage')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);
        if(!checkDev()) if($request->id == 1) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        $getManage = $userService->getManage($request);

        return response()->json($getManage);
    }

    public function manage(Request $request, UserService $userService)
    {
        if (checkPermission('user.manage')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);
        if(!checkDev()) if($request->id == 1) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        $manage = $userService->manage($request);

        return response()->json($manage);
    }

    public function destroy(Request $request, UserService $userService)
    {
        if (checkPermission('user.delete')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);
        if(!checkDev()) if($request->id == 1) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        $destroy = $userService->destroy($request);

        return response()->json($destroy);
    }

    public function data()
    {
        if (checkPermission('user.view')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        if(checkDev()){
            $users = User::all();
        } else {
            $users = User::select('users.*')
            ->leftJoin('user_role', function($join) {
                $join->on('users.id', '=', 'user_role.user_id');
            })
            ->leftJoin('roles', function($join) {
                $join->on('user_role.role_id', '=', 'roles.id');
            })
            ->where('roles.name', '!=', 'developer')->get();
        }
        $users->load('roles');

        return DataTables::of($users)
            ->editColumn('username', function ($user) {
                $username = "";

                $username = "<img alt='Foto {$user->name}' src='" . asset('assets/img/avatar/avatar-1.png') . "' data-original='{$user->getPhoto()}' class='lazy rounded-circle mr-1' width='30'><span class='font-weight-600'> " . $user->username . "</span>";

                return $username;
            })
            ->addColumn('role', function ($user) {
                $role = "";

                $role = formatRole($user->getFirstRole());

                return $role;
            })
            ->addColumn('action', function ($user) {
                $action = "";

                if (auth()->user()->can('user.manage')) $action .= "<a href='javascript:void(0)' class='btn btn-icon btn-success' tooltip='Kelola Pengguna' data-id='{$user->id}' onclick='getManageUser(this);'><i class='fas fa-tasks'></i></a>&nbsp;";
                if (auth()->user()->can('user.update')) $action .= "<a href='javascript:void(0)' class='btn btn-icon btn-primary' tooltip='Perbarui Pengguna' data-id='{$user->id}' onclick='getUpdateUser(this);'><i class='far fa-edit'></i></a>&nbsp;";
                if (auth()->user()->can('user.delete')) $action .= "<a href='javascript:void(0)' class='btn btn-icon btn-danger' tooltip='Hapus Pengguna' data-id='{$user->id}' onclick='deleteUser(this);'><i class='fas fa-trash'></i></a>&nbsp;";

                return $action;
            })
            ->escapeColumns([])
            ->addIndexColumn()
            ->make(true);
    }

    public function select2Roles(Request $request)
    {
        $search = $request->data['search'];

        if(checkDev()){
            $roles = Role::where('name', 'like', '%'.$search.'%')->paginate(25);
        } else {
            $roles = Role::where([['name', 'like', '%'.$search.'%'], ['name', '!=', 'developer']])->paginate(25);
        }

        foreach($roles as $key => $record){
            $roles[$key]->name = formatRole($record->name);
        }

        return response()->json($roles);
    }
}

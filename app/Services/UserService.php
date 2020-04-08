<?php

namespace App\Services;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserService
{
    public function store(Request $request)
    {
        $validator = $this->validator($request->all());
        if($validator->fails()){
            return ['status' => 'warning', 'message' => $validator->errors()->first()];
        }

        DB::beginTransaction();
        try{
            $data = [
                'username' => $request->username,
                'name' => $request->name,
                'email' => formatEmail($request->email),
                'phone' => formatPhone($request->phone),
                'password' => bcrypt($request->password),
            ];

            $user = User::create($data);
            $this->updateRole($request, $user);

            DB::commit();
            return ['status' => 'success', 'message' => 'Berhasil menambahkan pengguna.'];
        }catch(Exception $e){
            DB::rollback();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function show(Request $request)
    {
        $user = User::find($request->id);
        $user->load('roles');

        if (!$user) {
            return ['status' => 'error', 'message' => 'Pengguna yang Anda cari tidak ditemukan.', 'data' => ''];
        }

        return ['status' => 'success', 'message' => 'Berhasil mengambil data pengguna', 'data' => $user];
    }

    public function update(Request $request)
    {
        $validator = $this->validator($request->all(), 'update', $request->id);
        if ($validator->fails()) {
            return ['status' => 'warning', 'message' => $validator->errors()->first()];
        }

        DB::beginTransaction();
        try {
            $data = [
                'username' => $request->username,
                'name' => $request->name,
                'email' => formatEmail($request->email),
                'phone' => formatPhone($request->phone),
            ];

            $user = User::find($request->id);
            $user->update($data);
            $this->updatePhoto($request, $user);
            $this->updatePassword($request, $user);
            $this->updateRole($request, $user);

            if (!$user) {
                DB::rollback();
                return ['status' => 'error', 'message' => 'Gagal memperbarui pengguna.'];
            }

            DB::commit();
            return ['status' => 'success', 'message' => 'Berhasil memperbarui pengguna.'];
        } catch (Exception $e) {
            DB::rollback();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }


    public function getManage(Request $request)
    {
        $permissions = Permission::all();
        $user = User::find($request->id);
        $view = "";

        $user_permissions = $this->get_user_permission($user->id);
        $role_permissions = $this->get_role_permission($user->roles->first()->id);

        $no = 1;
        $rows = array();
        $header = array();
        $tmp = array();

        foreach ($permissions as $permission) {
            $x = explode(".", $permission->name);
            if (!in_array($x[1], $header)) {
                $header[] = $x[1];
                $tmp[$x[1]] = 0;
            }
        }

        foreach ($permissions as $permission) {
            $x = explode(".", $permission->name);
            $rows[$x[0]] = $tmp;
        }

        foreach ($permissions as $key => $permission) {
            $x = explode(".", $permission->name);
            //Rows
            $rows[$x[0]][$x[1]] = array('id' => $permission->id, 'action_name' => $x[1], 'is_role_permission' => (isset($role_permissions[$permission->id]->is_role_permission) && $role_permissions[$permission->id]->is_role_permission == 1) ? 1 : '', 'is_user_permission' => (isset($user_permissions[$permission->id]->is_user_permission) && $user_permissions[$permission->id]->is_user_permission == 1) ? 1 : '', 'value' => (isset($user_permissions[$permission->id]) ? 1 : 0));
        }

        $view .= '<input type="hidden" id="manage-user-id" name="id" value="' . $user->id . '" required>';
        $view .= '<div class="table-responsive">';

        $view .= '<table class="table table-bordered table-hover text-center">';

        $view .= '<thead>';
        $view .= '<tr>';
        $view .= '<td>#</td>';
        $view .= '<td>Izin</td>';
        foreach ($header as $hd) {
            $view .= '<td>' . ucfirst($hd) . '</td>';
        }
        $view .= '</tr>';
        $view .= '</thead>';

        $view .= '<tbody>';
        foreach ($rows as $key => $row) {
            $view .= '<tr>';
            $view .= '<td>' . $no++ . '</td>';
            $view .= '<td>' . $key . '</td>';
            foreach ($row as $action) {
                $view .= '<td>';
                if ($action == 0) {
                    $view .= '-';
                } else {
                    $checked = '';
                    $role_checked = '';
                    $set_disabled = '';
                    $name_input = 'name="permission[]"';

                    if ($action['value'] == 1) $checked = 'checked';
                    if ($action['is_role_permission'] == 1) {
                        $checked = 'checked';
                        $role_checked = 'Role';
                        $set_disabled = 'disabled';
                        $name_input = '';
                    }

                    $view .= '<label class="custom-switch">';
                    $view .= '<input type="checkbox" class="custom-switch-input" ' . $name_input . ' value="' . $action['id'] . '" id="customSwitch' . $action['id'] . '" ' . $set_disabled . ' ' . $checked . '>';
                    $view .= '<span class="custom-switch-indicator"></span>';
                    $view .= '<span class="custom-switch-description">' . $role_checked . '</span>';
                    $view .= '</label>';
                }
                $view .= '</td>';
            }
            $view .= '</tr>';
        }
        $view .= '</tbody>';

        $view .= '</table>';

        $view .= '</div>';

        return ['status' => 'success', 'message' => 'Berhasil mengambil data Manage Pengguna', 'data' => $view];
    }

    public function manage(Request $request)
    {
        DB::beginTransaction();
        try {
            $permissions = $request->permission;
            $user = User::find($request->id);

            if (empty($permissions)) {
                $user->permissions()->detach();

                DB::commit();
                return ['status' => 'success', 'message' => 'Berhasil mengubah data izin pengguna.'];
            }

            for ($i = 0; $i < count($permissions); $i++) {
                $perms[] = Permission::find($permissions[$i]);
            }

            foreach ($perms as $perm) {
                $data[] = $perm->name;
            }

            if (!empty($user)) {
                $user->updatePermissions($data);
            }

            DB::commit();
            return ['status' => 'success', 'message' => 'Berhasil mengubah data izin pengguna.'];
        } catch (Exception $e) {
            DB::rollback();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = User::find($request->id);

            if (!$user) {
                DB::rollback();
                return ['status' => 'error', 'message' => 'Pengguna tidak ditemukan.'];
            }

            Storage::delete($user->photo);
            $user->delete();

            DB::commit();
            return ['status' => 'success', 'message' => 'Berhasil menghapus pengguna.'];
        } catch (Exception $e) {
            DB::rollback();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    protected function validator(array $data, $type = 'insert', $id = 0)
    {
        $data['username'] = $data['username'];
        $data['email'] = formatEmail($data['email']);
        $data['phone'] = formatPhone($data['phone']);

        $rules_username = "";
        $rules_email = "";
        $rules_phone = "";
        if ($type == 'insert') {
            $rules_username = 'unique:users,username';
            $rules_email = 'unique:users,email';
            $rules_phone = 'unique:users,phone';
        } else if ($type == 'update') {
            $rules_username = 'unique:users,username,' . $id;
            $rules_email = 'unique:users,email,' . $id;
            $rules_phone = 'unique:users,phone,' . $id;
        }

        $rules = [
            'username' => ['required', 'string', 'min:4', 'max:191', $rules_username],
            'name' => ['required', 'string', 'max:191'],
            'email' => ['required', 'string', 'email', 'max:191', $rules_email],
            'phone' => ['required', 'string', 'numeric', $rules_phone],
            'password' => ['confirmed'],
        ];

        $messages = [
            'required' => ':attribute tidak boleh kosong',
            'string' => ':attribute harus berupa string',
            'min' => ':attribute minimal :min karakter',
            'max' => ':attribute maksimal :max karakter',
            'numeric' => ':attribute harus berupa angka',
            'unique' => ':attribute yang Anda masukkan sudah terdaftar'
        ];

        return Validator::make($data, $rules, $messages);
    }


    protected function get_user_permission($id)
    {
        $user = User::find($id);

        $permissions = $user->permissions;

        $merge = array();

        if ($permissions) {
            foreach ($permissions as $permission) {
                $permission->is_user_permission = 1;
                $merge[$permission->id] = $permission;
            }
        }

        return $merge;
    }

    protected function get_role_permission($id)
    {
        $role = Role::find($id);

        $permissions = $role->permissions;

        $merge = array();

        if ($permissions) {
            foreach ($permissions as $permission) {
                $permission->is_role_permission = 1;
                $merge[$permission->id] = $permission;
            }
        }

        return $merge;
    }

    protected function updatePhoto(Request $request, User $user)
    {
        if ($request->has('photo')) {
            Storage::delete($user->photo);
            $user->photo = $request->file('photo')->store('images/user/photo');
            $user->save();
        }

        if ($request->null_photo) {
            Storage::delete($user->photo);
            $user->photo = null;
            $user->save();
        }
    }

    protected function updatePassword(Request $request, User $user)
    {
        if ($request->password != null) {
            $user->password = bcrypt($request->password);
            $user->save();
        }
    }

    protected function updateRole(Request $request, User $user, $type = "insert")
    {
        if($request->has('role_id')) {
            if($request->rold_id == 1){
                if(!checkDev()){
                    DB::rollback();
                    return ['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.'];
                }
            }
            $role = Role::find($request->role_id);

            if (!$role) {
                DB::rollback();
                return ['status' => 'error', 'message' => 'Peran tidak ditemukan.'];
            }

            $user->syncRoles($role->name);
        }
    }
}

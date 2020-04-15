<?php

namespace App\Services;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CustomerService
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
                'nik' => $request->nik,
                'name' => $request->name,
                'date_of_birth' => $request->date_of_birth,
                'phone' => formatPhone($request->phone),
            ];

            $customer = Customer::create($data);

            DB::commit();
            return ['status' => 'success', 'message' => 'Berhasil menambahkan pelanggan.'];
        }catch(Exception $e){
            DB::rollback();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function show(Request $request)
    {
        $customer = Customer::find($request->id);

        if (!$customer) {
            return ['status' => 'error', 'message' => 'Pelanggan yang Anda cari tidak ditemukan.', 'data' => ''];
        }

        return ['status' => 'success', 'message' => 'Berhasil mengambil data pelanggan', 'data' => $customer, 'date_of_birth' => $customer->date_of_birth->format('Y-m-d')];
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
                'nik' => $request->nik,
                'name' => $request->name,
                'date_of_birth' => $request->date_of_birth,
                'phone' => formatPhone($request->phone),
            ];

            $customer = Customer::find($request->id);
            $customer->update($data);

            if (!$customer) {
                DB::rollback();
                return ['status' => 'error', 'message' => 'Gagal memperbarui pelanggan.'];
            }

            DB::commit();
            return ['status' => 'success', 'message' => 'Berhasil memperbarui pelanggan.'];
        } catch (Exception $e) {
            DB::rollback();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {
            $customer = Customer::find($request->id);

            if (!$customer) {
                DB::rollback();
                return ['status' => 'error', 'message' => 'Pelanggan tidak ditemukan.'];
            }

            $customer->delete();

            DB::commit();
            return ['status' => 'success', 'message' => 'Berhasil menghapus pelanggan.'];
        } catch (Exception $e) {
            DB::rollback();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    protected function validator(array $data, $type = 'insert', $id = 0)
    {
        $data['phone'] = formatPhone($data['phone']);

        $rules_nik = "";
        $rules_phone = "";
        if ($type == 'insert') {
            $rules_nik = 'unique:customers,nik';
            $rules_phone = 'unique:customers,phone';
        } else if ($type == 'update') {
            $rules_nik = 'unique:customers,nik,' . $id;
            $rules_phone = 'unique:customers,phone,' . $id;
        }

        $rules = [
            'nik' => ['required', 'digits_between:14,20', $rules_nik],
            'name' => ['required', 'string', 'max:191'],
            'date_of_birth' => ['required', 'date'],
            'phone' => ['required', 'digits_between:10,15', $rules_phone],
        ];

        $messages = [
            'required' => ':attribute tidak boleh kosong',
            'string' => ':attribute harus berupa string',
            'min' => ':attribute minimal :max karakter',
            'max' => ':attribute maksimal :max karakter',
            'nik.digits_between' => ':attribute antara 14-20 karakter',
            'phone.digits_between' => ':attribute antara 10-15 karakter',
            'date' => ':attribute harus berupa tanggal',
            'unique' => ':attribute yang Anda masukkan sudah terdaftar'
        ];

        return Validator::make($data, $rules, $messages);
    }
}

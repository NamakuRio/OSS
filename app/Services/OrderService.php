<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Order;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderService
{
    public function store(Request $request)
    {
        $validator = $this->validator($request->all());
        if($validator->fails()){
            return ['status' => 'warning', 'message' => $validator->errors()->first()];
        }

        DB::beginTransaction();
        try{
            $customer = Customer::find($request->customer_id);
            if (!$customer) {
                DB::rollback();
                return ['status' => 'error', 'message' => 'Pelanggan tidak ditemukan.'];
            }

            $data = [
                'customer_id' => $request->customer_id,
                'type' => $request->type,
                'merk' => strtoupper($request->merk),
                'color' => strtoupper($request->color),
                'complaint' => $request->complaint,
                'completeness' => $request->completeness,
                'user_id' => auth()->user()->id,
                'status' => 1,
            ];

            $order = Order::create($data);
            $order->triggerHistory();
            $urlPrint = route('invoice.print', $order);

            DB::commit();
            return ['status' => 'success', 'message' => 'Berhasil menambahkan servis.', 'print' => $urlPrint];
        }catch(Exception $e){
            DB::rollback();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function show(Request $request)
    {
        $order = Order::find($request->id);
        $order->load('customer');

        if (!$order) {
            return ['status' => 'error', 'message' => 'Servis yang Anda cari tidak ditemukan.', 'data' => ''];
        }

        return ['status' => 'success', 'message' => 'Berhasil mengambil data servis', 'data' => $order];
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
                'type' => $request->type,
                'merk' => strtoupper($request->merk),
                'color' => strtoupper($request->color),
                'complaint' => $request->complaint,
                'completeness' => $request->completeness,
            ];

            $order = Order::find($request->id);
            $createDescription = $this->createDescription($request, $order, 'update');
            $order->update($data);
            $order->triggerHistory('update', $createDescription);

            if (!$order) {
                DB::rollback();
                return ['status' => 'error', 'message' => 'Gagal memperbarui servis.'];
            }

            DB::commit();
            return ['status' => 'success', 'message' => 'Berhasil memperbarui servis.'];
        } catch (Exception $e) {
            DB::rollback();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function changeCost(Request $request)
    {
        $order = Order::find($request->id);

        if (!$order) {
            return ['status' => 'error', 'message' => 'Servis yang Anda cari tidak ditemukan.', 'data' => ''];
        }

        DB::beginTransaction();
        try{
            $data = [
                'cost' => getCost($request->cost)
            ];

            $createDescription = $this->createDescription($request, $order, 'cost');
            $order->update($data);
            $order->triggerHistory('cost', $createDescription);

            if (!$order) {
                DB::rollback();
                return ['status' => 'error', 'message' => 'Gagal memperbarui servis.'];
            }

            DB::commit();
            return ['status' => 'success', 'message' => 'Berhasil memperbarui servis.'];
        }catch(Exception $e){
            DB::rollback();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function changeComment(Request $request)
    {
        $order = Order::find($request->id);

        if (!$order) {
            return ['status' => 'error', 'message' => 'Servis yang Anda cari tidak ditemukan.', 'data' => ''];
        }

        DB::beginTransaction();
        try{
            $data = [
                'comment' => $request->comment
            ];

            $createDescription = $this->createDescription($request, $order, 'comment');
            $order->update($data);
            $order->triggerHistory('comment', $createDescription);

            if (!$order) {
                DB::rollback();
                return ['status' => 'error', 'message' => 'Gagal memperbarui servis.'];
            }

            DB::commit();
            return ['status' => 'success', 'message' => 'Berhasil memperbarui servis.'];
        }catch(Exception $e){
            DB::rollback();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function changeStatus(Request $request)
    {
        $order = Order::find($request->id);

        if (!$order) {
            return ['status' => 'error', 'message' => 'Servis yang Anda cari tidak ditemukan.', 'data' => ''];
        }

        DB::beginTransaction();
        try{
            $data = [
                'status' => $request->status
            ];

            $createDescription = $this->createDescription($request, $order, 'status');
            $order->update($data);
            $order->triggerHistory('status', $createDescription);

            if (!$order) {
                DB::rollback();
                return ['status' => 'error', 'message' => 'Gagal memperbarui servis.'];
            }

            DB::commit();
            return ['status' => 'success', 'message' => 'Berhasil memperbarui servis.'];
        }catch(Exception $e){
            DB::rollback();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {
            $order = Order::find($request->id);

            if (!$order) {
                DB::rollback();
                return ['status' => 'error', 'message' => 'Servis tidak ditemukan.'];
            }

            $order->delete();

            DB::commit();
            return ['status' => 'success', 'message' => 'Berhasil menghapus servis.'];
        } catch (Exception $e) {
            DB::rollback();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    protected function validator(array $data, $type = 'insert', $id = 0)
    {
        $rules = [
            'customer_id' => ['required'],
            'type' => ['required'],
            'merk' => ['required', 'string'],
            'color' => ['required', 'string'],
        ];

        $messages = [
            'required' => ':attribute tidak boleh kosong',
            'string' => ':attribute harus berupa string',
        ];

        return Validator::make($data, $rules, $messages);
    }

    protected function createDescription(Request $request, Order $order, $type)
    {
        $description = "";

        if($type == 'update'){
            $description .= "Servis diubah.";
        } else if($type == 'cost'){
            $beforeCost = number_format($order->cost);
            $afterCost = number_format(getCost($request->cost));
            $description .= "Ubah harga servis dari <code>Rp. {$beforeCost}</code> menjadi <code>Rp. {$afterCost}</code>";
        } else if($type == 'comment'){
            $beforeComment = $order->comment;
            $afterComment = $request->comment;
            $description .= "Ubah komentar servis dari <code>{$beforeComment}</code> menjadi <code>{$afterComment}</code>";
        } else if($type == 'status'){
            $beforeStatus = $order->status;
            $afterStatus = $request->status;
            if($beforeStatus == 1) {
                $beforeBadge = 'badge-warning';
                $beforeStatusText = 'Proses';
            }
            if($beforeStatus == 2) {
                $beforeBadge = 'badge-danger';
                $beforeStatusText = 'Batal';
            }
            if($beforeStatus == 3) {
                $beforeBadge = 'badge-primary';
                $beforeStatusText = 'Sudah Dikerjakan';
            }
            if($beforeStatus == 4) {
                $beforeBadge = 'badge-success';
                $beforeStatusText = 'Sudah Diambil';
            }
            if($afterStatus == 1) {
                $afterBadge = 'badge-warning';
                $afterStatusText = 'Proses';
            }
            if($afterStatus == 2) {
                $afterBadge = 'badge-danger';
                $afterStatusText = 'Batal';
            }
            if($afterStatus == 3) {
                $afterBadge = 'badge-primary';
                $afterStatusText = 'Sudah Dikerjakan';
            }
            if($afterStatus == 4) {
                $afterBadge = 'badge-success';
                $afterStatusText = 'Sudah Diambil';
            }
            $description .= "Ubah status servis dari <span class='badge {$beforeBadge}'>{$beforeStatusText}</span> menjadi <span class='badge {$afterBadge}'>{$afterStatusText}</span>";
        }

        return $description;
    }
}

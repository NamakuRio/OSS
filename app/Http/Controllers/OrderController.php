<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    public function index()
    {
        if (checkPermission('order.view')) abort(403);

        return view('order.index');
    }

    public function create()
    {
        if (checkPermission('order.create')) abort(403);

        return view('order.create');
    }

    public function store(Request $request, OrderService $orderService)
    {
        if (checkPermission('order.create')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        $store = $orderService->store($request);

        return response()->json($store);
    }

    public function show(Request $request, OrderService $orderService)
    {
        if (checkPermission('order.view')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        $show = $orderService->show($request);

        return response()->json($show);
    }

    public function update(Request $request, OrderService $orderService)
    {
        if (checkPermission('order.update')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        $update = $orderService->update($request);

        return response()->json($update);
    }

    public function destroy(Request $request, OrderService $orderService)
    {
        if (checkPermission('order.delete')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        $destroy = $orderService->destroy($request);

        return response()->json($destroy);
    }

    public function changeCost(Request $request, OrderService $orderService)
    {
        if (checkPermission('order.cost')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        $changeCost = $orderService->changeCost($request);

        return response()->json($changeCost);
    }

    public function changeComment(Request $request, OrderService $orderService)
    {
        if (checkPermission('order.comment')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        $changeComment = $orderService->changeComment($request);

        return response()->json($changeComment);
    }

    public function changeStatus(Request $request, OrderService $orderService)
    {
        if (checkPermission('order.status')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        $changeStatus = $orderService->changeStatus($request);

        return response()->json($changeStatus);
    }

    public function history(Order $order)
    {
        if (checkPermission('order.view')) abort(403);

        return view('order.history', compact('order'));
    }

    public function getHistory(Order $order)
    {
        $histories = $order->orderHistories()->orderBy('created_at', 'DESC')->paginate(10);
        $histories->load('order', 'user');

        foreach($histories as $key => $history) {
            $histories[$key]->created_at_new_tooltip = $history->created_at->format('l, d M Y');
            $histories[$key]->created_at_new = $history->created_at->diffForHumans();
        }

        return response()->json(['status' => 'success', 'message' => 'Berhasil mengambil data riwayat perubahan pesanan.', 'data' => $histories]);
    }

    public function data()
    {
        if (checkPermission('order.view')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        $orders = Order::all();
        if(request()->filter == 1){
            $orders = Order::where('status', 1)->get();
        }
        $orders->load('customer', 'user');

        return DataTables::of($orders)
            ->editColumn('type', function ($order) {
                $type = "";

                $type = strtoupper($order->type);

                return $type;
            })
            ->editColumn('cost', function ($order) {
                $cost = "";

                $cost = "Rp. " . ($order->cost == null ? number_format(0) : number_format($order->cost));
                if(auth()->user()->can('order.cost')) $cost .= " <a href='javascript:void(0);' tooltip='Ubah harga Pesanan' data-id='{$order->id}' onclick='getChangeCostOrder(this);'><i class='far fa-edit'></i></a>";

                return $cost;
            })
            ->editColumn('comment', function ($order) {
                $comment = "";

                $comment = ($order->comment ?? null);
                if(auth()->user()->can('order.comment')) $comment .= " <a href='javascript:void(0);' tooltip='Ubah komentar Pesanan' data-id='{$order->id}' onclick='getChangeCommentOrder(this);'><i class='far fa-edit'></i></a>";

                return $comment;
            })
            ->editColumn('status', function ($order) {
                $status = "";
                $statusClass = "";
                $statusText = "";

                switch ($order->status) {
                    case 1:
                        $statusClass = "badge-warning";
                        $statusText = "Proses";
                        break;

                    case 2:
                        $statusClass = "badge-danger";
                        $statusText = "Batal";
                        break;

                    case 3:
                        $statusClass = "badge-primary";
                        $statusText = "Sudah Dikerjakan";
                        break;

                    case 4:
                        $statusClass = "badge-success";
                        $statusText = "Sudah Diambil";
                        break;

                    default:
                        $statusClass = "";
                        $statusText = "";
                        break;
                }
                $status = "<a href='javascript:void(0);' class='badge {$statusClass}'>{$statusText}</a>";

                if(auth()->user()->can('order.status')) $status = "<a href='javascript:void(0);' class='badge {$statusClass}' tooltip='Ubah status pesanan' data-id='{$order->id}' onclick='getChangeStatusOrder(this);'>{$statusText}</a>";

                return $status;
            })
            ->editColumn('created_at', function ($order) {
                $created_at = "";

                $created_at = "<span tooltip='" . $order->created_at . "' style='cursor:pointer'>" . $order->created_at->diffForHumans() . "</span>";

                return $created_at;
            })
            ->addColumn('customer_name', function ($order) {
                $customer_name = "";

                $customer_name = "<a href='javascript:void(0);'>{$order->customer->nik} - {$order->customer->name}</a>";

                return $customer_name;
            })
            ->addColumn('user_name', function ($order) {
                $user_name = "";

                $user_name = "<a href='javascript:void(0);'>{$order->user->name}</a>";

                return $user_name;
            })
            ->addColumn('action', function ($order) {
                $action = "";

                $url_history = route('orders.history', $order);
                $url_whatsapp = setWhatsAppUrl($order);
                $url_print = route('invoice.print', $order);

                if(auth()->user()->can('order.view')) $action .= "<a href='{$url_history}' class='btn btn-icon btn-primary' tooltip='Riwayat Perubahan'><i class='ion-flash'></i></a>&nbsp;";
                if(auth()->user()->can('order.view')) $action .= "<a href='{$url_whatsapp}' target='_blank' class='btn btn-icon btn-warning' tooltip='Kirim WA Pesanan'><i class='ion-social-whatsapp-outline'></i></a>&nbsp;";
                if(auth()->user()->can('order.view')) $action .= "<a href='{$url_print}' target='_blank' class='btn btn-icon btn-dark' tooltip='Print Pesanan'><i class='fas fa-print'></i></a>&nbsp;";
                if(auth()->user()->can('order.update')) $action .= "<a href='javascript:void(0)' class='btn btn-icon btn-primary' tooltip='Perbarui Pesanan' data-id='{$order->id}' onclick='getUpdateOrder(this);'><i class='far fa-edit'></i></a>&nbsp;";
                if(auth()->user()->can('order.delete')) $action .= "<a href='javascript:void(0)' class='btn btn-icon btn-danger' tooltip='Hapus Pesanan' data-id='{$order->id}' onclick='deleteOrder(this);'><i class='fas fa-trash'></i></a>&nbsp;";

                return $action;
            })
            ->escapeColumns([])
            ->addIndexColumn()
            ->make(true);
    }

    public function select2Customers(Request $request)
    {
        $search = $request->data['search'];

        $customers = Customer::where('name', 'like', '%' . $search . '%')->orWhere('nik', 'like', '%' . $search . '%')->paginate(25);

        return response()->json($customers);
    }
}

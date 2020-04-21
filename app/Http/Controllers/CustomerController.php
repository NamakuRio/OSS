<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Services\CustomerService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    public function index()
    {
        if (checkPermission('customer.view')) abort(403);

        return view('customer.index');
    }

    public function store(Request $request, CustomerService $customerService)
    {
        if (checkPermission('customer.create')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        $store = $customerService->store($request);

        return response()->json($store);
    }

    public function show(Request $request, CustomerService $customerService)
    {
        if (checkPermission('customer.view')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        $show = $customerService->show($request);

        return response()->json($show);
    }

    public function update(Request $request, CustomerService $customerService)
    {
        if (checkPermission('customer.update')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        $update = $customerService->update($request);

        return response()->json($update);
    }

    public function destroy(Request $request, CustomerService $customerService)
    {
        if (checkPermission('customer.delete')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        $destroy = $customerService->destroy($request);

        return response()->json($destroy);
    }

    public function detail(Customer $customer)
    {
        if (checkPermission('customer.view')) abort(403);

        $customer->load('orders');
        return view('customer.detail', compact('customer'));
    }

    public function detailView(Customer $customer)
    {
        if (auth()->user()) return redirect()->route('customers.detail', ['customer' => $customer]);

        $customer->load('orders');
        return view('customer.detail_view', compact('customer'));
    }

    public function data()
    {
        if (checkPermission('customer.view')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        $customers = Customer::all();

        return DataTables::of($customers)
            ->editColumn('date_of_birth', function ($customer) {
                $date_of_birth = "";

                $date_of_birth = $customer->date_of_birth->format('Y-m-d');

                return $date_of_birth;
            })
            ->editColumn('created_at', function ($customer) {
                $created_at = "";

                $created_at = $customer->created_at->diffForHumans();

                return $created_at;
            })
            ->addColumn('action', function ($customer) {
                $action = "";

                $url_whatsapp = setWhatsAppUrlV2($customer);

                if (auth()->user()->can('customer.view')) $action .= "<a href='https://api.whatsapp.com/send?phone={$customer->phone}' target='_blank' class='btn btn-icon btn-dark' tooltip='Kirim Custom WA Pelanggan'><i class='ion-social-whatsapp-outline'></i></a>&nbsp;";
                if (auth()->user()->can('customer.view')) $action .= "<a href='{$url_whatsapp}' target='_blank' class='btn btn-icon btn-warning' tooltip='Kirim WA Link Data Pelanggan'><i class='ion-social-whatsapp-outline'></i></a>&nbsp;";
                if (auth()->user()->can('customer.view')) $action .= "<a href='" . route('customers.detail', ['customer' => $customer]) . "' class='btn btn-icon btn-info' tooltip='Detail Pelanggan'><i class='fas fa-server'></i></a>&nbsp;";
                if (auth()->user()->can('customer.update')) $action .= "<a href='javascript:void(0)' class='btn btn-icon btn-primary' tooltip='Perbarui Pelanggan' data-id='{$customer->id}' onclick='getUpdateCustomer(this);'><i class='far fa-edit'></i></a>&nbsp;";
                if (auth()->user()->can('customer.delete')) $action .= "<a href='javascript:void(0)' class='btn btn-icon btn-danger' tooltip='Hapus Pelanggan' data-id='{$customer->id}' onclick='deleteCustomer(this);'><i class='fas fa-trash'></i></a>&nbsp;";

                return $action;
            })
            ->escapeColumns([])
            ->addIndexColumn()
            ->make(true);
    }

    public function customerOrders(Customer $customer)
    {
        // if (checkPermission('customer.view')) return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat mengakses ini.']);

        $checkViewData = [];

        if(auth()->user()) {
            if(auth()->user()->can('order.handphone')) array_push($checkViewData, "handphone");
            if(auth()->user()->can('order.laptop')) array_push($checkViewData, "laptop");
            if(auth()->user()->can('order.printer')) array_push($checkViewData, "printer");
            if(auth()->user()->can('order.komputer')) array_push($checkViewData, "komputer");
            if(auth()->user()->can('order.powerbank')) array_push($checkViewData, "powerbank");

            $orders = $customer->orders()->whereIn('type', $checkViewData)->get();
        } else {
            $orders = $customer->orders;
        }

        return DataTables::of($orders)
            ->editColumn('type', function ($order) {
                $type = "";

                $type = strtoupper($order->type);

                return $type;
            })
            ->editColumn('cost', function ($order) {
                $cost = "";

                $cost = "<span class='text-danger'>Rp. " . ($order->cost == null ? number_format(0) : number_format($order->cost)) . "</span>";
                if (auth()->user()) {
                    if (auth()->user()->can('order.cost')) $cost .= " <a href='javascript:void(0);' tooltip='Ubah harga Servis' data-id='{$order->id}' onclick='getChangeCostOrder(this);'><i class='far fa-edit'></i></a>";
                }

                return $cost;
            })
            ->editColumn('comment', function ($order) {
                $comment = "";

                $comment = "<span class='text-danger'>" . ($order->comment ?? null) . "</span>";
                if (auth()->user()) {
                    if (auth()->user()->can('order.comment')) $comment .= " <a href='javascript:void(0);' tooltip='Ubah komentar Servis' data-id='{$order->id}' onclick='getChangeCommentOrder(this);'><i class='far fa-edit'></i></a>";
                }

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

                if (auth()->user()) {
                    if (auth()->user()->can('order.status')) $status = "<a href='javascript:void(0);' class='badge {$statusClass}' tooltip='Ubah status servis' data-id='{$order->id}' onclick='getChangeStatusOrder(this);'>{$statusText}</a>";
                }

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

                if (auth()->user()) {
                    if (auth()->user()->can('order.view')) $action .= "<a href='{$url_history}' class='btn btn-icon btn-primary' tooltip='Riwayat Perubahan'><i class='ion-flash'></i></a>&nbsp;";
                    // if (auth()->user()->can('order.view')) $action .= "<a href='{$url_whatsapp}' target='_blank' class='btn btn-icon btn-warning' tooltip='Kirim WA Servis'><i class='ion-social-whatsapp-outline'></i></a>&nbsp;";
                    if (auth()->user()->can('order.view')) $action .= "<a href='{$url_print}' target='_blank' class='btn btn-icon btn-dark' tooltip='Print Servis'><i class='fas fa-print'></i></a>&nbsp;";
                    if (auth()->user()->can('order.update')) $action .= "<a href='javascript:void(0)' class='btn btn-icon btn-primary' tooltip='Perbarui Servis' data-id='{$order->id}' onclick='getUpdateOrder(this);'><i class='far fa-edit'></i></a>&nbsp;";
                    if (auth()->user()->can('order.delete')) $action .= "<a href='javascript:void(0)' class='btn btn-icon btn-danger' tooltip='Hapus Servis' data-id='{$order->id}' onclick='deleteOrder(this);'><i class='fas fa-trash'></i></a>&nbsp;";
                }

                return $action;
            })
            ->escapeColumns([])
            ->addIndexColumn()
            ->make(true);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Order $order)
    {
        $order->load('customer', 'user');
        return view('invoice', compact('order'));
    }

    public function print(Order $order)
    {
        $order->load('customer', 'user');
        return view('invoice', compact('order'))->with('print', true);
    }
}

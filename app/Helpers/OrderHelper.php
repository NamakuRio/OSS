<?php

use App\Models\Customer;
use App\Models\Order;

if (!function_exists('getCost')) {

    /**
     * description
     *
     * @param
     * @return
     */
    function getCost($cost)
    {
        $cost_new = explode("Rp. ", $cost)[1];
        $cost_new = str_replace('.', '', $cost_new);

        return $cost_new;
    }
}

if (!function_exists('setWhatsAppUrl')) {

    /**
     * description
     *
     * @param
     * @return
     */
    function setWhatsAppUrl(Order $order)
    {
        $phone = $order->customer->phone;
        $status = "";
        switch ($order->status) {
            case 1:
                $status = "Proses";
                break;

            case 2:
                $status = "Batal";
                break;

            case 3:
                $status = "Sudah Dikerjakan";
                break;

            case 4:
                $status = "Sudah Diambil";
                break;

            default:
                $status = "";
                break;
        }

        $text = "*OSS Service Center*\n\n*--Rincian Pelanggan--*\nID Pelanggan : {$order->customer->id}\nNIK : {$order->customer->nik}\nNama : {$order->customer->name}\nTanggal Lahir : ".$order->customer->date_of_birth->format('Y-m-d')."\nNo. HP : {$order->customer->phone}\n\n*--Rincian Pesanan--*\nID Pesanan : {$order->id}\nJenis Pesanan : " . strtoupper($order->type) . "\nMerek : {$order->merk}\nWarna : {$order->color}\nKeluhan : {$order->complaint}\nKelengkapan : {$order->completeness}\nHarga : *Rp. " . number_format($order->cost) . "*\nKomentar : {$order->comment}\nStatus : {$status}";
        $text_url = urlencode($text);

        $url = "https://api.whatsapp.com/send?phone={$phone}&text={$text_url}";

        return $url;
    }
}

if (!function_exists('setWhatsAppUrlV2')) {

    /**
     * description
     *
     * @param
     * @return
     */
    function setWhatsAppUrlV2(Customer $customer)
    {
        $text = "*OSS Service Center*\n\n*--Rincian Pelanggan--*\nID Pelanggan : {$customer->id}\nNIK : {$customer->nik}\nNama : {$customer->name}\nTanggal Lahir : ".$customer->date_of_birth->format('Y-m-d')."\nNo. HP : {$customer->phone}\n\nUntuk melihat data pesanan Anda dapat membuka link berikut ini : " . route('customers.detail.view', ['customer' => $customer]);
        $text_url = urlencode($text);

        $url = "https://api.whatsapp.com/send?phone={$customer->phone}&text={$text_url}";

        return $url;
    }
}

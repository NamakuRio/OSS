<?php

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

        $text = "*OSS Service Center*\n\n*--Rincian Pelanggan--*\nNIK : {$order->customer->nik}\nNama : {$order->customer->name}\nTanggal Lahir : ".$order->customer->date_of_birth->format('Y-m-d')."\nNo. HP : {$order->customer->phone}\n\n*--Rincian Pesanan--*\nNo. Pesanan : {$order->id}\nJenis Pesanan : " . strtoupper($order->type) . "\nMerek : {$order->merk}\nWarna : {$order->color}\nKeluhan : {$order->complaint}\nKelengkapan : {$order->completeness}\nHarga : *Rp. " . number_format($order->cost) . "*\nKomentar : {$order->comment}\nStatus : {$status}";
        $text_url = urlencode($text);

        $url = "https://api.whatsapp.com/send?phone={$phone}&text={$text_url}";

        return $url;
    }
}

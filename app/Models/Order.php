<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderHistories()
    {
        return $this->HasMany(OrderHistory::class);
    }

    public function triggerHistory($type = 'create', $description = 'Pesanan dibuat.')
    {
        $data = [
            'user_id' => auth()->user()->id,
            'type' => $type,
            'description' => $description,
        ];

        $this->orderHistories()->create($data);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['nik', 'name', 'date_of_birth', 'phone'];

    protected $dates = ['date_of_birth'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}

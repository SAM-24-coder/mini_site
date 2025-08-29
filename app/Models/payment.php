<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class payment extends Model
{
    //
    protected $table = 'payments';
    protected $primaryKey = 'id';
    protected $fillable = [
        'idKw',
        'user_name',
        'email',
        'pack',
        'amount',
        'payment_system',
        'status',
        'invoice_code',
        'date',
        'time',
    ];
}

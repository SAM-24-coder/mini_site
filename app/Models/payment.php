<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
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
        'user_id', // Make sure this column exists in your payments table
    ];

    protected $dates = [
        'date',
        'created_at',
        'updated_at'
    ];

    /**
     * Get the user that owns the payment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

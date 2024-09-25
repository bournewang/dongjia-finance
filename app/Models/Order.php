<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_no',
        'amount',
        'status',
        'type',
        'paid_at',
        'refund_at',
        'invoice_serial_num'
    ];

    protected $casts = [
    ];

    const CREATED = 'unpaid';
    const PAID = 'paid';
    const CANCELED = 'canceled';
    const REFUNDED = 'refunded';

    static public function statusOptions()
    {
        return [
            self::CREATED   => __(ucfirst(self::CREATED)),
            self::PAID      => __(ucfirst(self::PAID)),
            self::CANCELED  => __(ucfirst(self::CANCELED)),
            self::REFUNDED  => __(ucfirst(self::REFUNDED)),
        ];
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

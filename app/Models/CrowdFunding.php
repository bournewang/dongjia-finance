<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrowdFunding extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'partner_role',
        'paid_deposit',
        'using_period',
        'start_at',
        'end_at',
        'returned_at',
        'status',
        'comment'
    ];

    protected $casts = [
    ];

    const APPLYING = 'applying';
    const WAITING = 'waiting';
    const USING = 'using';
    const COMPLETED = 'completed';
    const CANCELED = 'canceled';

    static public function statusOptions()
    {
        return [
            self::APPLYING  => ___(self::APPLYING),
            self::WAITING   => ___(self::WAITING),
            self::USING     => ___(self::USING),
            self::COMPLETED => ___(self::COMPLETED),
            self::CANCELED  => ___(self::CANCELED),
        ];
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function info()
    {
        $data = $this->toArray();
        $data['user_name'] = $this->user->displayName();
        $data['avatar'] = $this->user->avatar;
        return $data;
    }
}

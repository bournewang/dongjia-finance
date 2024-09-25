<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "province_code",
        "province_name",
        "city_code",
        "city_name",
        "county_code",
        "county_name",
        "status"
    ];

    const APPLYING = "applying";
    const APPROVED = "approved";
    const REJECTED = "rejected";

    static public function statusOptions()
    {
        return [
            self::APPLYING => ___(self::APPLYING),
            self::APPROVED => ___(self::APPROVED),
            self::REJECTED => ___(self::REJECTED)
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function info()
    {
        $data = $this->toArray();
        $data['status_label'] = self::statusOptions()[$this->status] ?? null;
        $data['status_icon'] = [
            self::APPLYING => 'time',
            self::APPROVED => 'verify',
            self::REJECTED => 'close-circle'
        ][$this->status] ?? null;
        $data['step_index'] = $this->status == self::APPLYING ? 0 : 1;
        $data['step_options'] = [
            ['status' => self::APPLYING, 'icon' => 'time', 'title' => self::statusOptions()[self::APPLYING], 'tips' => "等待审核通过"],
        ];
        if ($this->status == self::REJECTED) {
            $data['step_options'][] = ['status' => self::REJECTED, 'icon' => 'close-circle', 'title' => self::statusOptions()[self::REJECTED], 'tips' => "申请被驳回"];
        }else{
            $data['step_options'][] = ['status' => self::APPROVED, 'icon' => 'verify', 'title' => self::statusOptions()[self::APPROVED], 'tips' => "已获代理资格"];
        }
        return $data;
    }
}

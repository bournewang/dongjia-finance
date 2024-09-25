<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\ChallengeHelper;

class Challenge extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'partner_role',
        'type',
        'level',
        'success_at',
        'status',
        'reason'
    ];

    protected $casts = [
    ];

    const TYPE_CONSUMER     = 'consumer';
    const TYPE_CAR_MANAGER  = 'car_manager';
    const TYPE_CAR_OWNER    = 'car_owner';

    static public function typeOptions()
    {
        return [
            self::TYPE_CONSUMER     => ___(self::TYPE_CONSUMER),
            self::TYPE_CAR_MANAGER  => ___(self::TYPE_CAR_MANAGER),
            self::TYPE_CAR_OWNER    => ___(self::TYPE_CAR_OWNER),
        ];
    }
    const APPLYING = 'applying';
    const CHALLENGING = 'challenging';
    const SUCCESS = 'success';
    const CANCELED = 'canceled';
    const REJECTED = 'rejected';

    static public function statusOptions()
    {
        return [
            self::APPLYING      => __("Challenge").__(ucfirst(self::APPLYING)),
            self::CHALLENGING   => __(ucfirst(self::CHALLENGING)),
            self::SUCCESS       => __("Challenge Success"),
            self::CANCELED      => __(ucfirst(self::CANCELED)),
            self::REJECTED      => __(ucfirst(self::REJECTED))
        ];
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function info()
    {
        $userInfo = $this->user->info();
        $data = [
            "id" => $this->id,
            "referer_name" => $userInfo['referer_name'],
            "current_level" => $userInfo['level'],
            "current_level_label" => $userInfo['level_label'],
            "qrcode" => $userInfo['qrcode'],
            "level" => $this->level,
            "level_label" => User::levelOptions()[$this->level],
            "success_at" => $this->success_at,
            "status" => $this->status,
            "status_label" => self::statusOptions()[$this->status]
        ];

        if ($this->status == self::CHALLENGING) {
            $data['overview'] = ChallengeHelper::getRank($this);
        }elseif ($this->status == self::SUCCESS) {
            $data['status_prompt'] =
                // (config("challenge.levels")[$this->level]['bonus_text'] ?? null) . "<br/><hr><br/>" .
                (config("challenge.levels")[$this->level]['success_text'] ?? null);
        }
        $levelOptions = User::levelOptions();
        if ($str = config("challenge.status")[$this->status]['text']) {
            $current_text = str_replace("{challenge_type_label}", $this->user->challenge_type_label, config("challenge.current_text")[$this->user->level] ?? null);
            $data["status_prompt"] = str_replace(
                ["{name}", "{level}", "{current_text}", "{new_level}", "{reason}"],
                [
                    $this->user->name,
                    $levelOptions[$this->user->level],
                    $current_text,
                    $levelOptions[$this->level],
                    $this->reason
                ],
                $str);
        }
        $data['status_icon'] = config("challenge.status")[$this->status]['icon'] ?? null;

        return $data;
    }
}

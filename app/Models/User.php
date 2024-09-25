<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Filament\Models\Contracts\FilamentUser;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser, HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    use InteractsWithMedia;

    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Fit::Contain, 300, 300)
            ->nonQueued();
        $this
            ->addMediaConversion('thumb')
            ->fit(Fit::Contain, 100, 100)
            ->nonQueued();
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'openid',
        'platform_openid',
        'name',
        'nickname',
        'avatar',
        'gender',
        'mobile',
        'qrcode',
        'id_no',
        'balance',
        'referer_id',
        'email',
        'password',
        'certified_at',
        'status',
        'level',
        "province_code",
        "province_name",
        "city_code",
        "city_name",
        "county_code",
        "county_name",
        "street",
        "challenge_id",
        "crowd_funding_id",
        "challenge_type",
        "challenge_type_label",
        "is_union_founder",
        "sales",
        "partner"
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'referer_id' => 'integer'
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->email == env("ADMIN_EMAIL");
    }

    public function referer()
    {
        return $this->belongsTo(User::class, 'referer_id');
    }

    public function recommends()
    {
        return $this->hasMany(User::class, 'referer_id');
    }

    public function challenge()
    {
        return $this->hasOne(Challenge::class);
    }

    public function crowdFunding()
    {
        return $this->hasOne(CrowdFunding::class);
    }

    public function company()
    {
        return $this->hasOne(Company::class, 'legal_person_id');
    }

    public function partnerCompanies()
    {
        return $this->belongsToMany(Company::class, "company_user")
        ->withPivot(
            "partnership_years",
            "partnership_start",
            "partnership_end",
            "subscription_amount",
            "paid_amount",
        );
    }

    public function agent()
    {
        return $this->hasOne(Agent::class);
    }

    public function car()
    {
        return $this->hasOne(Car::class);
    }

    public function cars()
    {
        return $this->hasMany(Car::class);
    }

    const NONE_REGISTER     = 0; //"none_register";
    const REGISTER_CONSUMER = 1; //"register_consumer";
    const PARTNER_CONSUMER  = 2; //"partner_consumer";
    const CONSUMER_MERCHANT = 11;
    const COMMUNITY_STATION = 12;
    const RUN_CENTER_DIRECTOR = 13;
    const COUNTY_MANAGER    = 14;
    const AREA_PRESIDENT    = 15;
    const PROVINCE_CEO      = 16;

    static public function levelOptions()
    {
        $options = [];
        foreach (config('challenge.levels') as $level => $data){
            $options[$level] = $data['label'];
        }
        return $options;
    }

    public function levelLabel()
    {
        return self::levelOptions()[$this->level];
    }

    const NON_RESP = "non_resp";
    const RESP = "resp";
    static public function respOptions(){
        return [
            self::NON_RESP => ___(self::NON_RESP),
            self::RESP => ___(self::RESP)
        ];
    }

    public function root()
    {
        return $this->belongsTo(User::class);
    }

    public function relation()
    {
        return $this->hasOne(Relation::class);
    }

    public function info()
    {
        $data = $this->toArray();
        $data['created_at'] = $this->created_at->toDateTimeString();
        $data['created_date'] = $this->created_at->toDateString();
        $data['level_label'] = $this->levelLabel();
        // $data['referer_id'] = $this->referer_id ?? 0;
        $data['referer_name'] = $this->referer->name ?? null;
        $data['referer_mobile'] = $this->referer->mobile ?? null;
        $data['qrcode'] = $this->qrcode ? url($this->qrcode) : null;
        $data['display_name'] = $this->displayName();
        $data['area'] = implode("|", [$this->province_code, $this->city_code,$this->county_code]);
        $data['display_area'] = $this->displayArea();
        $data['id_no_star'] = substr($this->id_no, 0,6)."****" . substr($this->id_no, -4,4);
        $data['agent_id'] = $this->agent->id ?? null;
        $data['agent_status'] = $this->agent->status ?? null;
        $data['is_agent'] = ($this->agent->status ?? null) == Agent::APPROVED;
        $data['challenge_id'] = $this->challenge->id ?? null;
        $data['challenge_status'] = $this->challenge->status ?? null;
        $data['is_challenging'] = in_array(($this->challenge->status ?? null), [Challenge::CHALLENGING, Challenge::SUCCESS]);
        $data['is_partner'] = $this->level == self::PARTNER_CONSUMER || $this->partner;
        $data['name_with_star'] = !$this->name ? null : (mb_substr($this->name, 0,1) . "**");
        $data['mobile_with_star'] = !$this->mobile ? null : (substr($this->mobile, 0, 3) . "****" . substr($this->mobile, -4, 4));
        $data['sales_label'] = $this->sales ? self::respOptions()[$this->sales] . __("Sales") : null;
        $data['vip_card'] = $this->is_union_founder ? url("/storage/mpp/level/union-founder.png") :
                ($this->level >= self::CONSUMER_MERCHANT && $this->level <= self::PROVINCE_CEO ?
                url("/storage/mpp/level/{$this->level}.png") : null);
        // $data['level'] = 0;
        // $data['sales'] = null;
        return $data;
    }

    public function displayName()
    {
        return $this->name ?? $this->nickname ?? $this->mobile ?? __("User").$this->id;
    }

    public function displayArea()
    {
        return $this->province_name . ($this->city_name ?? '') . $this->county_name;
    }

    public function displayAddress()
    {
        return $this->displayArea() . $this->street;
    }
}

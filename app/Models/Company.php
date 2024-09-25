<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        "company_type",
        "execute_partner",
        "partner_role",
        "company_name",
        "credit_code",
        "legal_person_id",
        "registered_at",
        "partner_years",
        "partner_start_at",
        "partner_end_at",
        "bank",
        "sub_bank",
        "account_name",
        "account_no",
    ];

    public function legalPerson()
    {
        return $this->belongsTo(User::class, "legal_person_id");
    }

    const COMMON_PARTNER = 'common_partner';
    const LIMITED_PARTNER = 'limited_partner';

    static public function partnerRoleOptions()
    {
        return [
            self::COMMON_PARTNER    => ___(self::COMMON_PARTNER),
            self::LIMITED_PARTNER   => ___(self::LIMITED_PARTNER)
        ];
    }

    public function info()
    {
        $data = $this->toArray();
        if ($this->legalPerson) {
            $data['legal_person_name'] = $this->legalPerson->name;
        }
        if ($this->bank) {
            $data['bank_label'] = config('banks')[$this->bank] ?? null;
        }
        $data['company_type_label'] = Challenge::typeOptions()[$this->company_type] ?? null;
        $data['partner_role_label'] = self::partnerRoleOptions()[$this->partner_role] ?? null;
        return $data;
    }
}

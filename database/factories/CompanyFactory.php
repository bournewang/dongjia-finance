<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Challenge;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = array_keys(Challenge::typeOptions());
        $name = fake()->name() . "合伙公司";
        $roles = array_keys(Company::partnerRoleOptions());
        return [
            //
            "company_type"      => $types[rand(0, count($types) - 1)],
            "execute_partner"   => "深圳市千百惠投资管理有限公司",
            "partner_role"      => $roles[rand(0, count($roles) - 1)],
            "company_name"      => $name,
            "credit_code"       => fake()->phoneNumber(),
            "legal_person_id"   => rand(20,100),
            "registered_at"     => today()->subDays(rand(1,30))->toDateString(),
            "partner_years"     => 5,
            "partner_start_at"  => today()->addDays(10),
            "partner_end_at"    => today()->addDays(10+365*5),
            "bank"              => config('banks')[rand(0, count(config('banks'))-1)],
            "sub_bank"          => "郑州中原支行",
            "account_name"      => $name,
            "account_no"        => rand(1000, 9999)." ".rand(1000, 9999)." ".rand(1000, 9999). " ".rand(1000, 9999). " ". rand(10,99),
        ];
    }
}

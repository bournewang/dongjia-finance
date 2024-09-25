<?php

namespace App\Helpers;
use App\Models\Company;
use App\Models\Challenge;

class FormHelper
{
    static public function partnerAssetTitle($type)
    {
        return [
            Challenge::TYPE_CONSUMER => __("New Consume").__("Partnership Assets"),
            Challenge::TYPE_CAR_MANAGER => __("Car Use Rights").__("Partnership Assets"),
            Challenge::TYPE_CAR_OWNER => __("CCER").__("Partnership Assets")
        ][$type ?? Challenge::TYPE_CONSUMER];
    }
    static public function partnerAssetFields($type)
    {
        $prefix = self::partnerAssetTitle($type);

        return [
            ["name" => "name",                  "label" => ___($type).__("Consumer name")],
            ["name" => "mobile",                "label" => ___($type).__("Consumer mobile")],
            ["name" => "partnership_years",     "label" => __("Partnership Years")],
            ["name" => "partnership_start",     "label" => __("Partnership start")],
            ["name" => "partnership_end",       "label" => __("Partnership end")],
            ["name" => "subscription_amount",   "label" => $prefix . __("Subscription to pay")],
            ["name" => "paid_amount",           "label" => $prefix . __("Paid amount")],
        ];
    }

    static public function carViewFields()
    {
        return [
            ["icon" => "data-display",  "name" => "plate_no", "label" => "车牌号", "disabled" => true],
            ["icon" => "barcode-1",     "name" => "vin", "label" => "车架号", "disabled" => true],
            ["icon" => "flag-1",        "name" => "car_model_brand", "label" => "品牌", "disabled" => true],
            ["icon" => "vehicle",       "name" => "car_model_name", "label" => "车型", "disabled" => true],
            ["icon" => "calendar-event","name" => "car_model_yeartype", "label" => "年份", "disabled" => true],
            ["icon" => "undertake-environment-protection", "name" => "car_model_fuelgrade", "label" => "汽油型号", "disabled" => true],
        ];
    }

    static public function carFormFields()
    {
        return [
            ["icon" => "vehicle",       "name" => "plate_no", "label" => "车牌号", "required" => true],
            ["icon" => "vehicle",       "name" => "vin", "label" => "车架号", "required" => true]
        ];
    }

    static public function consumerFields()
    {
        return [
            ["name" => "name",          "label" => __("Name")],
            ["name" => "mobile",        "label" => __("Mobile")],
            ["name" => "id_no",         "label" => __("ID No"),     "display_field" => "id_no_star"],
            // ["name" => "level_label",   "label" => "身份类别"],
            ["name" => "area",          "label" => __("Display Area"), "display_field" => "display_area", "type" => "area"],
            ["name" => "street",        "label" => __("Street")],
        ];
    }

    static public function salesFields()
    {
        $fields = self::consumerFields();
        $fields[] = ["name" => "sales_label",          "label" => __("Sales")];
        return $fields;
    }

    static public function companyFields($user)
    {
        $bankOptions = [];
        foreach (config("banks") as $code => $name) {
            $bankOptions[] = ["value" => $code, "label" => $name];
        }
        return [
            ["icon" => "app",           "name" => "company_type_label",     "label" =>  "公司类型", "disabled" => true, "defaultValue" => ($user->challenge_type_label ?? null)],
            ["icon" => "gesture-click", "name" => "execute_partner",  "label" =>  "执行合伙人", "disabled" => true, "defaultValue" => config("city-partner.execute_partner")],
            // ["icon" => "app",           "name" => "partner_role",     "label" =>  "合伙人身份", "required" => true, "type" => "checkbox", "options"=>$roleOptions, "defaultValue" => Company::COMMON_PARTNER],
            ["icon" => "star",          "name" => "partner_role_label",     "label" =>  "合伙人身份", "disabled" => true, "defaultValue" => Company::partnerRoleOptions()[Company::COMMON_PARTNER]],
            ["icon" => "info-circle",   "name" => "company_name",     "label" =>  "公司名称", "required" => true],
            ["icon" => "data-display",  "name" => "credit_code",      "label" =>  "信用代码"],
            ["icon" => "user-marked",   "name" => "legal_person_name","label" =>  "法人", "required" => true],
            ["icon" => "calendar-edit", "name" => "registered_at",    "label" =>  "注册日期", "type" => "date"],
            // ["icon" => "cooperate",     "name" => "partner_years",    "label" =>  "合伙年限"],
            // ["icon" => "calendar-2",    "name" => "partner_start_at", "label" =>  "合伙开始日期", "type" => "date"],
            // ["icon" => "calendar-event", "name" => "partner_end_at",  "label" =>  "合伙结束日期", "type" => "date"],
            ["icon" => "institution-checked", "name" => "bank",       "label" =>  "开户银行", "type" => "picker", "options" => $bankOptions, "display_field" => "bank_label"],
            ["icon" => "institution",   "name" => "sub_bank",         "label" =>  "支行"],
            ["icon" => "verify",        "name" => "account_name",     "label" =>  "账户名称"],
            ["icon" => "data-display",  "name" => "account_no",     "label" =>  "账号"],
        ];
    }

    static public function partnerStatsFields()
    {
        return [
            ["icon" => "data-display",  "name" => "register_consumers", "label" =>  ___('register_consumers')],
            ["icon" => "data-display",  "name" => "partner_consumers",  "label" =>  ___('partner_consumers')],
            ["icon" => "data-display",  "name" => "challenge_consumers","label" =>  ___('challenge_consumers')],
        ];
    }
}

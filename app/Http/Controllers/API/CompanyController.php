<?php

namespace App\Http\Controllers\API;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Challenge;
use App\Models\User;
use App\Helpers\ChallengeHelper;
use App\Models\Company;
use Log;

class CompanyController extends ApiBaseController
{
    public function store(Request $request)
    {
        $input = $request->all();
        $input['company_type'] = $this->user->challenge_type;
        $input['partner_role'] = Company::COMMON_PARTNER;
        \Log::debug($input);
        // check legal person
        if ($input['legal_person_name'] != $this->user->name) {
            return $this->sendError("只能提交本人为法人的企业！");
        }
        $input['legal_person_id'] = $this->user->id;
        $input = array_filter($input);
        \Log::debug($input);
        if (!$company = $this->user->company){
            $company = Company::create($input);
        }else{
            $company->update($input);
        }
        return $this->sendResponse($company->info());
    }

    public function partnerAsset($id, Request $request)
    {
        $data = $request->all();
        if ($company = $this->user->partnerCompanies()->find($id)) {
            $this->user->partnerCompanies()->updateExistingPivot($id, $data);
        }else{
            $this->user->partnerCompanies()->attach($id, $data);
        }

        return $this->sendResponse($data);
    }
}

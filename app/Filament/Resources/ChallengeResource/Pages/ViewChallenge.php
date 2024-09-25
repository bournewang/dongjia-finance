<?php

namespace App\Filament\Resources\ChallengeResource\Pages;

use App\Filament\Resources\ChallengeResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms\Components\TextInput;
use App\Models\Challenge;
use App\Models\User;
use App\Models\Company;
use App\Helpers\UserHelper;

class ViewChallenge extends ViewRecord
{
    protected static string $resource = ChallengeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
            Actions\Action::make('Approve')
                ->translateLabel()
                ->visible(fn (): bool => $this->getRecord()->status == Challenge::APPLYING)
                ->color("success")
                ->action(function (array $data, Challenge $record) {
                    // $record = $this->getRecord();
                    if ($record->status == Challenge::APPLYING) {
                        $record->user->update(["level" => User::CONSUMER_MERCHANT]);
                        $record->update(['status' => Challenge::CHALLENGING, 'reason' => null]);
                        // create a company
                        Company::create([
                            "company_type" => $record->type,
                            "execute_partner" => config("city-partner.execute_partner"),
                            "partner_role" => Company::COMMON_PARTNER,
                            "legal_person_id" => $record->user_id,
                        ]);
                        $this->refreshFormData(['status']);
                    }
                }),
            Actions\Action::make('Reject')
                ->translateLabel()
                ->visible(fn (): bool => $this->getRecord()->status == Challenge::APPLYING)
                ->color("danger")
                ->form([
                    TextInput::make('reason')->translateLabel()->required(),
                ])
                ->action(function (array $data, Challenge $record) {
                    $record->update(['status' => Challenge::REJECTED, 'reason' => $data['reason'] ?? null]);
                }),

        ];
    }
}

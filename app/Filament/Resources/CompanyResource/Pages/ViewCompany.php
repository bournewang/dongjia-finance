<?php

namespace App\Filament\Resources\CompanyResource\Pages;

use App\Filament\Resources\CompanyResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Models\Company;
use App\Helpers\UserHelper;

class ViewCompany extends ViewRecord
{
    protected static string $resource = CompanyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
            // Actions\Action::make('Confirm')
            //     ->translateLabel()
            //     ->visible(fn (): bool => !!$this->getRecord()->id)
            //     ->action(function () {
            //         $record = $this->getRecord();
            //         if ($record->status == Company::APPLYING) {
            //             $record->update(['status' => Company::CHALLENGING]);
            //             $this->refreshFormData(['status']);
            //             // UserHelper::createQrCode($record->user);
            //         }
            //     })
        ];
    }
}

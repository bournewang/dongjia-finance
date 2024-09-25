<?php

namespace App\Filament\Resources\CrowdFundingResource\Pages;

use App\Filament\Resources\CrowdFundingResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Models\CrowdFunding;
use App\Helpers\UserHelper;

class ViewCrowdFunding extends ViewRecord
{
    protected static string $resource = CrowdFundingResource::class;

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         // Actions\DeleteAction::make(),
    //         Actions\Action::make('Confirm')
    //             ->translateLabel()
    //             ->visible(fn (): bool => $this->getRecord()->status == CrowdFunding::APPLYING)
    //             ->action(function () {
    //                 $record = $this->getRecord();
    //                 if ($record->status == CrowdFunding::APPLYING) {
    //                     $record->update(['status' => CrowdFunding::CHALLENGING]);
    //                     $this->refreshFormData(['status']);
    //                     UserHelper::createQrCode($record->user);
    //                 }
    //             })
    //     ];
    // }
}

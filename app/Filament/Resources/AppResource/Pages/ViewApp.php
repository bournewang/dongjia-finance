<?php

namespace App\Filament\Resources\AppResource\Pages;

use App\Filament\Resources\AppResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Models\App;
use App\Helpers\UserHelper;

class ViewApp extends ViewRecord
{
    protected static string $resource = AppResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
            // Actions\Action::make('Confirm')
            //     ->translateLabel()
            //     ->visible(fn (): bool => $this->getRecord()->status == App::APPLYING)
            //     ->action(function () {
            //         $record = $this->getRecord();
            //         if ($record->status == App::APPLYING) {
            //             $record->update(['status' => App::CHALLENGING]);
            //             $this->refreshFormData(['status']);
            //             // UserHelper::createQrCode($record->user);
            //         }
            //     })
        ];
    }
}

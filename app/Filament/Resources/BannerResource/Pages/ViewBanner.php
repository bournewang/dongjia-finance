<?php

namespace App\Filament\Resources\BannerResource\Pages;

use App\Filament\Resources\BannerResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBanner extends ViewRecord
{
    protected static string $resource = BannerResource::class;

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

<?php

namespace App\Filament\Resources\AgentResource\Pages;

use App\Filament\Resources\AgentResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Models\Agent;
use App\Helpers\UserHelper;

class ViewAgent extends ViewRecord
{
    protected static string $resource = AgentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
            Actions\Action::make('Approve')
                ->translateLabel()
                // ->visible(fn (): bool => $this->getRecord()->status == Agent::APPLYING)
                ->action(function () {
                    $record = $this->getRecord();
                    // if ($record->status == Agent::APPLYING) {
                    $record->update(['status' => Agent::APPROVED]);
                    $this->refreshFormData(['status']);
                }),
            Actions\Action::make('Reject')
                ->translateLabel()
                // ->visible(fn (): bool => $this->getRecord()->status == Agent::APPLYING)
                ->action(function () {
                    $record = $this->getRecord();
                    // if ($record->status == Agent::APPLYING) {
                    $record->update(['status' => Agent::REJECTED]);
                    $this->refreshFormData(['status']);
                    // }
                }),
        ];
    }
}

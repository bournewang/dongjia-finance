<?php

namespace App\Filament\Resources\CrowdFundingResource\Pages;

use App\Filament\Resources\CrowdFundingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCrowdFundings extends ListRecords
{
    protected static string $resource = CrowdFundingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\ChallengeResource\Pages;

use App\Filament\Resources\ChallengeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Challenge;
use App\Models\User;
use App\Filament\LevelTab;

class ListChallenges extends ListRecords
{
    protected static string $resource = ChallengeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $tabs = [
            __('All') => Tab::make(),
            __('Applying') => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', Challenge::APPLYING)),
            __('Challenging') => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', Challenge::CHALLENGING)),
            __('Challenge Success') => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', Challenge::SUCCESS)),
            __('Canceled') => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', Challenge::CANCELED)),
        ];

        // $tabs = [
        //     __('All') => Tab::make()
        // ];

        foreach (User::levelOptions() as $level => $label) {
            if ($level < User::COMMUNITY_STATION) continue;
            $tabs[$label] = LevelTab::makeTab($level);
        }
        return $tabs;
    }
}

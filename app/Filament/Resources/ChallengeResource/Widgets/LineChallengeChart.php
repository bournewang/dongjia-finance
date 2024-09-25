<?php

namespace App\Filament\Resources\ChallengeResource\Widgets;

use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use App\Models\Challenge;
use App\Models\User;
class LineChallengeChart extends ChartWidget
{
    protected static ?string $heading = '挑战';
    public ?string $filter = 'week';

    protected function getData(): array
    {
        $activeFilter = $this->filter;
        $subDays = 7;
        if ($activeFilter == 'month'){
            $subDays = 30;
        } elseif ($activeFilter == 'season'){
            $subDays = 91;
        }
        // $users = Trend::model(User::class)
        //     ->between(
        //         start: today()->subDays($subDays),
        //         end: today()->subDay(1)->endOfDay(),
        //     )
        //     ->perDay()
        //     ->count();
        $challenge = Trend::model(Challenge::class)
            ->between(
                start: today()->subDays($subDays),
                end: today()->subDay(1)->endOfDay(),
            )
            ->perDay()
            ->count();
        $success = Trend::model(Challenge::class)
            ->dateColumn("success_at")
            ->between(
                start: today()->subDays($subDays),
                end: today()->subDay(1)->endOfDay(),
            )
            ->perDay()
            ->count();
        // \Log::debug($data);

        return [
            'datasets' => [
                // [
                //     'label' => __('User'),
                //     'data' => $users->map(fn (TrendValue $value) => $value->aggregate),
                // ],
                [
                    'label' => __('Challenge'),
                    // 'borderColor' => 'gray',
                    'data' => $challenge->map(fn (TrendValue $value) => $value->aggregate),
                ],
                [
                    'label' => __('Challenge Success'),
                    // 'backgroundColor' => 'lightgreen',
                    'borderColor' => 'lightgreen',
                    'data' => $success->map(fn (TrendValue $value) => $value->aggregate),
                ]
            ],
            'labels' => $challenge->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getFilters(): ?array
    {
        return [
            // 'today' => 'Today',
            'week' => __('Last week'),
            'month' => __('Last month'),
            'season' => __('Last season'),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}

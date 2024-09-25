<?php

namespace App\Filament\Resources\UserResource\Widgets;

use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use App\Models\User;

class LineUsersChart extends ChartWidget
{
    protected static ?string $heading = "用户";
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
        $data = Trend::query(User::where('level', '>', User::NONE_REGISTER))
            ->between(
                start: today()->subDays($subDays),
                end: today()->subDay(1)->endOfDay(),
            )
            ->perDay()
            ->count();
        // \Log::debug($data);

        return [
            'datasets' => [
                [
                    'label' => __('User'),
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
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

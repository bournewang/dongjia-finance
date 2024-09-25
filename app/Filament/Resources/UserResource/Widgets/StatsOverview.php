<?php

namespace App\Filament\Resources\UserResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use App\Models\User;
use App\Models\Challenge;
use DB;
class StatsOverview extends BaseWidget
{
    protected function getTrendData($builder, $dateColumn = 'created_at')
    {
        $origin = $builder->whereBetween($dateColumn, [today()->subDays(7), today()->subDay(1)->endOfDay()])
            ->select(DB::raw("count(id) as total"), DB::raw("date($dateColumn) as calc_date"))
            ->groupBy('calc_date')
            ->orderBy('calc_date', 'asc')
            ->pluck('total', 'calc_date')
            ->toArray();
        $start = today()->subDays(7);
        $i = 0;
        $data = [];
        $day = $start->toDateString();
        $data[$day] = $origin[$day] ?? 0;
        while($i < 6) {
            $day = $start->addDays(1)->toDateString();
            $data[$day] = $origin[$day] ?? 0;
            // echo  . "\n";
            $i++;
        }
        $view_data = array_values($data);
        if ($view_data[6] > $view_data[5]){
            $trend = "up";
        }else{
            $trend = "down";
        }
        $diff = $this->views_show(abs($view_data[6] - $view_data[5]));

        return [$diff, $trend, $view_data];
    }

    protected function views_show($num)
    {
        if($num < 1000) {
            return $num;
        }else if($num >=1000 && $num < 10000){
            return round($num/1000,1).'k';
        } else if ($num >= 10000) {
            return round($num/10000,2).'w';
        }
    }

    protected function getStats(): array
    {
        [$diff1, $trend1, $register_data]  = $this->getTrendData(User::where('level', '>', User::NONE_REGISTER));
        [$diff2, $trend2, $challenge_data] = $this->getTrendData(Challenge::whereIn('status', [Challenge::APPLYING, Challenge::CHALLENGING, Challenge::SUCCESS]));
        [$diff3, $trend3, $success_data]   = $this->getTrendData(Challenge::where('status', Challenge::SUCCESS));

        // \Log::debug($register_data);
        // \Log::debug($challenge_data);
        // \Log::debug($success_data);
        return [
            Stat::make(__('Register Users'), $this->views_show($register_data[6]))
                ->description(__($trend1 == 'up' ? 'Increase' : 'Decrease').$diff1)
                ->descriptionIcon('heroicon-m-arrow-trending-'.$trend1)
                ->chart($register_data)
                ->color($trend1 == 'up' ? 'success' : 'danger'),
            Stat::make(__('Challenge Users'), $this->views_show($challenge_data[6]))
                ->description(__($trend2 == 'up' ? 'Increase' : 'Decrease').$diff2)
                ->descriptionIcon('heroicon-m-arrow-trending-'.$trend2)
                ->chart($challenge_data)
                ->color($trend2 == 'up' ? 'success' : 'danger'),
            Stat::make(__('Challenge Success'), $this->views_show($success_data[6]))
                ->description(__($trend3 == 'up' ? 'Increase' : 'Decrease').$diff3)
                ->descriptionIcon('heroicon-m-arrow-trending-'.$trend3)
                ->chart($success_data)
                ->color($trend3 == 'up' ? 'success' : 'danger'),
        ];
    }
}

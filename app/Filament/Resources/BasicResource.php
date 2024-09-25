<?php
namespace App\Filament\Resources;

use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;

class BasicResource {

    static function dateRangeFilter($field = 'created_at')
    {
        return
        Filter::make('created_at')->translateLabel()
        ->form([
            DatePicker::make('created_from')->translateLabel(),
            DatePicker::make('created_until')->translateLabel(),
        ])
        ->query(function (Builder $query, array $data): Builder {
            return $query
            ->when($data['created_from'], fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date))
            ->when($data['created_until'], fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date));
        });
    }
}

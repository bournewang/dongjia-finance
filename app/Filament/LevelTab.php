<?php
namespace App\Filament;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User;

class LevelTab
{
    static public function makeTab($level)
    {
        return Tab::make()->modifyQueryUsing(fn (Builder $query) => $query->where('level', $level));
    }
}

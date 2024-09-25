<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Order;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            __('All') => Tab::make(),
            __('Unpaid') => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', Order::CREATED)),
            __('Paid') => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', Order::PAID)),
            __('Canceled') => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', Order::CANCELED)),
            __('Refunded') => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', Order::REFUNDED)),
        ];
    }
}

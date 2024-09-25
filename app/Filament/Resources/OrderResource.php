<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\User;

// hide order temporarily
// class OrderResource extends Resource
class OrderResource
{
    protected static ?string $model = Order::class;
    protected static ?string $label = "订单";

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Select::make('user_id')
                    ->label('User')
                    ->options(User::all()->pluck('name', 'id'))
                    ->translateLabel()
                    ->searchable(),
                TextInput::make('order_no')->translateLabel(),
                TextInput::make('amount')->translateLabel(),
                TextInput::make('paid_at')->translateLabel(),
                TextInput::make('refund_at')->translateLabel(),
                Select::make('status')
                    ->translateLabel()
                    ->options(Order::statusOptions())
            ]);
    }
    // 'user_id',
    // 'order_no',
    // 'amount',
    // 'status',
    // 'paid_at',
    // 'refund_at'

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make("user.name")->translateLabel()->searchable(),
                TextColumn::make("order_no")->translateLabel()->searchable(),
                TextColumn::make("amount")->translateLabel(),
                // TextColumn::make("status")->translateLabel(),
                ViewColumn::make('status')->translateLabel()
                    ->view('filament.tables.columns.order-status'),
                TextColumn::make("paid_at")->translateLabel(),
                TextColumn::make("refund_at")->translateLabel(),
            ])
            ->filters([
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')->translateLabel(),
                        DatePicker::make('created_until')->translateLabel(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['created_from'], fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date))
                            ->when($data['created_until'], fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date));
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Contracts\View\View;
use Filament\Infolists\Components\Section;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $label = "用户";

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Select::make('referer_id')->label("Referer")
                    ->translateLabel()
                    ->options(User::whereNotNull("name")->pluck('name', 'id'))
                    ->nullable()
                    ->searchable(),
                TextInput::make('name')->translateLabel(),
                TextInput::make('nickname')->translateLabel(),
                TextInput::make('mobile')->translateLabel(),
                // TextInput::make('status')->translateLabel(),
                // TextInput::make('level')->translateLabel(),
                // Select::make('level')
                //     ->translateLabel()
                //     ->options(User::levelOptions())

                SpatieMediaLibraryFileUpload::make('id_card_front')->translateLabel()->collection('id_card_front')->label('ID Front'),
                SpatieMediaLibraryFileUpload::make('id_card_end')->translateLabel()->collection('id_card_end')->label('ID End'),
                SpatieMediaLibraryFileUpload::make('pay_receipt_challenge')->translateLabel()->collection('pay_receipt_challenge')->label("征召授职付款凭证"),
                SpatieMediaLibraryFileUpload::make('pay_receipt_funding')->translateLabel()->collection('pay_receipt_funding')->label("众筹付款凭证"),
                SpatieMediaLibraryFileUpload::make('pay_receipt_consumer')->translateLabel()->collection('pay_receipt_consumer')->label("入伙实缴付款凭证"),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make("id")->translateLabel()->searchable(),
                // TextColumn::make("platform_openid")->translateLabel()->searchable(),
                ImageColumn::make('avatar')->translateLabel()->circular()
                    ->defaultImageUrl(url('/images/default-avatar.png')),
                TextColumn::make("name")->translateLabel()->searchable(),
                TextColumn::make("nickname")->translateLabel()->searchable(),
                // TextColumn::make("email")->translateLabel()->searchable()
                TextColumn::make("mobile")->translateLabel()->searchable(),
                ToggleColumn::make("status")->translateLabel(),
                // TextColumn::make("level")->translateLabel(),
                ViewColumn::make('level')->translateLabel()
                    ->view('filament.tables.columns.user-level'),
                TextColumn::make("referer.name")->translateLabel(),
                TextColumn::make("recommends_count")->translateLabel()->counts('recommends'),
                ToggleColumn::make("is_union_founder")->translateLabel(),
                TextColumn::make("created_at")->translateLabel() //label(__("Created At")),
            ])
            ->defaultSort("id", "desc")
            ->filters([
                //
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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            // ->bulkActions([
            //     Tables\Actions\BulkActionGroup::make([
            //         Tables\Actions\DeleteBulkAction::make(),
            //     ]),
            // ])
            ;
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make()
                ->columns(3)
                ->schema([
                    // TextEntry::make("openid")->translateLabel(),
                    // TextEntry::make("platform_openid")->translateLabel(),
                    TextEntry::make("name")->translateLabel(),
                    TextEntry::make("nickname")->translateLabel(),
                    // TextColumn::make("email")->translateLabel()->searchable()
                    TextEntry::make("mobile")->translateLabel(),
                    // TextEntry::make("status")->translateLabel(),
                    // TextEntry::make("level")->translateLabel(),
                    TextEntry::make("level")->translateLabel()
                        ->formatStateUsing(fn (string $state): View =>
                        view('filament.infolists.components.user-level', ['state' => $state])),
                    TextEntry::make("referer.name")->translateLabel(),
                    TextEntry::make("created_at")->translateLabel(), //label("Created At"),
                    SpatieMediaLibraryImageEntry::make('id_card_front')->translateLabel()->collection('id_card_front')->label('ID Front'),
                    SpatieMediaLibraryImageEntry::make('id_card_end')->translateLabel()->collection('id_card_end')->label('ID End'),
                    SpatieMediaLibraryImageEntry::make('pay_receipt_challenge')->translateLabel()->collection('pay_receipt_challenge')->label("征召授职付款凭证"),
                    SpatieMediaLibraryImageEntry::make('pay_receipt_funding')->translateLabel()->collection('pay_receipt_funding')->label("众筹付款凭证"),
                    SpatieMediaLibraryImageEntry::make('pay_receipt_consumer')->translateLabel()->collection('pay_receipt_consumer')->label("入伙实缴付款凭证"),
                ])
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\Viewuser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}

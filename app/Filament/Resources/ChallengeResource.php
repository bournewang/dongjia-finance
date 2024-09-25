<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChallengeResource\Pages;
use App\Filament\Resources\ChallengeResource\RelationManagers;
use App\Models\Challenge;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Contracts\View\View;
use Filament\Infolists\Components\Section;

class ChallengeResource extends Resource
{
    protected static ?string $model = Challenge::class;
    protected static ?string $label = "征召";

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                // TextInput::make('name'),
                Select::make('user_id')
                    ->translateLabel()
                    ->options(User::whereNotNull("name")->pluck('name', 'id'))
                    ->searchable(),
                // TextInput::make('index_no')->translateLabel(),
                // TextInput::make('level')->translateLabel(),
                Select::make('type')->translateLabel()
                        ->options(Challenge::typeOptions()),
                Select::make("partner_role")->multiple()->options(\App\Models\Company::partnerRoleOptions())->translateLabel(),
                Select::make('level')
                    ->translateLabel()
                    ->options(User::levelOptions()),
                TextInput::make('success_at')->translateLabel(),
                // TextInput::make('status'),
                Select::make('status')
                    ->translateLabel()
                    ->options(Challenge::statusOptions())
                    // ->searchable(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make()
                ->columns(3)
                ->schema([
                    TextEntry::make("user_id")->label('User ID')->translateLabel(),
                    TextEntry::make("user_id")->label('Name')->translateLabel()
                        ->formatStateUsing(fn (string $state): View =>
                        view('filament.infolists.components.user-displayname',['state' => $state])),
                    TextEntry::make("user.mobile")->label('Mobile')->translateLabel(),
                    // ImageEntry::make("user.avatar")->label('Avatar')->translateLabel()->circular(),
                    SpatieMediaLibraryImageEntry::make('user.id_card_front')->translateLabel()->collection('id_card_front')->label('ID Front'),
                    SpatieMediaLibraryImageEntry::make('user.id_card_end')->translateLabel()->collection('id_card_end')->label('ID End'),
                    SpatieMediaLibraryImageEntry::make('user.pay_receipt_funding')->translateLabel()->collection('pay_receipt_challenge')->label('Pay Receipt'),

                    TextEntry::make("type")->label("Challenge Type")->translateLabel()
                        ->formatStateUsing(fn (string $state): View =>
                        view('filament.infolists.components.challenge-type', ['state' => $state])),
                    TextEntry::make("partner_role")->translateLabel()
                        ->formatStateUsing(fn (string $state): View =>
                        view('filament.infolists.components.partner-roles', ['state' => $state])),
                    TextEntry::make("level")->translateLabel(),
                    TextEntry::make("success_at")->translateLabel(),
                    TextEntry::make("created_at")->translateLabel(),
                    TextEntry::make("status")->translateLabel()
                        ->formatStateUsing(fn (string $state): View =>
                        view('filament.infolists.components.challenge-status', ['state' => $state])),
                    TextEntry::make("reason")->translateLabel()
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make("id")->translateLabel()->searchable(),
                ImageColumn::make("user.avatar")->label("Avatar")->translateLabel()->circular()
                    ->defaultImageUrl(url("/images/default-avatar.png")),
                TextColumn::make("user.name")->label('User')->translateLabel()->searchable(),
                TextColumn::make("user.mobile")->label('Mobile')->translateLabel()->searchable(),

                // TextColumn::make("index_no")->translateLabel()->searchable(),
                // TextColumn::make("level")->translateLabel()->searchable(),
                ViewColumn::make('level')->translateLabel()
                    ->view('filament.tables.columns.user-level'),
                ViewColumn::make('type')->translateLabel()
                        ->view('filament.tables.columns.challenge-type'),
                TextColumn::make("success_at")->translateLabel()->searchable(),
                // TextColumn::make("status")->translateLabel()->searchable(),
                TextColumn::make("created_at")->translateLabel(),
                ViewColumn::make('status')->translateLabel()
                    ->view('filament.tables.columns.challenge-status'),
                TextColumn::make("reason")->translateLabel(),
            ])
            ->defaultSort("id", "desc")
            ->filters([
                BasicResource::dateRangeFilter()
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->translateLabel(),
                Tables\Actions\EditAction::make()->translateLabel(),
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
            'index' => Pages\ListChallenges::route('/'),
            // 'create' => Pages\CreateChallenge::route('/create'),
            'edit' => Pages\EditChallenge::route('/{record}/edit'),
            'view' => Pages\ViewChallenge::route('/{record}'),
        ];
    }
}

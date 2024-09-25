<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AgentResource\Pages;
use App\Filament\Resources\AgentResource\RelationManagers;
use App\Models\Agent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Contracts\View\View;
use App\Models\User;

class AgentResource extends Resource
{
    protected static ?string $model = Agent::class;
    protected static ?string $label = "代理";

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->translateLabel()
                    ->options(User::all()->pluck('name', 'id'))
                    ->searchable(),
                Select::make('status')
                    ->translateLabel()
                    ->options(Agent::statusOptions())

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("id")->translateLabel()->searchable(),
                ImageColumn::make("user.avatar")->label("Avatar")->translateLabel()->circular()
                    ->defaultImageUrl(url("/images/default-avatar.png")),
                TextColumn::make("user_id")->label('User')->translateLabel()
                    ->view('filament.tables.columns.user-displayname'),
                TextColumn::make("user.mobile")->label('Mobile')->translateLabel(),
                TextColumn::make("province_name")->label('Province')->translateLabel()->searchable(),
                TextColumn::make("city_name")->label('City')->translateLabel()->searchable(),
                TextColumn::make("county_name")->label('County')->translateLabel()->searchable(),
                TextColumn::make("created_at")->translateLabel(),
                ViewColumn::make('status')->translateLabel()
                    ->view('filament.tables.columns.agent-status'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
                    TextEntry::make("user.province_name")->label('Province')->translateLabel(),
                    TextEntry::make("user.city_name")->label('City')->translateLabel(),
                    TextEntry::make("user.county_name")->label('County')->translateLabel(),
                    TextEntry::make("user.street")->label('Street')->translateLabel(),

                    TextEntry::make("status")->translateLabel()
                        ->formatStateUsing(fn (string $state): View =>
                        view('filament.infolists.components.agent-status', ['state' => $state])),
                    // TextEntry::make("user.")->label('')->translateLabel(),
                    ImageEntry::make("user.avatar")->label('Avatar')->translateLabel()->circular(),//->columnSpan(2),
                    SpatieMediaLibraryImageEntry::make('user.id_card_front')->translateLabel()->collection('id_card_front')->label('ID Front'),
                    SpatieMediaLibraryImageEntry::make('user.id_card_end')->translateLabel()->collection('id_card_end')->label('ID End'),
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
            'index' => Pages\ListAgents::route('/'),
            'create' => Pages\CreateAgent::route('/create'),
            'view' => Pages\ViewAgent::route('/{record}'),
            'edit' => Pages\EditAgent::route('/{record}/edit'),
        ];
    }
}

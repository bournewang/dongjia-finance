<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BannerResource\Pages;
use App\Filament\Resources\BannerResource\RelationManagers;
use App\Models\Banner;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BannerResource extends Resource
{
    protected static ?string $model = Banner::class;
    protected static ?string $label = "轮播/广告位";
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                // TextInput::make('name')->label("App Name")->translateLabel(),
                Select::make('type')->options(\App\Models\Banner::typeOptions())->translateLabel(),
                Select::make('category')->options(\App\Models\Banner::categoryOptions())->translateLabel(),
                TextInput::make('ad_position')->translateLabel(),
                TextInput::make('height')->translateLabel(),
                TextInput::make('url')->label("Url/AppId")->translateLabel(),
                TextInput::make('sort')->translateLabel(),
                SpatieMediaLibraryFileUpload::make("attachments")->label("Image")->translateLabel()->collection("image")->conversion("preview"),
                Select::make('status')->options([0 => "Disabled", 1 => "Enabled", ])->translateLabel(),
            ]);
    }
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make()
                ->columns(3)
                ->schema([
                    // TextEntry::make("name")->label('App Name')->translateLabel(),
                    TextEntry::make("type")->translateLabel(),
                    TextEntry::make("category")->translateLabel(),
                    TextEntry::make("ad_position")->translateLabel(),
                    TextEntry::make("height")->translateLabel(),
                    TextEntry::make("url")->label('Url/AppId')->translateLabel(),
                    TextEntry::make("sort")->translateLabel(),
                    SpatieMediaLibraryImageEntry::make('attachments')->label("Image")->translateLabel()->collection('image'),

                    TextEntry::make("created_at")->translateLabel(),
                ])
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // TextColumn::make("name")->label("App Name")->translateLabel(),
                TextColumn::make("type")->translateLabel(),
                TextColumn::make("category")->translateLabel(),
                TextColumn::make("ad_position")->translateLabel(),
                TextColumn::make("url")->label("Url/AppId")->translateLabel(),
                TextColumn::make("sort")->translateLabel(),
                ToggleColumn::make("status")->translateLabel(),
                TextColumn::make("created_at")->translateLabel(),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBanners::route('/'),
            'create' => Pages\CreateBanner::route('/create'),
            'view' => Pages\ViewBanner::route('/{record}'),
            'edit' => Pages\EditBanner::route('/{record}/edit'),
        ];
    }
}

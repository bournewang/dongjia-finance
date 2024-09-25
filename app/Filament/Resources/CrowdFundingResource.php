<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CrowdFundingResource\Pages;
use App\Filament\Resources\CrowdFundingResource\RelationManagers;
use App\Models\CrowdFunding;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Fieldset;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Contracts\View\View;
use Filament\Infolists\Components\Section;

function statusColor($state){
    return [
        // 'draft' => 'gray',
        // 'reviewing' => 'warning',
        // 'published' => 'success',
        // 'rejected' => 'danger',
        //
        //
        CrowdFunding::APPLYING  => 'gray',
        CrowdFunding::WAITING   => 'warning',
        CrowdFunding::USING     => 'success',
        CrowdFunding::COMPLETED => 'success',
        CrowdFunding::CANCELED  => 'danger'
        ][$state];
    }
    class CrowdFundingResource extends Resource
    {
        protected static ?string $model = CrowdFunding::class;
        protected static ?string $label = "众筹";

        protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

        public static function form(Form $form): Form
        {
            return $form
            ->schema([
                //
                TextInput::make("user_id")->translateLabel()->disabled(),
                // Select::make("user_id")->label('User')->translateLabel()->searchable()->options(\App\Models\User::all()->pluck('name', 'id')),
                Select::make("partner_role")->multiple()->options(\App\Models\Company::partnerRoleOptions())->translateLabel(),
                TextInput::make("paid_deposit")->translateLabel(),
                TextInput::make("using_period")->translateLabel(),

                DatePicker::make("start_at")->translateLabel(),
                DatePicker::make("end_at")->translateLabel(),
                DatePicker::make("returned_at")->translateLabel(),
                // TextColumn::make("status")->translateLabel()->searchable(),
                Select::make('status')->translateLabel()//->color(fn (string $state): string => statusColor($state))
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
                TextColumn::make("paid_deposit")->translateLabel()->searchable(),
                TextColumn::make("using_period")->translateLabel()->searchable(),

                TextColumn::make("start_at")->translateLabel()->searchable(),
                TextColumn::make("end_at")->translateLabel()->searchable(),
                TextColumn::make("returned_at")->translateLabel()->searchable(),
                // TextColumn::make("status")->translateLabel()->searchable(),
                ViewColumn::make('status')->translateLabel()//->color(fn (string $state): string => statusColor($state))
                ->view('filament.tables.columns.crowdfunding-status'),

            ])
            ->defaultSort("id", "desc")
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
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
                // Fieldset::make('Profile')->translateLabel()
                // ->schema([

                Section::make()
                ->columns(3)
                ->schema([
                    TextEntry::make("user_id")->label('User ID')->translateLabel(),
                    TextEntry::make("user_id")->label('Name')->translateLabel()
                    ->formatStateUsing(fn (string $state): View => view(
                        'filament.infolists.components.user-displayname',
                        ['state' => $state],
                    )),
                    TextEntry::make("user.mobile")->label('Mobile')->translateLabel(),
                    SpatieMediaLibraryImageEntry::make('user.id_card_front')->collection('id_card_front')->label('ID Front')->translateLabel(),
                    SpatieMediaLibraryImageEntry::make('user.id_card_end')->collection('id_card_end')->label('ID End')->translateLabel(),
                    SpatieMediaLibraryImageEntry::make('user.pay_receipt_funding')->collection('pay_receipt_funding')->label('Pay Receipt')->translateLabel(),
                    SpatieMediaLibraryImageEntry::make('user.driver_licence_front')->collection('driver_licence_front')->label('Driver Licence Front')->translateLabel(),
                    SpatieMediaLibraryImageEntry::make('user.driver_licence_end')->collection('driver_licence_end')->label('Driver Licence End')->translateLabel(),

                    // TextEntry::make("partner_role")->translateLabel(),
                    TextEntry::make("partner_role")->translateLabel()
                        ->formatStateUsing(fn (string $state): View =>
                        view('filament.infolists.components.partner-roles', ['state' => $state])),
                    TextEntry::make("paid_deposit")->translateLabel(),
                    TextEntry::make("using_period")->translateLabel(),
                    TextEntry::make("start_at")->translateLabel(),
                    TextEntry::make("end_at")->translateLabel(),
                    TextEntry::make("returned_at")->translateLabel(),
                    TextEntry::make("status")->translateLabel()
                        ->formatStateUsing(fn (string $state): View =>
                        view('filament.infolists.components.crowdfunding-status', ['state' => $state])),
                    TextEntry::make("comment")->translateLabel(),
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
                'index' => Pages\ListCrowdFundings::route('/'),
                'create' => Pages\CreateCrowdFunding::route('/create'),
                'edit' => Pages\EditCrowdFunding::route('/{record}/edit'),
                'view' => Pages\ViewCrowdFunding::route('/{record}'),
            ];
        }
    }

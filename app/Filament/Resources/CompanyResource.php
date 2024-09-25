<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyResource\Pages;
use App\Filament\Resources\CompanyResource\RelationManagers;
use App\Models\Company;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Contracts\View\View;
use App\Models\Challenge;
use App\Models\User;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;
    protected static ?string $label = "合伙企业";

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Select::make('company_type')->options(Challenge::typeOptions())->translateLabel(),
                TextInput::make("execute_partner")->translateLabel(),
                // TextColumn::make("partner_role")->translateLabel(),

                TextInput::make("company_name")->translateLabel(),
                TextInput::make("credit_code")->translateLabel(),
                // TextInput::make("legalPerson.name")->translateLabel(),
                Select::make('legal_person_id')->label("Legal person")
                    ->translateLabel()
                    ->options(User::all()->pluck('name', 'id'))
                    ->searchable(),
                DatePicker::make("registered_at")->translateLabel(),
                TextInput::make("partner_years")->translateLabel(),
                DatePicker::make("partner_start_at")->translateLabel(),
                DatePicker::make("partner_end_at")->translateLabel(),

                Select::make("bank")->options(config('banks'))->translateLabel(),
                TextInput::make("sub_bank")->translateLabel(),
                TextInput::make("account_name")->translateLabel(),
                TextInput::make("account_no")->translateLabel(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make()
                ->columns(3)
                ->schema([
                    // TextEntry::make("company_type")->translateLabel(),
                    TextEntry::make("company_type")->translateLabel()
                        ->formatStateUsing(fn (string $state): View =>
                        view('filament.infolists.components.challenge-type', ['state' => $state])),
                    TextEntry::make("execute_partner")->translateLabel(),
                    // TextEntry::make("partner_role")->translateLabel(),
                    // ViewEntry::make("partner_role")->translateLabel()
                    //     ->formatStateUsing(fn (string $state): View =>
                    //     view('filament.infolists.components.company-partner-role', ['state' => $state])),
                    TextEntry::make("partner_role")->translateLabel()
                        ->formatStateUsing(fn (string $state): View =>
                        view('filament.infolists.components.partner-roles', ['state' => $state])),

                    TextEntry::make("company_name")->translateLabel(),
                    TextEntry::make("credit_code")->translateLabel(),
                    TextEntry::make("legalPerson.name")->translateLabel(),
                    TextEntry::make("registered_at")->translateLabel(),
                    TextEntry::make("partner_years")->translateLabel(),
                    TextEntry::make("partner_start_at")->translateLabel(),
                    TextEntry::make("partner_end_at")->translateLabel(),
                    TextEntry::make("bank")->translateLabel(),
                    TextEntry::make("sub_bank")->translateLabel(),
                    TextEntry::make("account_name")->translateLabel(),
                    TextEntry::make("account_no")->translateLabel(),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("id")->translateLabel()->searchable(),
                // TextColumn::make("company_type")->translateLabel(),
                ViewColumn::make('company_type')->translateLabel()
                        ->view('filament.tables.columns.challenge-type'),
                TextColumn::make("execute_partner")->translateLabel(),
                // TextColumn::make("partner_role")->translateLabel(),

                TextColumn::make("company_name")->translateLabel(),
                TextColumn::make("credit_code")->translateLabel(),
                TextColumn::make("legalPerson.name")->translateLabel(),
                TextColumn::make("registered_at")->translateLabel(),
                TextColumn::make("partner_years")->translateLabel(),
                TextColumn::make("partner_start_at")->translateLabel(),
                TextColumn::make("partner_end_at")->translateLabel(),

                TextColumn::make("bank")->translateLabel(),
                TextColumn::make("sub_bank")->translateLabel(),
                TextColumn::make("account_name")->translateLabel(),
                TextColumn::make("account_no")->translateLabel(),

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
            'index' => Pages\ListCompanies::route('/'),
            'create' => Pages\CreateCompany::route('/create'),
            'view' => Pages\ViewCompany::route('/{record}'),
            'edit' => Pages\EditCompany::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources\TaxRules;

use App\Filament\Resources\TaxRules\Pages\CreateTaxRule;
use App\Filament\Resources\TaxRules\Pages\EditTaxRule;
use App\Filament\Resources\TaxRules\Pages\ListTaxRules;
use App\Filament\Resources\TaxRules\Schemas\TaxRuleForm;
use App\Filament\Resources\TaxRules\Tables\TaxRulesTable;
use App\Models\TaxRule;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TaxRuleResource extends Resource
{
    protected static ?string $model = TaxRule::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedScale;
    protected static ?string $navigationLabel = 'Tax Rules';

    public static function form(Schema $schema): Schema
    {
        return TaxRuleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TaxRulesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTaxRules::route('/'),
            'create' => CreateTaxRule::route('/create'),
            'edit' => EditTaxRule::route('/{record}/edit'),
        ];
    }
}

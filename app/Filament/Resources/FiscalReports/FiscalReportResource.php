<?php

namespace App\Filament\Resources\FiscalReports;

use App\Filament\Resources\FiscalReports\Pages\CreateFiscalReport;
use App\Filament\Resources\FiscalReports\Pages\EditFiscalReport;
use App\Filament\Resources\FiscalReports\Pages\ListFiscalReports;
use App\Filament\Resources\FiscalReports\Schemas\FiscalReportForm;
use App\Filament\Resources\FiscalReports\Tables\FiscalReportsTable;
use App\Models\FiscalReport;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FiscalReportResource extends Resource
{
    protected static ?string $model = FiscalReport::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;
    protected static ?string $navigationLabel = 'Fiscal Reports';

    public static function form(Schema $schema): Schema
    {
        return FiscalReportForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FiscalReportsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFiscalReports::route('/'),
            'create' => CreateFiscalReport::route('/create'),
            'edit' => EditFiscalReport::route('/{record}/edit'),
        ];
    }
}

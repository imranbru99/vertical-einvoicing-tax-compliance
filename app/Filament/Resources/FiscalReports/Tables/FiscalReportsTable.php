<?php

namespace App\Filament\Resources\FiscalReports\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class FiscalReportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('period_label')->searchable(),
                TextColumn::make('jurisdiction.country_code')->label('Country')->badge(),
                TextColumn::make('status')->badge(),
                TextColumn::make('invoice_count')->label('Invoices'),
                TextColumn::make('tax_collected')->label('Tax')->sortable(),
                TextColumn::make('period_end')->date()->sortable(),
            ])
            ->filters([])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}

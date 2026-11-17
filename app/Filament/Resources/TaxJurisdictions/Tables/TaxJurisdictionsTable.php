<?php

namespace App\Filament\Resources\TaxJurisdictions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class TaxJurisdictionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('country_code')->badge()->searchable(),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('currency'),
                TextColumn::make('default_scheme')->badge(),
                IconColumn::make('requires_digital_signature')->boolean()->label('Sign'),
                IconColumn::make('requires_clearance')->boolean()->label('Clearance'),
                IconColumn::make('is_active')->boolean()->label('Active'),
            ])
            ->filters([])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}

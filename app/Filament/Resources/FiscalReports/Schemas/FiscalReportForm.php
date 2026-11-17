<?php

namespace App\Filament\Resources\FiscalReports\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Schema;

class FiscalReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
                Select::make('tax_jurisdiction_id')->relationship('jurisdiction', 'name')->required()->searchable(),
                TextInput::make('period_label')->required(),
                DatePicker::make('period_start')->required(),
                DatePicker::make('period_end')->required(),
                Select::make('status')->options(\App\Enums\FiscalReportStatus::options())->required(),
                TextInput::make('invoice_count')->numeric(),
                TextInput::make('taxable_base')->numeric(),
                TextInput::make('tax_collected')->numeric(),
                TextInput::make('tax_payable')->numeric(),
                TextInput::make('file_path')->maxLength(255),
        ]);
    }
}

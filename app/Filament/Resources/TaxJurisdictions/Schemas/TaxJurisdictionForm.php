<?php

namespace App\Filament\Resources\TaxJurisdictions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Schema;

class TaxJurisdictionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
                TextInput::make('country_code')->required()->maxLength(2)->unique(ignoreRecord: true),
                TextInput::make('name')->required()->maxLength(150),
                TextInput::make('currency')->required()->maxLength(3)->default('USD'),
                TextInput::make('default_scheme')->required()->default('vat'),
                TextInput::make('authority_name')->maxLength(150),
                TextInput::make('einvoice_format')->maxLength(100),
                Toggle::make('requires_digital_signature')->default(false),
                Toggle::make('requires_clearance')->default(false),
                Toggle::make('is_active')->default(true),
        ]);
    }
}

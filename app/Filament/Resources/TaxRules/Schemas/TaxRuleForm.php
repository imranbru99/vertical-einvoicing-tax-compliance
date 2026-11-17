<?php

namespace App\Filament\Resources\TaxRules\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Schema;

class TaxRuleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
                Select::make('tax_jurisdiction_id')->relationship('jurisdiction', 'name')->required()->searchable(),
                TextInput::make('code')->required()->maxLength(50),
                TextInput::make('name')->required()->maxLength(150),
                Select::make('scheme')->options(\App\Enums\TaxScheme::options())->required(),
                TextInput::make('rate_percent')->numeric()->required()->suffix('%'),
                TextInput::make('category')->maxLength(80),
                Toggle::make('is_default')->default(false),
                Toggle::make('is_active')->default(true),
                DatePicker::make('effective_from'),
                DatePicker::make('effective_to'),
                Textarea::make('notes')->rows(2)->columnSpanFull(),
        ]);
    }
}

<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Schema;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
                TextInput::make('name')->required()->maxLength(150),
                TextInput::make('legal_name')->maxLength(200),
                TextInput::make('email')->email()->maxLength(150),
                TextInput::make('tax_id')->maxLength(80),
                TextInput::make('country_code')->maxLength(2),
                TextInput::make('address_line')->maxLength(200),
                TextInput::make('city')->maxLength(100),
                TextInput::make('postal_code')->maxLength(30),
                Toggle::make('portal_enabled')->default(true),
                Toggle::make('is_active')->default(true),
        ]);
    }
}

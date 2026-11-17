<?php

namespace App\Filament\Resources\AuditEvents\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Schema;

class AuditEventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
                Select::make('invoice_id')->relationship('invoice', 'number')->searchable(),
                Select::make('user_id')->relationship('user', 'name')->searchable(),
                TextInput::make('event_type')->required()->maxLength(80),
                TextInput::make('message')->required()->maxLength(255),
                TextInput::make('ip_address')->maxLength(45),
        ]);
    }
}

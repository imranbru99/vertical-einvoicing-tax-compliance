<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
                TextInput::make('name')->required()->maxLength(150),
                TextInput::make('email')->email()->required()->unique(ignoreRecord: true),
                TextInput::make('password')->password()->dehydrated(fn ($state) => filled($state))->required(fn (string $operation) => $operation === 'create'),
                Select::make('role')->options(\App\Enums\UserRole::options())->required(),
                TextInput::make('company')->maxLength(150),
                TextInput::make('job_title')->maxLength(150),
                Toggle::make('is_active')->default(true),
        ]);
    }
}

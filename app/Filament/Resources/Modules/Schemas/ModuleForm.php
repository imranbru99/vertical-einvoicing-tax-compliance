<?php

namespace App\Filament\Resources\Modules\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Schema;

class ModuleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
                TextInput::make('key')->required()->unique(ignoreRecord: true)->maxLength(100),
                TextInput::make('name')->required()->maxLength(150),
                TextInput::make('category')->required()->maxLength(80),
                Textarea::make('description')->rows(3)->columnSpanFull(),
                TextInput::make('icon')->maxLength(100),
                TextInput::make('sort_order')->numeric()->default(0),
                TextInput::make('estimated_hours_saved_weekly')->numeric()->default(0)->suffix('hrs/week'),
                Toggle::make('is_enabled')->default(true),
                Toggle::make('is_core')->default(false),
        ]);
    }
}

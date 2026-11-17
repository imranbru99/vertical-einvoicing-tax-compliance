<?php

namespace App\Filament\Resources\DigitalSignatures\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Schema;

class DigitalSignatureForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
                Select::make('invoice_id')->relationship('invoice', 'number')->required()->searchable(),
                Select::make('signed_by')->relationship('signer', 'name')->searchable(),
                Select::make('status')->options(\App\Enums\SignatureStatus::options())->required(),
                TextInput::make('algorithm')->default('RSA-SHA256'),
                TextInput::make('certificate_subject')->maxLength(200),
                TextInput::make('signature_hash')->maxLength(128),
        ]);
    }
}

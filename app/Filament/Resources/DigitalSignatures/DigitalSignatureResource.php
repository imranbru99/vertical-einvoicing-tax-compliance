<?php

namespace App\Filament\Resources\DigitalSignatures;

use App\Filament\Resources\DigitalSignatures\Pages\CreateDigitalSignature;
use App\Filament\Resources\DigitalSignatures\Pages\EditDigitalSignature;
use App\Filament\Resources\DigitalSignatures\Pages\ListDigitalSignatures;
use App\Filament\Resources\DigitalSignatures\Schemas\DigitalSignatureForm;
use App\Filament\Resources\DigitalSignatures\Tables\DigitalSignaturesTable;
use App\Models\DigitalSignature;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DigitalSignatureResource extends Resource
{
    protected static ?string $model = DigitalSignature::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFingerPrint;
    protected static ?string $navigationLabel = 'Signatures';

    public static function form(Schema $schema): Schema
    {
        return DigitalSignatureForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DigitalSignaturesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDigitalSignatures::route('/'),
            'create' => CreateDigitalSignature::route('/create'),
            'edit' => EditDigitalSignature::route('/{record}/edit'),
        ];
    }
}

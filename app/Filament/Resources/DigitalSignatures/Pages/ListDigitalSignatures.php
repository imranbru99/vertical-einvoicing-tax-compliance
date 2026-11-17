<?php

namespace App\Filament\Resources\DigitalSignatures\Pages;

use App\Filament\Resources\DigitalSignatures\DigitalSignatureResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDigitalSignatures extends ListRecords
{
    protected static string $resource = DigitalSignatureResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}

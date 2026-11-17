<?php

namespace App\Filament\Resources\AuditEvents\Pages;

use App\Filament\Resources\AuditEvents\AuditEventResource;
use Filament\Resources\Pages\EditRecord;

class EditAuditEvent extends EditRecord
{
    protected static string $resource = AuditEventResource::class;
}

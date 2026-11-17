<?php

namespace App\Filament\Resources\FiscalReports\Pages;

use App\Filament\Resources\FiscalReports\FiscalReportResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFiscalReports extends ListRecords
{
    protected static string $resource = FiscalReportResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}

<?php

namespace App\Enums;

enum FiscalReportStatus: string
{
    case Draft = 'draft';
    case Generating = 'generating';
    case Ready = 'ready';
    case Submitted = 'submitted';
    case Archived = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Generating => 'Generating',
            self::Ready => 'Ready',
            self::Submitted => 'Submitted',
            self::Archived => 'Archived',
        };
    }

    /** @return array<string, string> */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $s) => [$s->value => $s->label()])
            ->all();
    }
}

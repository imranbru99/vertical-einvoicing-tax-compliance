<?php

namespace App\Enums;

enum InvoiceStatus: string
{
    case Draft = 'draft';
    case Validated = 'validated';
    case Signed = 'signed';
    case Submitted = 'submitted';
    case Accepted = 'accepted';
    case Rejected = 'rejected';
    case Paid = 'paid';
    case Void = 'void';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Validated => 'Tax Validated',
            self::Signed => 'Digitally Signed',
            self::Submitted => 'Submitted to Authority',
            self::Accepted => 'Accepted',
            self::Rejected => 'Rejected',
            self::Paid => 'Paid',
            self::Void => 'Void',
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

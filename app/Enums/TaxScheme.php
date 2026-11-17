<?php

namespace App\Enums;

enum TaxScheme: string
{
    case Vat = 'vat';
    case Gst = 'gst';
    case SalesTax = 'sales_tax';
    case ReverseCharge = 'reverse_charge';
    case Exempt = 'exempt';
    case ZeroRated = 'zero_rated';

    public function label(): string
    {
        return match ($this) {
            self::Vat => 'VAT',
            self::Gst => 'GST',
            self::SalesTax => 'Sales Tax',
            self::ReverseCharge => 'Reverse Charge',
            self::Exempt => 'Exempt',
            self::ZeroRated => 'Zero-Rated',
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

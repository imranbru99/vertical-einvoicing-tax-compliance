<?php

namespace App\Enums;

enum SignatureStatus: string
{
    case Pending = 'pending';
    case Signed = 'signed';
    case Expired = 'expired';
    case Revoked = 'revoked';
    case Failed = 'failed';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Signed => 'Signed',
            self::Expired => 'Expired',
            self::Revoked => 'Revoked',
            self::Failed => 'Failed',
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

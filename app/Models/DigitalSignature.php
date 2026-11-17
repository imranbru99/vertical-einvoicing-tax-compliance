<?php

namespace App\Models;

use App\Enums\SignatureStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DigitalSignature extends Model
{
    protected $fillable = [
        'invoice_id',
        'signed_by',
        'status',
        'algorithm',
        'certificate_subject',
        'signature_hash',
        'signed_at',
        'expires_at',
        'payload',
    ];

    protected function casts(): array
    {
        return [
            'status' => SignatureStatus::class,
            'signed_at' => 'datetime',
            'expires_at' => 'datetime',
            'payload' => 'array',
        ];
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function signer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'signed_by');
    }
}

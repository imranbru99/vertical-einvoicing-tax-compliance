<?php

namespace App\Services;

use App\Enums\InvoiceStatus;
use App\Enums\SignatureStatus;
use App\Models\DigitalSignature;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Support\Str;

class SignatureService
{
    public function __construct(
        protected ModuleRegistry $modules,
        protected InvoiceService $invoices,
    ) {}

    public function sign(Invoice $invoice, User $signer, string $certificateSubject = 'CN=Vertical E-Invoicing Demo'): DigitalSignature
    {
        if (! $this->modules->isEnabled('digital_signatures')) {
            abort(403, 'Digital signatures module is disabled.');
        }

        $hash = hash('sha256', $invoice->number.'|'.$invoice->total.'|'.now()->toIso8601String().'|'.Str::random(16));

        $signature = DigitalSignature::query()->updateOrCreate(
            ['invoice_id' => $invoice->id],
            [
                'signed_by' => $signer->id,
                'status' => SignatureStatus::Signed,
                'algorithm' => 'RSA-SHA256',
                'certificate_subject' => $certificateSubject,
                'signature_hash' => $hash,
                'signed_at' => now(),
                'expires_at' => now()->addYear(),
                'payload' => [
                    'invoice_number' => $invoice->number,
                    'total' => (string) $invoice->total,
                    'currency' => $invoice->currency,
                ],
            ]
        );

        $invoice->update(['status' => InvoiceStatus::Signed]);
        $this->invoices->audit($invoice, $signer, 'invoice.signed', 'Invoice digitally signed', [
            'hash' => $hash,
        ]);

        return $signature;
    }
}

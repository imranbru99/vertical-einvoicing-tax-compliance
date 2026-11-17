<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fiscal_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tax_jurisdiction_id')->constrained()->cascadeOnDelete();
            $table->string('period_label');
            $table->date('period_start');
            $table->date('period_end');
            $table->string('status')->default('draft')->index();
            $table->unsignedInteger('invoice_count')->default(0);
            $table->decimal('taxable_base', 14, 2)->default(0);
            $table->decimal('tax_collected', 14, 2)->default(0);
            $table->decimal('tax_payable', 14, 2)->default(0);
            $table->string('file_path')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->json('summary')->nullable();
            $table->timestamps();
        });

        Schema::create('audit_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('event_type')->index();
            $table->string('message');
            $table->string('ip_address', 45)->nullable();
            $table->json('context')->nullable();
            $table->timestamps();
        });

        Schema::create('compliance_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('tax_jurisdiction_id')->nullable()->constrained()->nullOnDelete();
            $table->date('period_week');
            $table->decimal('hours_saved', 10, 2)->default(0);
            $table->unsignedInteger('invoices_processed')->default(0);
            $table->decimal('estimated_cost_saved', 14, 2)->default(0);
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compliance_metrics');
        Schema::dropIfExists('audit_events');
        Schema::dropIfExists('fiscal_reports');
    }
};

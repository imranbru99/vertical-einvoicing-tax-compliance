<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tax_jurisdictions', function (Blueprint $table) {
            $table->id();
            $table->string('country_code', 2)->unique();
            $table->string('name');
            $table->string('currency', 3)->default('USD');
            $table->string('default_scheme')->default('vat');
            $table->string('authority_name')->nullable();
            $table->boolean('requires_digital_signature')->default(false);
            $table->boolean('requires_clearance')->default(false);
            $table->boolean('is_active')->default(true);
            $table->string('einvoice_format')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('tax_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tax_jurisdiction_id')->constrained()->cascadeOnDelete();
            $table->string('code');
            $table->string('name');
            $table->string('scheme')->default('vat');
            $table->decimal('rate_percent', 8, 4)->default(0);
            $table->string('category')->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->date('effective_from')->nullable();
            $table->date('effective_to')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['tax_jurisdiction_id', 'code']);
        });

        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('legal_name')->nullable();
            $table->string('email')->nullable();
            $table->string('tax_id')->nullable()->index();
            $table->string('country_code', 2)->nullable();
            $table->string('address_line')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('portal_token')->nullable()->unique();
            $table->boolean('portal_enabled')->default(true);
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
        Schema::dropIfExists('tax_rules');
        Schema::dropIfExists('tax_jurisdictions');
    }
};

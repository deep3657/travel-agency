<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotation_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id')->constrained('quotations')->cascadeOnDelete();
            $table->unsignedInteger('version_number');
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('cgst', 12, 2)->default(0);
            $table->decimal('sgst', 12, 2)->default(0);
            $table->decimal('igst', 12, 2)->default(0);
            $table->decimal('grand_total', 12, 2)->default(0);
            $table->text('terms')->nullable();
            $table->text('customer_notes')->nullable();
            $table->string('pdf_path', 255)->nullable();
            $table->timestamp('sent_at')->nullable();

            $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by_id')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            $table->unique(['quotation_id', 'version_number']);
        });

        // Now add the FK from quotations.current_version_id → quotation_versions.id
        Schema::table('quotations', function (Blueprint $table) {
            $table->foreign('current_version_id')
                ->references('id')
                ->on('quotation_versions')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropForeign(['current_version_id']);
        });
        Schema::dropIfExists('quotation_versions');
    }
};

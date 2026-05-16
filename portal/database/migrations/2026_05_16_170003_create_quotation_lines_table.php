<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotation_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_version_id')->constrained('quotation_versions')->cascadeOnDelete();
            $table->string('line_type', 16)->default('other');
            $table->string('description', 500);
            $table->json('structured_data')->nullable();
            $table->foreignId('package_id')->nullable()->constrained('packages')->nullOnDelete();
            $table->decimal('quantity', 8, 2)->default(1);
            $table->decimal('unit_rate', 12, 2);
            $table->decimal('amount', 12, 2);
            $table->foreignId('vendor_id')->nullable()->constrained('vendors')->nullOnDelete();
            $table->decimal('purchase_cost', 12, 2)->nullable();
            $table->timestamps();

            $table->index('quotation_version_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotation_lines');
    }
};

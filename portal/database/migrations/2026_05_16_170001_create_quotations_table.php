<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->char('ulid', 26)->unique();
            $table->foreignId('trip_id')->constrained('trips')->cascadeOnDelete();
            // current_version_id — no FK here due to circular dependency with quotation_versions.
            // The constraint is added in a later migration once quotation_versions exists.
            $table->unsignedBigInteger('current_version_id')->nullable();
            $table->date('validity_date')->nullable();
            $table->string('status', 20)->default('draft');

            $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by_id')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            $table->index('trip_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};

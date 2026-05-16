<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->char('ulid', 26)->unique();
            $table->string('doc_type', 20);
            // booking_id FK will be added in M9 when bookings table exists.
            $table->unsignedBigInteger('booking_id')->nullable();
            $table->foreignId('quotation_version_id')->nullable()->constrained('quotation_versions')->nullOnDelete();
            $table->unsignedInteger('version_number')->default(1);
            $table->string('pdf_path', 255);
            $table->unsignedBigInteger('size_bytes')->default(0);
            $table->foreignId('generated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('generated_at')->useCurrent();
            $table->timestamps();

            $table->index('doc_type');
            $table->index('booking_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};

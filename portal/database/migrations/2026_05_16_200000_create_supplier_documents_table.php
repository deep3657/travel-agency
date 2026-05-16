<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supplier_documents', function (Blueprint $table) {
            $table->id();
            $table->char('ulid', 26)->unique();
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->nullOnDelete();
            $table->string('doc_type', 16);
            $table->string('supplier_name', 120)->nullable();
            $table->foreignId('supplier_vendor_id')->nullable()->constrained('vendors')->nullOnDelete();
            $table->string('original_filename', 255);
            $table->string('storage_path', 255);
            $table->string('mime', 80);
            $table->unsignedBigInteger('size_bytes');
            $table->char('sha256', 64);
            $table->string('extraction_mode', 8)->default('manual');
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('booking_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supplier_documents');
    }
};

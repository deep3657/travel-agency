<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('extraction_jobs', function (Blueprint $table) {
            $table->id();
            $table->char('ulid', 26)->unique();
            $table->foreignId('supplier_document_id')->constrained('supplier_documents')->cascadeOnDelete();
            $table->string('status', 16)->default('pending');
            $table->string('provider', 20)->nullable();
            $table->string('model', 60)->nullable();
            $table->timestamp('request_started_at')->nullable();
            $table->timestamp('request_completed_at')->nullable();
            $table->unsignedInteger('response_time_ms')->nullable();
            $table->unsignedInteger('prompt_tokens')->nullable();
            $table->unsignedInteger('completion_tokens')->nullable();
            $table->decimal('estimated_cost_inr', 8, 4)->nullable();
            $table->json('extracted_json')->nullable();
            $table->json('confidence_json')->nullable();
            $table->string('error_code', 40)->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index('supplier_document_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('extraction_jobs');
    }
};

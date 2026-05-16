<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->char('ulid', 26)->unique();
            $table->string('name', 120);
            $table->string('phone', 20)->unique();
            $table->string('alt_phone', 20)->nullable();
            $table->string('email', 190)->unique();
            $table->string('address_line1', 190)->nullable();
            $table->string('address_line2', 190)->nullable();
            $table->string('city', 80)->nullable();
            $table->string('state', 80)->nullable();
            $table->string('pincode', 10)->nullable();
            $table->string('country', 80)->default('India');
            $table->date('dob')->nullable();
            $table->date('anniversary')->nullable();
            $table->string('gstin', 15)->nullable();
            $table->string('company_name', 190)->nullable();
            $table->string('pan', 10)->nullable();
            $table->text('notes')->nullable();
            $table->json('tags')->nullable();

            // Author tracking (LLD §3 preamble: every mutable table carries created_by/updated_by).
            $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by_id')->nullable()->constrained('users')->nullOnDelete();

            $table->softDeletes();
            $table->timestamps();

            $table->index('phone', 'idx_customers_phone');
            $table->index('email', 'idx_customers_email');
            $table->index('gstin', 'idx_customers_gstin');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};

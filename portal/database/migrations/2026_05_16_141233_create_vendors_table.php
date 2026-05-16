<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->char('ulid', 26)->unique();
            $table->string('name', 190);
            $table->string('code', 20)->nullable()->unique();
            $table->string('contact_person', 120)->nullable();
            $table->string('email', 190)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('gstin', 15)->nullable();
            $table->text('address')->nullable();
            $table->unsignedInteger('payment_terms_days')->default(0);
            $table->text('notes')->nullable();

            // TracksAuthor
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->unsignedBigInteger('updated_by_id')->nullable();
            $table->foreign('created_by_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('updated_by_id')->references('id')->on('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};

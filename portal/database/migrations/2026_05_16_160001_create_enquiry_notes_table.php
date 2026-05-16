<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enquiry_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enquiry_id')->constrained('enquiries')->cascadeOnDelete();
            $table->foreignId('author_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('body');
            $table->timestamps();

            $table->index('enquiry_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enquiry_notes');
    }
};

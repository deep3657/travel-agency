<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('change_request_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('change_request_id')->constrained('change_requests')->cascadeOnDelete();
            $table->foreignId('author_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('body');
            $table->timestamps();

            $table->index('change_request_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('change_request_notes');
    }
};

<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('itinerary_days', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('packages')->cascadeOnDelete();
            $table->unsignedTinyInteger('day_number');
            $table->string('title', 190);
            $table->text('description')->nullable();
            $table->string('image_path', 255)->nullable();
            $table->timestamps();

            $table->index('package_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('itinerary_days');
    }
};

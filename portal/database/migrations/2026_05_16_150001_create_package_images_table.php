<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('package_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('packages')->cascadeOnDelete();
            $table->string('image_path', 255);
            $table->integer('sort_order')->default(0);
            $table->string('alt_text', 255)->nullable();
            $table->timestamps();

            $table->index('package_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('package_images');
    }
};

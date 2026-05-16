<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->char('ulid', 26)->unique();
            $table->string('slug', 180)->unique();
            $table->string('title', 190);
            $table->string('destinations', 255);
            $table->string('departure_city', 120)->nullable();
            $table->unsignedTinyInteger('duration_days')->default(1);
            $table->unsignedTinyInteger('duration_nights')->default(0);
            $table->decimal('price_from_inr', 12, 2);
            $table->string('hero_image_path', 255)->nullable();
            $table->string('short_description', 500)->nullable();
            $table->longText('long_description')->nullable();
            $table->json('highlights')->nullable();
            $table->json('inclusions')->nullable();
            $table->json('exclusions')->nullable();
            $table->longText('terms')->nullable();
            $table->json('category_tags')->nullable();
            $table->string('seo_meta_title', 190)->nullable();
            $table->string('seo_meta_description', 255)->nullable();
            $table->string('status', 16)->default('draft');
            $table->timestamp('published_at')->nullable();

            $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by_id')->nullable()->constrained('users')->nullOnDelete();

            $table->softDeletes();
            $table->timestamps();

            $table->index('status');
            $table->index('slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};

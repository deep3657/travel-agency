<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enquiries', function (Blueprint $table) {
            $table->id();
            $table->char('ulid', 26)->unique();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('assigned_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('enquiry_type', 16)->default('package');
            $table->date('travel_from')->nullable();
            $table->date('travel_to')->nullable();
            $table->string('origin', 120)->nullable();
            $table->string('destination', 120)->nullable();
            $table->unsignedTinyInteger('pax_adult')->default(1);
            $table->unsignedTinyInteger('pax_child')->default(0);
            $table->unsignedTinyInteger('pax_infant')->default(0);
            $table->decimal('budget_min', 12, 2)->nullable();
            $table->decimal('budget_max', 12, 2)->nullable();
            $table->text('special_requirements')->nullable();
            $table->string('status', 20)->default('new');
            $table->string('created_via', 20)->default('admin_entry');
            $table->string('source', 20)->nullable();
            $table->foreignId('package_id')->nullable()->constrained('packages')->nullOnDelete();
            $table->unsignedBigInteger('converted_to_trip_id')->nullable();

            $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by_id')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            $table->index('customer_id');
            $table->index('status');
            $table->index('assigned_user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enquiries');
    }
};

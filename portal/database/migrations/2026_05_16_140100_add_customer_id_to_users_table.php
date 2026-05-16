<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Adds the back-reference from users to customers (LLD §17 step 4).
     * Done as a separate migration because the users table is created before
     * the customers table to satisfy the FK created_by_id/updated_by_id on
     * customers itself.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('customer_id')
                ->nullable()
                ->after('user_type')
                ->constrained('customers')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('customer_id');
        });
    }
};

<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds the `created_by_id` / `updated_by_id` audit columns that the
 * SupplierDocument model expects via the TracksAuthor trait. Other tables
 * using TracksAuthor (trips, customers, etc.) already include these columns;
 * this brings supplier_documents in line.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('supplier_documents', function (Blueprint $table) {
            if (! Schema::hasColumn('supplier_documents', 'created_by_id')) {
                $table->foreignId('created_by_id')->nullable()->after('uploaded_by')->constrained('users')->nullOnDelete();
            }
            if (! Schema::hasColumn('supplier_documents', 'updated_by_id')) {
                $table->foreignId('updated_by_id')->nullable()->after('created_by_id')->constrained('users')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('supplier_documents', function (Blueprint $table) {
            if (Schema::hasColumn('supplier_documents', 'updated_by_id')) {
                $table->dropConstrainedForeignId('updated_by_id');
            }
            if (Schema::hasColumn('supplier_documents', 'created_by_id')) {
                $table->dropConstrainedForeignId('created_by_id');
            }
        });
    }
};

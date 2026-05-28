<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('passengers', function (Blueprint $table) {
            if (Schema::hasColumn('passengers', 'gender')) {
                $table->dropColumn('gender');
            }
        });
    }

    public function down(): void
    {
        Schema::table('passengers', function (Blueprint $table) {
            $table->string('gender', 10)->nullable()->after('title');
        });
    }
};

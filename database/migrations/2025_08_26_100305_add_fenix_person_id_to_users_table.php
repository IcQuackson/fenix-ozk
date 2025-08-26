<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add a nullable unique Fenix identifier for the person
            if (!Schema::hasColumn('users', 'fenix_person_id')) {
                $table->string('fenix_person_id')->nullable()->unique()->after('email');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'fenix_person_id')) {
                // Drop unique index then column (index name follows convention)
                $table->dropUnique(['fenix_person_id']);
                $table->dropColumn('fenix_person_id');
            }
        });
    }
};

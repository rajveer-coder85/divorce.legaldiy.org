<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('journey_vetting_submissions', function (Blueprint $table) {
            $table->string('access_slug', 80)->nullable()->unique()->after('reference');
            $table->timestamp('access_slug_created_at')->nullable()->after('access_slug');
        });
    }
    public function down(): void
    {
        Schema::table('journey_vetting_submissions', function (Blueprint $table) {
            $table->dropUnique(['access_slug']);
            $table->dropColumn(['access_slug', 'access_slug_created_at']);
        });
    }
};

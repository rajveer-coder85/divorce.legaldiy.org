<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('journey_vetting_submissions', fn (Blueprint $table) => $table->timestamp('access_enabled_at')->nullable()->after('access_slug_created_at'));
    }
    public function down(): void
    {
        Schema::table('journey_vetting_submissions', fn (Blueprint $table) => $table->dropColumn('access_enabled_at'));
    }
};

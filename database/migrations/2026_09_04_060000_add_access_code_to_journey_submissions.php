<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('journey_vetting_submissions', function (Blueprint $table) {
            $table->text('access_code')->nullable()->after('access_slug');
            $table->string('access_code_hash', 64)->nullable()->after('access_code');
        });
    }
    public function down(): void
    {
        Schema::table('journey_vetting_submissions', fn (Blueprint $table) => $table->dropColumn(['access_code', 'access_code_hash']));
    }
};

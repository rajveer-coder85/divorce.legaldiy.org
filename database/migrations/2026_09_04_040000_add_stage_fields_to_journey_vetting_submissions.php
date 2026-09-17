<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('journey_vetting_submissions', function (Blueprint $table) {
            $table->string('monthly_income_range', 30)->nullable()->after('employment_status');
            $table->string('separation_status', 30)->nullable()->after('court_experience');
            $table->string('separation_duration', 30)->nullable()->after('separation_status');
            $table->string('divorce_stage', 30)->nullable()->after('separation_duration');
            $table->string('papers_filed', 30)->nullable()->after('divorce_stage');
        });
    }
    public function down(): void
    {
        Schema::table('journey_vetting_submissions', fn (Blueprint $table) => $table->dropColumn(['monthly_income_range', 'separation_status', 'separation_duration', 'divorce_stage', 'papers_filed']));
    }
};

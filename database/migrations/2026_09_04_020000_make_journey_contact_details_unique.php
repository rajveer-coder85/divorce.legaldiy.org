<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('journey_vetting_submissions', function (Blueprint $table) {
            $table->unique('email', 'journey_vetting_email_unique');
            $table->unique('phone', 'journey_vetting_phone_unique');
        });
    }

    public function down(): void
    {
        Schema::table('journey_vetting_submissions', function (Blueprint $table) {
            $table->dropUnique('journey_vetting_email_unique');
            $table->dropUnique('journey_vetting_phone_unique');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('journey_vetting_submissions', function (Blueprint $table) {
            $table->string('phone', 30)->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('journey_vetting_submissions', function (Blueprint $table) {
            $table->string('phone', 30)->nullable()->change();
        });
    }
};

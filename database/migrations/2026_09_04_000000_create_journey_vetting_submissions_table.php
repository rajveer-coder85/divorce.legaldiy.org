<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('journey_vetting_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 24)->unique();
            $table->string('full_name', 150);
            $table->string('email', 254)->index();
            $table->string('phone', 30)->nullable();
            $table->string('identity_type', 20);
            $table->text('identity_number');
            $table->string('education_level', 40);
            $table->string('employment_status', 40);
            $table->string('preferred_language', 40);
            $table->string('court_experience', 20);
            $table->unsignedTinyInteger('legal_document_confidence');
            $table->text('support_needs')->nullable();
            $table->string('agreement_status', 20);
            $table->json('selected_topics');
            $table->timestamp('email_verified_at');
            $table->timestamp('submitted_at');
            $table->string('status', 30)->default('pending_review')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journey_vetting_submissions');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id(); $table->string('reference', 24)->unique(); $table->string('full_name', 150);
            $table->string('email', 254)->index(); $table->string('topic', 40); $table->text('message');
            $table->timestamp('email_verified_at'); $table->timestamp('submitted_at');
            $table->string('status', 30)->default('new')->index(); $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('inquiries'); }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inquiry_communications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inquiry_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('direction', 20)->default('outbound');
            $table->string('from_email', 254);
            $table->string('to_email', 254);
            $table->string('subject', 200);
            $table->text('body');
            $table->timestamp('sent_at')->nullable()->index();
            $table->timestamps();
        });

        DB::table('inquiries')->where('status', 'reviewed')->update(['status' => 'ongoing']);
    }

    public function down(): void
    {
        DB::table('inquiries')->where('status', 'ongoing')->update(['status' => 'reviewed']);
        Schema::dropIfExists('inquiry_communications');
    }
};

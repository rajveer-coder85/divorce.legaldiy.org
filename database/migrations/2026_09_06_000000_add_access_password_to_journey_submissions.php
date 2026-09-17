<?php
use Illuminate\Database\Migrations\Migration;use Illuminate\Database\Schema\Blueprint;use Illuminate\Support\Facades\Schema;
return new class extends Migration { public function up():void{Schema::table('journey_vetting_submissions',function(Blueprint $table){$table->string('access_password')->nullable()->after('access_enabled_at');$table->timestamp('access_invited_at')->nullable()->after('access_password');});}public function down():void{Schema::table('journey_vetting_submissions',fn(Blueprint $table)=>$table->dropColumn(['access_password','access_invited_at']));}};

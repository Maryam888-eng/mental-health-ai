<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('conversations', function (Blueprint $table) {
        $table->enum('risk_score', ['low', 'medium', 'high', 'crisis'])->default('low')->after('status');
        $table->timestamp('risk_assessed_at')->nullable()->after('risk_score');
    });
}

public function down()
{
    Schema::table('conversations', function (Blueprint $table) {
        $table->dropColumn(['risk_score', 'risk_assessed_at']);
    });
}};
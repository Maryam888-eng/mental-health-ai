<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Check existing columns
        $columns = DB::getSchemaBuilder()->getColumnListing('users');
        
        Schema::table('users', function (Blueprint $table) use ($columns) {
            // Sirf woh columns add karein jo exist nahi karte
            
            if (!in_array('display_name', $columns)) {
                $table->string('display_name')->nullable()->unique()->after('name');
            }
            
            if (!in_array('avatar', $columns)) {
                $table->string('avatar')->nullable()->after('role');
            }
            
            if (!in_array('bio', $columns)) {
                $table->text('bio')->nullable()->after('avatar');
            }
            
            if (!in_array('phone', $columns)) {
                $table->string('phone')->nullable()->after('bio');
            }
            
            if (!in_array('date_of_birth', $columns)) {
                $table->date('date_of_birth')->nullable()->after('phone');
            }
            
            if (!in_array('gender', $columns)) {
                $table->string('gender')->nullable()->after('date_of_birth');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'display_name',
                'avatar',
                'bio',
                'phone',
                'date_of_birth',
                'gender'
            ]);
        });
    }
};
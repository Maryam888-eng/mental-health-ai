<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('emergency_alerts', function (Blueprint $table) {
            // Notified doctor
            $table->foreignId('notified_doctor_id')->nullable()
                  ->after('message_id')
                  ->constrained('users')->onDelete('set null');
            
            // Priority level
            $table->integer('priority_level')->default(3)
                  ->after('alert_type')
                  ->comment('1=Highest, 4=Lowest');
            
            // Notification tracking
            $table->timestamp('notified_at')->nullable()->after('resolution_notes');
            $table->string('notification_method')->nullable()->after('notified_at')
                  ->comment('email, sms, push, in_app');
            
            // Additional info
            $table->string('trigger_keyword')->nullable()->after('notification_method')
                  ->comment('Which keyword triggered this alert');
            $table->json('meta_data')->nullable()->after('trigger_keyword');
            
            // Indexes
            $table->index(['alert_type', 'is_resolved']);
            $table->index(['priority_level', 'is_resolved']);
        });
    }

    public function down()
    {
        Schema::table('emergency_alerts', function (Blueprint $table) {
            $table->dropForeign(['notified_doctor_id']);
            $table->dropColumn([
                'notified_doctor_id',
                'priority_level',
                'notified_at',
                'notification_method',
                'trigger_keyword',
                'meta_data'
            ]);
            $table->dropIndex(['alert_type', 'is_resolved']);
            $table->dropIndex(['priority_level', 'is_resolved']);
        });
    }
};
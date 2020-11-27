<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmailsNotificationsSentColumnToOnlineEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('online_events', function (Blueprint $table) {
            $table->boolean('email_notifications_sent')
                ->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('online_events', function (Blueprint $table) {
            $table->dropColumn(['email_notifications_sent']);
        });
    }
}

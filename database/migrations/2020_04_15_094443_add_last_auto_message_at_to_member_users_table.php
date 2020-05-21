<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLastAutoMessageAtToMemberUsersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('member_users', function (Blueprint $table) {
            $table->timestamp('last_auto_message_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('member_users', function (Blueprint $table) {
            $table->dropColumn('last_auto_message_at');
        });
    }
}

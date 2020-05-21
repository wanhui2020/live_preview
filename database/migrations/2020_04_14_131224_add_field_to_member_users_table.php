<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldToMemberUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('member_users', function (Blueprint $table) {
            $table->string('change_charm_integral')->default(0)->comment('改变魅力积分');
            $table->string('change_vip_integral')->default(0)->comment('改变VIP积分');
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
            //
        });
    }
}

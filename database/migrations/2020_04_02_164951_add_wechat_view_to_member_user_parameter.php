<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWechatViewToMemberUserParameter extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('member_user_parameter', function (Blueprint $table) {
            $table->unsignedTinyInteger('wechat_view')->default(0)->comment('微信查看 0允许 1不允许');
//
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('member_user_parameter', function (Blueprint $table) {
            //
        });
    }
}

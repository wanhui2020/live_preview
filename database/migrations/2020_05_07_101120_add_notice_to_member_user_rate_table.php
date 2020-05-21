<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNoticeToMemberUserRateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('member_user_rate', function (Blueprint $table) {
            $table->unsignedDecimal('recommender_consume_rate', 12, 4)->default(0.1)->comment('（邀请人）邀请消费分成（%）');
            $table->unsignedDecimal('invite_consume_rate', 12, 4)->default(0.1)->comment('（经纪人）邀请消费分成（%）');
            $table->unsignedTinyInteger('reward_customization')->default(0)->comment('奖励自定义 0否 1是');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('member_user_rate', function (Blueprint $table) {
            //
        });
    }
}

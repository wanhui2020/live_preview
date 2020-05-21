<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNoticeToPlatformConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('platform_config', function (Blueprint $table) {
            $table->unsignedTinyInteger('notice_display')->default(0)->comment('公告显示 0是 1否');
            $table->unsignedTinyInteger('notice_agreement')->default(0)->comment('同意协议 0是 1否');
            $table->unsignedTinyInteger('self_sex')->default(0)->comment('自拍认证 0不限 1男2女');
            $table->unsignedDecimal('recommender_consume_rate', 12, 4)->default(0.1)->comment('（邀请人）邀请消费分成（%）');
            $table->unsignedDecimal('invite_consume_rate', 12, 4)->default(0.1)->comment('（经纪人）邀请消费分成（%）');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('platform_config', function (Blueprint $table) {
            //
        });
    }
}

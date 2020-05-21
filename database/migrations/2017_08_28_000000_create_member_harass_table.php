<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * 会员防骚扰设置
 * Class CreateMerchantsWalletTable
 */
class CreateMemberHarassTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_harass', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('member_id')->default(0)->comment('所属会员');

            $table->unsignedInteger('vip_grade')->default(0)->comment('VIP等级');
            $table->unsignedInteger('charm_grade')->default(0)->comment('魅力等级');
            $table->unsignedInteger('sex')->default(9)->comment('性别要求0男1女');
            $table->unsignedInteger('age')->default(0)->comment('年龄要求');
            $table->unsignedInteger('distance')->default(0)->comment('距离要求');
            $table->unsignedInteger('is_realname')->default(9)->comment('实名要求0实名1未实名');
            $table->unsignedInteger('is_selfie')->default(9)->comment('自拍认证0认证1未认证');
            $table->unsignedTinyInteger('sort')->default(0)->comment('排序');
            $table->unsignedTinyInteger('status')->default(0)->comment('状态0 正常 1禁用');
            $table->text('remark')->nullable()->comment('备注');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_harass');
    }
}

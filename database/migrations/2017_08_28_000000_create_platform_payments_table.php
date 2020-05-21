<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * 支付通道
 * Class CreateMerchantsTable
 */
class CreatePlatformPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('platform_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('通道名称');
            $table->unsignedInteger('channel_id')->default(0)->comment('所属通道');
            $table->unsignedTinyInteger('vip_min_grade')->default(0)->comment('VIP最低等级');
            $table->string('type')->nullable()->comment('支付方式');
            $table->string('account')->comment('账号');
            $table->unsignedDecimal('min_money', 12, 2)->default(100)->comment('最小金额');
            $table->unsignedDecimal('max_money', 12, 2)->default(50000)->comment('最大金额');
            $table->unsignedDecimal('day_quota', 12, 2)->default(0)->comment('日限额');
            $table->time('begin_time')->nullable()->comment('开始时间');
            $table->time('end_time')->nullable()->comment('结束时间');
            $table->text('parameter')->nullable()->comment('通道参数');

            $table->unsignedDecimal('recharge_rate', 12, 4)->default(0.00)->comment('充值费率');
            $table->unsignedDecimal('cost_rate', 12, 4)->default(0.00)->comment('成本费率');

            $table->unsignedTinyInteger('status')->default(1)->comment('状态 0:正常 1:禁用  ');
            $table->unsignedTinyInteger('sort')->default(0)->comment('排序');
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
        Schema::dropIfExists('platform_payments');
    }
}

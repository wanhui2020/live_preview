<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * vip管理
 * Class CreateMerchantsWalletTable
 */
class CreatePlatformVipTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('platform_vip', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable()->comment('VIP名称');
            $table->unsignedTinyInteger('grade')->default(0)->comment('等级');
            $table->string('icon')->nullable()->comment('图标');
            $table->unsignedInteger('integral')->default(0)->comment('积分数');
            $table->unsignedDecimal('price')->default(0)->comment('直接升级价格');
            $table->unsignedInteger('recharge_give')->default(0)->comment('充值赠送比例');
            $table->text('describe')->nullable()->comment('特权说明');
            $table->unsignedTinyInteger('sort')->default(0)->comment('排序');
            $table->unsignedTinyInteger('status')->default(0)->comment('状态0 正常 1禁用');
            $table->text('remark')->nullable()->comment('备注');

            $table->unique(['grade']);
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
        Schema::dropIfExists('platform_vip');
    }
}

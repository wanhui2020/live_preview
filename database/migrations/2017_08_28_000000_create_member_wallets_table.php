<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberWalletsTable extends Migration
{
    /**
     * 会员钱包
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_wallets', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('member_id')->default(0)->comment('所属会员');

            $table->decimal('gold_balance')->default(0.00)->comment('能量余额');
            $table->decimal('gold_usable')->default(0.00)->comment('能量可用余额');
            $table->decimal('gold_freeze')->default(0.00)->comment('能量冻结余额');
            $table->decimal('gold_platform')->default(0.00)->comment('能量平台冻结余额');

            $table->decimal('money_balance')->default(0.00)->comment('金币余额');
            $table->decimal('money_recharge')->default(0.00)->comment('金币充值');
            $table->decimal('money_conversion')->default(0.00)->comment('金币兑换现金');
            $table->decimal('money_commission')->default(0.00)->comment('佣金奖励');
            $table->decimal('money_freeze')->default(0.00)->comment('现金冻结余额');
            $table->decimal('money_platform')->default(0.00)->comment('现金平台冻结余额');
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
        Schema::dropIfExists('member_wallets');
    }
}

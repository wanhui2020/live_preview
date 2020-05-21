<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberWalletRechargesTable extends Migration
{
    /**
     * 会员充值记录
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_wallet_recharges', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no')->unique()->comment('充值单号');
            $table->unsignedInteger('member_id')->default(0)->comment('所属会员');
            $table->unsignedInteger('payment_id')->default(0)->comment('所属支付通道');
            $table->nullableMorphs('relevance');//业务关联
            $table->unsignedDecimal('money', 12, 2)->default(0.00)->comment('申请金额');

            $table->unsignedDecimal('cost_rate', 12, 4)->default(0.00)->comment('成本费率');
            $table->unsignedDecimal('cost_commission', 12, 4)->default(0.00)->comment('成本佣金');

            $table->dateTime('pay_time')->nullable()->comment('支付时间');
            $table->unsignedTinyInteger('pay_status')->default(9)->comment('支付状态0成功1失败2取消9支付中');
            $table->text('pay_remark')->nullable()->comment('支付备注');

            $table->unsignedInteger('audit_uid')->nullable()->comment('经办人');
            $table->string('audit_name')->nullable()->comment('经办人姓名');
            $table->dateTime('audit_time')->nullable()->comment('经办时间');
            $table->string('audit_reason')->nullable()->comment('经办意见');

            $table->unsignedTinyInteger('status')->default(9)->comment('状态(0  审核通过1审核拒绝9支付中)');
            $table->unsignedTinyInteger('sort')->default(0)->comment('排序');
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
        Schema::dropIfExists('member_wallet_recharges');
    }
}

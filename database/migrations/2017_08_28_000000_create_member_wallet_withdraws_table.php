<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberWalletWithdrawsTable extends Migration
{
    /**
     * 会员提现记录
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_wallet_withdraws', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('member_id')->default(0)->comment('所属会员');
            $table->string('no')->unique()->comment('单号');
            $table->nullableMorphs('relevance');//业务关联
            $table->unsignedDecimal('money', 12, 2)->default(0.00)->comment('申请金额');

            $table->unsignedDecimal('cost_rate', 12, 4)->default(0.00)->comment('成本费率');
            $table->unsignedDecimal('cost_commission', 12, 2)->default(0.00)->comment('成本佣金');

            $table->string('username')->nullable()->comment('用户姓名');
            $table->string('bank_account')->nullable()->comment('银行账号');
            $table->string('bank_name')->nullable()->comment('银行名称');

            $table->dateTime('pay_time')->nullable()->comment('支付时间');
            $table->unsignedTinyInteger('pay_status')->default(9)->comment('支付状态0成功1失败9支付中');
            $table->text('pay_remark')->nullable()->comment('支付备注');

            $table->unsignedInteger('audit_uid')->nullable()->comment('经办人');
            $table->string('audit_name')->nullable()->comment('经办人姓名');
            $table->dateTime('audit_time')->nullable()->comment('经办时间');
            $table->string('audit_reason')->nullable()->comment('经办意见');

            $table->unsignedTinyInteger('status')->default(0)->comment('状态(0未审核  1审核通过 2审核拒绝 9待办)');
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
        Schema::dropIfExists('member_wallet_withdraws');
    }
}

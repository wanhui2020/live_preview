<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealWithdrawsTable extends Migration
{
    /**
     * 提现申请
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deal_withdraws', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('member_id')->default(0)->comment('所属会员');
            $table->string('no')->unique()->comment('单号');

            $table->unsignedDecimal('money', 12, 2)->default(0.00)->comment('申请金额');

            $table->unsignedInteger('total')->default(0)->comment('提现合计');
            $table->unsignedTinyInteger('platform_way')->default(1)->comment('佣金分层方式，0能量，1金币');
            $table->unsignedDecimal('platform_rate', 12, 4)->default(0)->comment('分层比例');
            $table->unsignedDecimal('platform_commission', 12, 2)->default(0)->comment('平台佣金');
            $table->unsignedDecimal('received', 12, 2)->default(0.00)->comment('实到');

            $table->string('username')->nullable()->comment('用户姓名');
            $table->string('bank_account')->nullable()->comment('银行账号');
            $table->string('bank_name')->nullable()->comment('银行名称');

            $table->unsignedInteger('audit_uid')->nullable()->comment('经办人');
            $table->string('audit_name')->nullable()->comment('经办人姓名');
            $table->dateTime('audit_time')->nullable()->comment('经办时间');
            $table->string('audit_reason')->nullable()->comment('经办意见');

            $table->unsignedTinyInteger('status')->default(9)->comment('状态 0成功 1失败2用户取消3系统取消 8支付中 9待支付');
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
        Schema::dropIfExists('deal_withdraws');
    }
}

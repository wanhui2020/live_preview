<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * 会员提现收款人账号
 * Class CreateMemberUsersTable
 */
class CreateMemberPayeesTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create('member_payees', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('member_id')->default(0)->comment('所属会员');

            $table->string('name')->nullable()->comment('收款人（实名）');
            $table->string('bank_name')->nullable()->comment('银行名称');
            $table->string('bank_account')->nullable()->comment('银行账号');
            $table->string('alipay_account')->nullable()->comment('支付宝账号');
            $table->string('alipay_code')->nullable()->comment('支付宝收款码');
            $table->string('weixin_account')->nullable()->comment('微信账号');
            $table->string('weixin_code')->nullable()->comment('微信收款码');

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
        Schema::dropIfExists('member_payees');
    }
}

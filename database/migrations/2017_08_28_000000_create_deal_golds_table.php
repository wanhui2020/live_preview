<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealGoldsTable extends Migration
{
    /**
     * 会员能量购买记录
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deal_golds', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no')->unique()->comment('订单编号');
            $table->unsignedInteger('member_id')->default(0)->comment('所属会员ID');
            $table->unsignedInteger('price_id')->default(0)->comment('价格编号');
            $table->string('name')->nullable()->comment('价格名称');
            $table->unsignedDecimal('money',12,2)->default(0)->comment('产品金额');

            $table->unsignedInteger('gold')->default(0)->comment('充值能量');
            $table->unsignedInteger('give')->default(0)->comment('赠送能量');
            $table->unsignedDecimal('received',12,2)->default(0.00)->comment('实到能量');

            $table->unsignedInteger('audit_uid')->nullable()->comment('经办人');
            $table->string('audit_name')->nullable()->comment('经办人姓名');
            $table->dateTime('audit_time')->nullable()->comment('经办时间');
            $table->string('audit_reason')->nullable()->comment('经办意见');

            $table->unsignedTinyInteger('status')->default(9)->comment('状态(0正常  1失败2取消 9待确认)');
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
        Schema::dropIfExists('deal_golds');
    }
}

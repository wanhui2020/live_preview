<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * vip 购买记录
 * Class CreateMerchantsTable
 */
class CreateDealVipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deal_vips', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no')->unique()->comment('订单编号');
            $table->unsignedInteger('member_id')->default(0)->comment('所属会员ID');
            $table->unsignedInteger('vip_id')->default(0)->comment('所属会员产品ID');
            $table->string('name')->nullable()->comment('VIP名称');
            $table->unsignedDecimal('money')->default(0.00)->comment('价格');
            $table->integer('days')->default(0)->comment('天数');

            $table->unsignedInteger('total')->default(0)->comment('消费合计');
            $table->unsignedTinyInteger('platform_way')->default(0)->comment('佣金分层方式，0能量，1金币');
            $table->unsignedDecimal('platform_rate', 12, 4)->default(0)->comment('分层比例');
            $table->unsignedDecimal('platform_commission', 12, 2)->default(0)->comment('平台佣金');
            $table->unsignedDecimal('received', 12, 2)->default(0.00)->comment('接收方实到能量');


            $table->unsignedInteger('audit_uid')->nullable()->comment('经办人');
            $table->string('audit_name')->nullable()->comment('经办人姓名');
            $table->dateTime('audit_time')->nullable()->comment('经办时间');
            $table->string('audit_reason')->nullable()->comment('经办意见');

            $table->unsignedTinyInteger('status')->default(9)->comment('状态 0成功 1失败 8支付中 9待支付');
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
        Schema::dropIfExists('deal_vips');
    }
}

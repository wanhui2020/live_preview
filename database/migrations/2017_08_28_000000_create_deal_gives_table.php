<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * 会员打赏
 * Class CreateMerchantsTable
 */
class CreateDealGivesTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create('deal_gives', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no')->unique()->comment('订单编号');
            $table->unsignedInteger('member_id')->default(0)->comment('赠送人');
            $table->unsignedInteger('to_member_id')->default(0)->comment('被赠送人');
            $table->unsignedDecimal('money',12,2)->default(0)->comment('打赏金额');

            $table->unsignedInteger('total')->default(0)->comment('消费合计');
            $table->unsignedTinyInteger('platform_way')->default(1)->comment('佣金分层方式，0能量，1金币');
            $table->unsignedDecimal('platform_rate', 12, 4)->default(0)->comment('分层比例');
            $table->unsignedDecimal('platform_commission', 12, 2)->default(0)->comment('平台佣金');
            $table->unsignedDecimal('received', 12, 2)->default(0.00)->comment('接收方实到能量');


            $table->unsignedTinyInteger('status')->default(9)->comment('状态 0成功  1失败 8支付中 9待支付 ');
            $table->unsignedTinyInteger('sort')->default(0)->comment('排序');
            $table->text('remark')->nullable()->comment('备注');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deal_gives');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * 商户订单卖出
 * Class CreateMerchantsTable
 */
class CreateDealTalksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deal_talks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no')->unique()->comment('订单编号');
            $table->string('room_id')->nullable()->comment('房间编号');
            $table->unsignedInteger('dialing_id')->default(0)->comment('主叫方');
            $table->unsignedInteger('called_id')->default(0)->comment('被叫方');
            $table->unsignedTinyInteger('type')->default(0)->comment('呼叫类型，0视频，1语音');
            $table->dateTime('begin_time')->nullable()->comment('开始时间');
            $table->dateTime('end_time')->nullable()->comment('结束时间');
            $table->unsignedInteger('duration')->default(0)->comment('通话时间秒');
            $table->unsignedInteger('price')->default(0)->comment('单价金币/分');
            $table->unsignedInteger('total')->default(0)->comment('呼叫方消费金币');
            $table->unsignedTinyInteger('platform_way')->default(1)->comment('佣金分层方式，0能量，1金币');
            $table->unsignedDecimal('platform_rate', 12, 4)->default(0)->comment('分层比例');
            $table->unsignedDecimal('platform_commission', 12, 2)->default(0)->comment('平台佣金');
            $table->unsignedDecimal('received', 12, 2)->default(0.00)->comment('被叫方实到金币');
            $table->unsignedTinyInteger('way')->default(9)->comment('结束辅助状态（0正常结束 1无应答挂断 2被叫拒绝挂断,3主叫未接通取消 4创建通话失败取消 5通强制挂断   9未结束  ） ');
            $table->unsignedTinyInteger('status')->default(9)->comment('状态（0结束1通话中 8呼叫中 9准备中） ');
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
        Schema::dropIfExists('deal_talks');
    }
}

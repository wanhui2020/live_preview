<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealChatsTable extends Migration
{
    /**
     * 会员聊天计费
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deal_chats', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no')->unique()->comment('订单编号');
            $table->unsignedInteger('member_id')->default(0)->comment('所属会员ID');
            $table->unsignedInteger('to_member_id')->default(0)->comment('解锁会员');

            $table->unsignedInteger('total')->default(0)->comment('合计消费');
            $table->unsignedTinyInteger('platform_way')->default(1)->comment('佣金分层方式，0能量，1金币');
            $table->unsignedDecimal('platform_rate', 12, 4)->default(0)->comment('分层比例');
            $table->unsignedDecimal('platform_commission', 12, 2)->default(0)->comment('平台佣金');
            $table->unsignedDecimal('received', 12, 2)->default(0.00)->comment('接收方实到能量');


            $table->unsignedTinyInteger('status')->default(9)->comment('状态(0正常 1失败 )');
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
        Schema::dropIfExists('deal_chats');
    }
}

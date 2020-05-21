<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * 资源查看
 * Class CreateMerchantsTable
 */
class CreateDealViewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deal_views', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no')->unique()->comment('订单编号');
            $table->unsignedInteger('member_id')->default(0)->comment('所属会员');
            $table->unsignedInteger('to_member_id')->default(0)->comment('资源所属会员');

            $table->unsignedInteger('resource_id')->default(0)->comment('被查看资源编号');
            $table->dateTime('end_time')->nullable()->comment('结束时间');
            $table->unsignedInteger('count')->default(1)->comment('查看次数');

            $table->unsignedInteger('total')->default(0)->comment('消费合计');
            $table->unsignedTinyInteger('platform_way')->default(0)->comment('佣金分层方式，0能量，1金币');
            $table->unsignedDecimal('platform_rate', 12, 4)->default(0)->comment('分层比例');
            $table->unsignedDecimal('platform_commission', 12, 2)->default(0)->comment('平台佣金');
            $table->unsignedDecimal('received', 12, 2)->default(0.00)->comment('接收方实到能量');


            $table->unsignedTinyInteger('status')->default(9)->comment('状态（0正常1无效） ');
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
        Schema::dropIfExists('deal_views');
    }
}

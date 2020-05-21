<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 线下约单 订单表
        Schema::create('date_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('order_no',64)->comment('订单编号');
            $table->unsignedInteger('from_user_id')->comment('发起人member_user_id');
            $table->unsignedInteger('to_user_id')->comment('接收人member_user_id');
            $table->timestamp('start_at')->nullable()->comment('开始时间');
            $table->timestamp('end_at')->nullable()->comment('结束时间');
            $table->unsignedDecimal('amount')->comment('订单总价');
            $table->unsignedInteger('status')->default(0)->comment('订单状态，date_order_status.status');
            $table->string('status_name')->default(0)->comment('订单状态名称date_order_status.name');

            $table->string('payment_method',64)->comment('支付方式');
            $table->string('payment_no')->comment('支付单号');
            $table->timestamp('payment_at')->comment('支付时间');

            $table->string('address')->comment('地点全称');
            // FIXME 根据情况，添加省/市/县 多个字段地址 或 经纬度 以用于地图标记

            $table->timestamps();

            $table->index(['status','to_user_id','from_user_id'],'status_toUserId_fromUserId');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('date_orders');
    }
}

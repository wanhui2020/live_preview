<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDateOrderRefundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 线下约单 订单退款表
        Schema::create('date_order_refunds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('date_order_id');
            $table->unsignedInteger('member_user_id');
            $table->unsignedInteger('host_member_user_id');
            $table->unsignedInteger('date_refund_id')->comment('date_refund表id');
            $table->string('comment')->comment('退款原因');

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
        Schema::dropIfExists('date_order_refunds');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDateOrderReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 线下约单 订单评价表
        Schema::create('date_order_reviews', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('date_order_id');
            $table->unsignedInteger('member_user_id');
            $table->unsignedInteger('host_member_user_id');
            $table->unsignedTinyInteger('star')->comment('分数');
            $table->string('comment')->comment('评价');

            $table->timestamps();

            $table->index(['date_order_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('date_order_reviews');
    }
}

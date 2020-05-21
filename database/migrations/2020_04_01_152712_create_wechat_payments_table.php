<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWechatPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wechat_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no')->unique()->comment('订单编号');
            $table->unsignedInteger('member_id')->comment('查看人')->index();
            $table->unsignedInteger('to_member_id')->comment('被查看人')->index();
            $table->decimal('money', 12, 2)->default(0)->comment('花费金币');
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
        Schema::dropIfExists('wechat_payments');
    }
}

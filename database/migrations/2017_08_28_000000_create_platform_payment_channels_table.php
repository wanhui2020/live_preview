<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * 支付通道维护
 * Class CreateMerchantsTable
 */
class CreatePlatformPaymentChannelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('platform_payment_channels', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('通道名称');
            $table->string('code')->unique()->comment('通道标识');
            $table->string('icon')->nullable()->comment('通道图标');

            $table->unsignedTinyInteger('status')->default(1)->comment('状态 0:正常 1:禁用  ');
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
        Schema::dropIfExists('platform_payment_channels');
    }
}

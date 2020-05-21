<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldToPlatformPaymentChannelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('platform_payment_channels', function (Blueprint $table) {
            $table->unsignedTinyInteger('type')->default(0)->comment('0 线上 1线下');
            $table->string('payee')->nullable()->comment('收款方');
            $table->string('payee_icon')->nullable()->comment('收款方二维码');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('platform_payment_channels', function (Blueprint $table) {
            //
        });
    }
}

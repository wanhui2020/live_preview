<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealConversionsTable extends Migration
{
    /**
     * 余额兑换
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deal_conversions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('member_id')->default(0)->comment('所属会员');
            $table->string('no')->unique()->comment('单号');

            $table->unsignedDecimal('gold',12,2)->default(0.00)->comment('兑换金币');

            $table->unsignedDecimal('conversion_rate',12,4)->default(0.00)->comment('金币兑换手续费费率');
            $table->unsignedDecimal('conversion_commission',12,2)->default(0.00)->comment('兑换手续费佣金');
            $table->unsignedDecimal('received_gold',12,2)->default(0.00)->comment('实际兑换金币');

            $table->unsignedDecimal('gold_rate',12,4)->default(0.00)->comment('金币兑换比例');

            $table->unsignedDecimal('money',12,2)->default(0.00)->comment('兑换后金额');

            $table->unsignedInteger('audit_uid')->nullable()->comment('经办人');
            $table->string('audit_name')->nullable()->comment('经办人姓名');
            $table->dateTime('audit_time')->nullable()->comment('经办时间');
            $table->string('audit_reason')->nullable()->comment('经办意见');

            $table->unsignedTinyInteger('status')->default(0)->comment('状态(0正常  1禁止)');
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
        Schema::dropIfExists('deal_conversions');
    }
}

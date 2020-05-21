<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * 平台费率
 * Class CreateMerchantsTable
 */
class CreatePlatformRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //商户
        Schema::create('platform_rates', function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('relevance');

            $table->unsignedDecimal('recharge_rate', 12, 4)->default(0)->comment('充值费率');
            $table->unsignedDecimal('golden_rate', 12, 4)->default(0)->comment('收单费率');
            $table->unsignedDecimal('withdraw_rate', 12, 4)->default(0)->comment('提现费率');
            $table->unsignedDecimal('payroll_rate', 12, 4)->default(0)->comment('代付费率');
            $table->unsignedDecimal('settle_rate', 12, 4)->default(0)->comment('结算费率');

            $table->unsignedDecimal('agent_recharge_rate', 12, 4)->default(0.00)->comment('服务商充值费率');
            $table->unsignedDecimal('agent_golden_rate', 12, 4)->default(0.00)->comment('服务商收单费率');
            $table->unsignedDecimal('agent_withdraw_rate', 12, 4)->default(0.00)->comment('服务商提现费率');
            $table->unsignedDecimal('agent_payroll_rate', 12, 4)->default(0.00)->comment('服务商代付费率');
            $table->unsignedDecimal('agent_settle_rate', 12, 4)->default(0.00)->comment('服务商结算费率');


            $table->unsignedTinyInteger('status')->default(0)->comment('状态 0:正常 1:禁用  ');
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
        Schema::dropIfExists('platform_rates');
    }
}

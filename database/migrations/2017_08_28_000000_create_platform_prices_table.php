<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * 平台充值价格维护
 * Class CreateMerchantsTable
 */
class CreatePlatformPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('platform_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('价格名称');
            $table->unsignedDecimal('money', 12, 2)->default(0)->comment('充值金额');
            $table->unsignedDecimal('rate', 12, 4)->default(0.8)->comment('兑换比例');

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
        Schema::dropIfExists('platform_prices');
    }
}

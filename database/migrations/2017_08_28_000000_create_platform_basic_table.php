<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * 平台基础数据
 * Class CreateMerchantsWalletTable
 */
class CreatePlatformBasicTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('platform_basic', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("type")->nullable()->comment("数据类型(1.消息收费 2.语音收费 3.视频收费 4.颜照库收费 5.视频库收费 6.换衣收费 7.举报理由");
            $table->string('key')->comment('对应键');
            $table->string('value')->nullable()->comment('对应值');

            $table->unsignedTinyInteger('status')->default(0)->comment('状态 0启用 1禁用 ');
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
        Schema::dropIfExists('platform_basic');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * 平台礼物
 * Class CreateMerchantsTable
 */
class CreatePlatformGiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('platform_gifts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 10)->comment('礼物名称');
            $table->integer('gold')->default(0)->comment('价格能量');
            $table->string('ico')->nullable()->comment('图标');
            $table->string('thumb')->nullable()->comment('缩略图');
            $table->string('cartoon')->nullable()->comment('动画地址');

            $table->unsignedTinyInteger('status')->default(9)->comment('状态 0:正常 1:禁用  ');
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
        Schema::dropIfExists('platform_gifts');
    }
}

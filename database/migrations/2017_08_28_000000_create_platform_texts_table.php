<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * 文本管理
 * Class CreateMerchantsTable
 */
class CreatePlatformTextsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('platform_texts', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('type')->default(0)->comment('类型 1 自拍认证文本 2 邀请文本');
            $table->text('content')->nullable()->comment('文本内容');

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
        Schema::dropIfExists('platform_texts');
    }
}

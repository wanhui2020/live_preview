<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlatformThemeTable extends Migration
{
    /**
     * 平台APP主题
     *
     * @return void
     */
    public function up()
    {
        Schema::create('platform_theme', function (Blueprint $table) {
            $table->increments('id');



            $table->unsignedTinyInteger('status')->default(0)->comment('状态。0=正常，1=禁用');
            $table->unsignedTinyInteger('sort')->default(0)->comment('排序');
            $table->text('remark')->nullable()->comment('备注');
            $table->unsignedInteger('versions')->default(0)->comment('版本号');
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
        Schema::dropIfExists('platform_config');
    }
}

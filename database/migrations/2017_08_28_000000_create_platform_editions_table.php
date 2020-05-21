<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * 应用版本
 * Class CreateMerchantsTable
 */
class CreatePlatformEditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //应用版本
        Schema::create('platform_editions', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('type')->nullable()->comment('所属类别 安卓android 苹果ios');
            $table->string('version')->nullable()->comment('版本号不可重复');
            $table->string('url')->nullable()->comment('下载地址');
            $table->unsignedTinyInteger('is_force')->default(1)->comment('强制更新 1 否 0是');
            $table->text('describe')->nullable()->comment('描述内容');
            $table->unsignedTinyInteger('status')->default(0)->comment('状态 1 启用 0禁用');
            $table->unsignedTinyInteger('sort')->default(0)->comment('排序');
            $table->unique(['type', 'version']);
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
        Schema::dropIfExists('platform_editions');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * 平台文件
 * Class CreateMerchantsTable
 */
class CreatePlatformFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('platform_files', function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('relevance');//应用场景
            $table->string('name')->nullable()->comment('文件名');
            $table->string('url')->nullable()->comment('原始地址');
            $table->string('thumb')->nullable()->comment('缩略图');
            $table->string('extension')->nullable()->comment('扩展名');
            $table->unsignedInteger('size')->default(0)->comment('文件大小');
            $table->unsignedInteger('width')->default(0)->comment('宽度');
            $table->unsignedInteger('height')->default(0)->comment('高度');
            $table->string('color')->nullable()->comment('主色');

            $table->unsignedTinyInteger('status')->default(9)->comment('状态 0:正常 1:禁用 9待审  ');
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
        Schema::dropIfExists('platform_files');
    }
}

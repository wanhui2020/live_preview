<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * 平台会员标签
 * Class CreateMerchantsTable
 */
class CreatePlatformTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('platform_tags', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 10)->comment('标签名称');
            $table->string('describe')->comment('标签描述');
            $table->string('ico')->nullable()->comment('标签图标');
            $table->string('thumb')->nullable()->comment('缩略图');

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
        Schema::dropIfExists('platform_tags');
    }
}

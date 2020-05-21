<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * 平台公告
 * Class CreateMerchantsTable
 */
class CreatePlatformKeywordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //关键字
        Schema::create('platform_keywords', function (Blueprint $table) {
            $table->increments('id');

            $table->string('replace')->nullable()->comment('替换的关键字');
            $table->string('toreplace')->nullable()->comment('被替换的关键字');

            $table->unsignedTinyInteger('type')->default(0)->comment('类型 0替换 1禁用');
            $table->unsignedTinyInteger('status')->default(0)->comment('状态0启用 1禁用 ');

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
        Schema::dropIfExists('platform_keywords');
    }
}

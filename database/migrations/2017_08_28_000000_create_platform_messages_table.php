<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * 平台公告
 * Class CreateMerchantsTable
 */
class CreatePlatformMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //平台公告
        Schema::create('platform_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('type')->default(0)->comment('类型 0所有人 1已认证的人 2未认证的人');
            $table->unsignedTinyInteger('is_banner')->default(0)->comment('是否banner 0否 1是');
            $table->string('pic')->nullable()->comment('标题图片');
            $table->string('title')->nullable()->comment('标题');
            $table->text('content')->nullable()->comment('内容');
            $table->string('url')->nullable()->comment('连接地址');

            $table->unsignedTinyInteger('status')->default(0)->comment('状态0启用 1禁用');
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
        Schema::dropIfExists('platform_messages');
    }
}

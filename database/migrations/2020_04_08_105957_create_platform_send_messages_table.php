<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlatformSendMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('platform_send_messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedTinyInteger('type')->default(0)->comment('0会员1客服');
            $table->string('content')->comment('内容');
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
        Schema::dropIfExists('platform_send_messages');
    }
}

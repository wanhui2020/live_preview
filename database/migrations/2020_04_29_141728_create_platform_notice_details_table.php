<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlatformNoticeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('platform_notice_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('member_id')->default(0)->comment('所属会员');
            $table->nullableMorphs('relevance');//业务关联
            $table->unsignedTinyInteger('status')->default(0)->comment('状态0:正常 1:禁用  ');
            $table->unsignedTinyInteger('is_read')->default(1)->comment('0:已读 1:未读  ');
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
        Schema::dropIfExists('platform_notice_details');
    }
}

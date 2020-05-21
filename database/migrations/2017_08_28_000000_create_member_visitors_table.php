<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberVisitorsTable extends Migration
{
    /**
     * 会员访问记录
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_visitors', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('member_id')->default(0)->comment('所属会员');
            $table->unsignedInteger('to_member_id')->default(0)->comment('所访问的会员编号');
            $table->dateTime('last_time')->nullable()->comment('最后访问时间');
            $table->string('last_ip', 20)->nullable()->comment('最后访问IP');
            $table->string('last_versions')->nullable()->comment('最后客户端版本号');
            $table->string('last_platform')->nullable()->comment('最后客户端手机平台');

            $table->unsignedInteger('number')->default(0)->comment('访问次数');

            $table->unsignedTinyInteger('status')->default(0)->comment('状态0:正常 1:禁用  ');
            $table->unsignedTinyInteger('sort')->default(0)->comment('排序');
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
        Schema::dropIfExists('member_visitors');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberSigninsTable extends Migration
{
    /**
     * 会员签到
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_signins', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('member_id')->default(0)->comment('所属会员');
            $table->integer('award')->default(0)->comment('奖励金币');
            $table->integer('continuous_days')->default(0)->comment('连续签到天数');
            $table->dateTime('signin_date')->comment('签到日期');

            $table->unsignedTinyInteger('status')->default(0)->comment('状态(0正常，1无效)');
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
        Schema::dropIfExists('member_signins');
    }
}

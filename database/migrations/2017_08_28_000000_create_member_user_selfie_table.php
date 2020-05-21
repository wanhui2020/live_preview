<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberUserSelfieTable extends Migration
{
    /**
     * 自拍认证
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_user_selfie', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('member_id')->default(0)->comment('所属会员');

            $table->string('picture')->nullable()->comment('自拍照');
            $table->string('video')->nullable()->comment('自拍视频');
            $table->string('undertaking')->nullable()->comment('承诺条款');

            $table->unsignedInteger('audit_uid')->nullable()->comment('审核人');
            $table->dateTime('audit_time')->nullable()->comment('审核时间');
            $table->string('audit_reason')->nullable()->comment('审核意见');

            $table->unsignedTinyInteger('status')->default(9)->comment('状态 0认证通过 1认证拒绝 8审核中 9待认证');
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
        Schema::dropIfExists('member_user_selfie');
    }
}

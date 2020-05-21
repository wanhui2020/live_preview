<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberFeedbacksTable extends Migration
{
    /**
     * 会员反馈
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_feedbacks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('member_id')->default(0)->comment('所属会员');

            $table->text('content')->comment('意见内容');
            $table->text('replay')->nullable()->comment('回复内容');
            $table->unsignedInteger('replay_uid' )->default(0)->comment('回复人');
            $table->dateTime('replay_time')->nullable()->comment('回复时间');
            $table->unsignedTinyInteger('replay_status')->default(0)->comment('状态 0:未回复 1:已回复');

            $table->unsignedTinyInteger('status')->default(9)->comment('状态0已处理1未处理 9待处理');
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
        Schema::dropIfExists('member_feedbacks');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberVerificationsTable extends Migration
{
    /**
     * 会员资料审核
     * @return void
     */
    public function up()
    {
        Schema::create('member_verifications', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('member_id')->default(0)->comment('所属会员');
            $table->string('old_data')->nullable()->comment('原始数据');
            $table->string('new_data')->nullable()->comment('新数据');
            $table->unsignedTinyInteger('info_type')->default(9)->comment('资料类型 0 昵称 1 个性签名2格言9其他');

            $table->unsignedInteger('audit_uid' )->nullable()->comment('审核人');
            $table->dateTime('audit_time')->nullable()->comment('审核时间');
            $table->string('audit_reason' )->nullable()->comment('审核意见');

            $table->unsignedTinyInteger('status')->default(9)->comment('状态 0:审核通过 1:审核拒绝 9:待审核 ');
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
        Schema::dropIfExists('member_verifications');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberUserRealnameTable extends Migration
{
    /**
     * 会员实名
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_user_realname', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('member_id')->default(0)->comment('所属会员');

            $table->string('idcard')->nullable()->comment('身份证号');
            $table->string('name')->nullable()->comment('真实姓名');
            $table->string('idcard_front')->nullable()->comment('身份证正面照');
            $table->string('idcard_back')->nullable()->comment('身份证反面照');
            $table->string('idcard_hand')->nullable()->comment('手持身份证');


            $table->string('address')->nullable()->comment('地址');

            $table->unsignedInteger('audit_uid')->nullable()->comment('审核人');
            $table->dateTime('audit_time')->nullable()->comment('审核时间');
            $table->string('audit_reason')->nullable()->comment('审核意见');

            $table->unsignedTinyInteger('status')->default(9)->comment('状态( 0审核通过 1审核拒绝  9 待审核)');
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
        Schema::dropIfExists('member_user_realname');
    }
}

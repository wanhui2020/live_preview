<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberReportsTable extends Migration
{
    /**
     * 会员举报
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('member_id')->default(0)->comment('所属会员');
            $table->unsignedInteger('to_member_id')->default(0)->comment('举报对象会员ID');
            $table->unsignedInteger('report_id')->default(0)->comment('基础数据id');
            $table->string('type')->nullable()->comment('举报类型');
            $table->text('content')->nullable()->comment('举报内容说明');

            $table->unsignedInteger('audit_uid' )->nullable()->comment('处理人');
            $table->dateTime('audit_time')->nullable()->comment('处理时间');
            $table->string('audit_reason' )->nullable()->comment('处理意见');

            $table->unsignedTinyInteger('status')->default(9)->comment('状态( 0已处理1未处理9处理中)');
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
        Schema::dropIfExists('member_reports');
    }
}

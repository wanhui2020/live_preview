<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberDynamicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_dynamics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('member_id')->default(0)->comment('所属会员');
            $table->text('content')->nullable()->comment('文本内容');
            $table->tinyInteger('type')->default(0)->comment('类型( 0图片 1 视频 ）');
            $table->unsignedDecimal('price')->default(0)->comment('查看价格');

            $table->unsignedInteger('audit_uid')->nullable()->comment('审核人');
            $table->dateTime('audit_time')->nullable()->comment('审核时间');
            $table->string('audit_reason')->nullable()->comment('审核意见');

            $table->unsignedTinyInteger('status')->default(9)->comment('状态(0审核通过 1审核拒绝9待审核)');
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
        Schema::dropIfExists('member_dynamics');
    }
}

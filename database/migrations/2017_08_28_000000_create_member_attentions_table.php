<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberAttentionsTable extends Migration
{
    /**
     * 会员关注
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_attentions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('member_id')->default(0)->comment('关注者');
            $table->unsignedInteger('to_member_id')->default(0)->comment('被关注者');

            $table->unsignedTinyInteger('status')->default(0)->comment('状态 0:已关注 1:未关注');
            $table->unsignedTinyInteger('sort')->default(0)->comment('排序');
            $table->text('remark')->nullable()->comment('备注');
            $table->unique(['member_id', 'to_member_id']);
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
        Schema::dropIfExists('member_attentions');
    }
}

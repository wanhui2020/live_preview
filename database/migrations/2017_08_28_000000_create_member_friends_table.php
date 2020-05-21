<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberFriendsTable extends Migration
{
    /**
     * 会员好友
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_friends', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('member_id')->default(0)->comment('所属会员');
            $table->unsignedInteger('to_member_id')->default(0)->comment('好友编号');

            $table->unsignedTinyInteger('status')->default(9)->comment('状态 0:正常 1:禁用 2:冻结 ');
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
        Schema::dropIfExists('member_friends');
    }
}

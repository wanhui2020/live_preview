<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldToMemberDynamicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('member_dynamics', function (Blueprint $table) {
            $table->string('resident')->nullable()->comment('城市');
            $table->unsignedInteger('like_number')->default(0)->comment('点赞次数');
            $table->unsignedInteger('comment_number')->default(0)->comment('评论次数');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('member_dynamics', function (Blueprint $table) {
            //
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * 微信关注
 * Class CreateMemberUsersTable
 */
class CreateWeixinUsersTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create('weixin_users', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('parent_id')->default(0)->comment('推荐人');

            $table->string('no')->unique()->comment('会员编号');
            $table->string('openid')->unique()->comment('微信编号');
            $table->string('nick_name')->nullable()->comment('昵称');

            $table->string('head_pic')->nullable()->comment('头像图片');
            $table->unsignedTinyInteger('sex')->default(9)->comment('姓别 0男 1女9未知');

            $table->date('birthday')->nullable()->comment('生日');

            $table->unsignedTinyInteger('status')->default(0)->comment('状态0正常1 禁用 ');
            $table->unsignedTinyInteger('sort')->default(0)->comment('排序');
            $table->rememberToken();
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
        Schema::dropIfExists('weixin_users');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberUserExtendTable extends Migration
{
    /**
     * 会员扩展信息
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_user_extend', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('member_id')->default(0)->comment('所属会员');
            $table->string('weixin', 11)->nullable()->comment('个人微信号');
            $table->unsignedTinyInteger('weixin_verify')->default(9)->comment('微信号验证 0通过 1不通过 2待审核9未审核');
            $table->string('qq')->nullable()->comment('QQ号');
            $table->unsignedTinyInteger('qq_verify')->default(9)->comment('QQ号验证 0通过 1不通过 2待审核9未审核');
            $table->date('birthday')->nullable()->comment('生日');
            $table->string('hobbies')->nullable()->comment('兴趣爱好');
            $table->string('profession')->nullable()->comment('职业');
            $table->string('height')->nullable()->comment('身高(cm)');
            $table->string('weight')->nullable()->comment('体重(KG)');
            $table->string('constellation')->nullable()->comment('星座');
            $table->string('blood')->nullable()->comment('血型');
            $table->string('emotion')->nullable()->comment('情感');
            $table->string('income')->nullable()->comment('收入');

            $table->unsignedTinyInteger('status')->default(0)->comment('状态0正常  1禁用');
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
        Schema::dropIfExists('member_user_extend');
    }
}

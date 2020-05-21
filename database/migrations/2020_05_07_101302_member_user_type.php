<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MemberUserType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_user_type', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedInteger('member_id')->default(0)->comment('所属会员ID');
            $table->unsignedInteger('type_id')->default(0)->comment('所属类型ID');

            $table->unsignedTinyInteger('sort')->default(0)->comment('排序');
            $table->unsignedTinyInteger('status')->default(0)->comment('状态0 正常 1禁用');
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
        Schema::dropIfExists('member_user_type');
    }
}

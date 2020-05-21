<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberBlacklistsTable extends Migration
{
    /**
     * 黑名单
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_blacklists', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('member_id')->default(0)->comment('拉黑人');
            $table->unsignedInteger('to_member_id')->default(0)->comment('被拉黑人');
            $table->string('type')->nullable()->comment('拉黑原因');

            $table->unsignedTinyInteger('status')->default(1)->comment('状态 0:正常 1:禁用  ');
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
        Schema::dropIfExists('member_blacklists');
    }
}

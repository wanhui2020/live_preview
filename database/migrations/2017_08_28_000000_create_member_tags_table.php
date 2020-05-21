<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberTagsTable extends Migration
{
    /**
     * 会员标签
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_tags', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('member_id')->default(0)->comment('所属会员');
            $table->unsignedInteger('tag_id')->default(0)->comment('标签编号');

            $table->unsignedTinyInteger('status')->default(0)->comment('状态 0:正常 1:禁用  ');
            $table->unsignedTinyInteger('sort')->default(0)->comment('排序');
            $table->text('remark')->nullable()->comment('备注');
            $table->unique(['member_id', 'tag_id']);
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
        Schema::dropIfExists('member_tags');
    }
}

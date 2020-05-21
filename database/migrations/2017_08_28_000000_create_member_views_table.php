<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberViewsTable extends Migration
{
    /**
     * 会员查看
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_views', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('member_id')->default(0)->comment('所属会员');
            $table->morphs('relevance');//查看对象


            $table->decimal('gold',12,2)->default(0)->comment('花费金币');

            $table->unsignedTinyInteger('status')->default(0)->comment('状态0:正常 1:禁用  ');
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
        Schema::dropIfExists('member_views');
    }
}

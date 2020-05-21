<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDateProjectsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 线下约单 项目表
        Schema::create('date_projects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->comment('项目名称');
            $table->string('image')->comment('项目图片');
            $table->text('desc')->default('')->comment('描述');
            $table->boolean('need_review')->comment('是否需要审核');
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
        Schema::dropIfExists('date_projects');
    }
}

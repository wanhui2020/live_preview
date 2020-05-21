<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 线下约单 项目等级表
        Schema::create('project_levels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->comment('等级名称');
            $table->unsignedTinyInteger('level')->comment('等级');
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
        Schema::dropIfExists('project_levels');
    }
}

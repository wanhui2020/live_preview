<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDateOrderProjectsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 线下约单 订单项目表
        Schema::create('date_order_projects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('date_order_id');
            $table->unsignedInteger('date_project_id');
            $table->string('date_project_name')->comment('项目名称');
            $table->timestamps();

            $table->index(['date_order_id', 'date_project_id'],'orderId_projectId');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('date_order_projects');
    }
}

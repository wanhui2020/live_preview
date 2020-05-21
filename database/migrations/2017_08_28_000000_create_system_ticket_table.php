<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemTicketTable extends Migration
{
    /**
     * 系统编号规则
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_tickets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->comment('编号类型');
            $table->string('prefix')->nullable()->comment('前缀');
            $table->char('no')->comment('编号');
            $table->unsignedInteger('versions')->default(1)->comment('版本号');
            $table->unique(['type','no']);
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
        Schema::dropIfExists('system_tickets');
    }
}

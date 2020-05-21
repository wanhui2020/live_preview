<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_config', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable()->comment('系统名称');
            $table->string('logo')->nullable()->comment('标志');
            $table->string('domain')->nullable()->comment('平台域名');
            $table->string('tel')->nullable()->comment('客服热线');
            $table->string('weixin')->nullable()->comment('客服微信');
            $table->unsignedTinyInteger('status')->default(0)->comment('状态。0正常，1禁用');
            $table->unsignedTinyInteger('sort')->default(0)->comment('排序');
            $table->text('remark')->nullable()->comment('备注');
            $table->unsignedInteger('versions')->default(0)->comment('版本号');$table->softDeletes();
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
        Schema::dropIfExists('system_config');
    }
}

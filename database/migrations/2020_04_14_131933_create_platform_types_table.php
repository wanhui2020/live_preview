<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlatformTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('platform_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable()->comment('名称');
            $table->string('url')->nullable()->comment('连接');
            $table->unsignedTinyInteger('sort')->default(0)->comment('排序');
            $table->unsignedTinyInteger('status')->default(0)->comment('状态0 正常 1禁用');
            $table->text('content')->nullable()->comment('内容');
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
        Schema::dropIfExists('platform_types');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlatformConditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('platform_conditions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('key')->nullable()->comment('键');
            $table->string('name')->nullable()->comment('名称');
            $table->string('mark')->default('=')->comment('符号');
            $table->string('value')->nullable()->comment('值');
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
        Schema::dropIfExists('platform_conditions');
    }
}

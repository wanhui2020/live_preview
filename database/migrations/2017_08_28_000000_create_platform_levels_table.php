<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * 平台会员评级规则
 * Class CreateMerchantsTable
 */
class CreatePlatformLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('platform_levels', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('type')->default(0)->comment('规则类型（0积分 1富豪 2魅力）');
            $table->tinyInteger('level')->default(0)->comment('会员级别');
            $table->string('name')->comment('会员级别名称');
            $table->tinyInteger('min_score')->default(0)->comment('范围下限(最低)');
            $table->tinyInteger('max_score')->default(0)->comment('范围上限(最高)');

            $table->unsignedTinyInteger('status')->default(0)->comment('状态 0:正常 1:禁用  ');
            $table->unsignedTinyInteger('sort')->default(0)->comment('排序');
            $table->text('remark')->nullable()->comment('备注');
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
        Schema::dropIfExists('platform_levels');
    }
}

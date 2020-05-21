<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * 平台VIP会员等级
 * Class CreateMerchantsTable
 */
class CreatePlatformGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('platform_grades', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 10)->comment('名称');
            $table->string('describe')->comment('描述');
            $table->string('ico')->nullable()->comment('图标');
            $table->unsignedInteger('period')->default(7)->comment('时间周期');
            $table->integer('gold')->default(0)->comment('所需金币');

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
        Schema::dropIfExists('platform_grades');
    }
}

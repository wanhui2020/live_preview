<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 线下约单 用户项目表
        Schema::create('member_projects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('member_user_id')->comment('用户id');
            $table->string('review_video')->comment('审核视频url');
            $table->text('desc')->comment('说明');
            $table->string('project_level_name')->comment('技能水平');
            $table->string('project_level')->comment('技能水平 project_levels表level字段');
            $table->boolean('reviewed')->default(false)->comment('是否已审核');
            $table->timestamp('reviewed_at')->nullable()->comment('审核时间');
            $table->unsignedInteger('reviewed_system_user_id')->nullable()->comment('审核人员id');

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
        Schema::dropIfExists('member_projects');
    }
}

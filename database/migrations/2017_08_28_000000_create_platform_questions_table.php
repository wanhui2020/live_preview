<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * 服务工单
 * Class CreateMerchantsWalletTable
 */
class CreatePlatformQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('platform_questions', function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('relevance');
            $table->string('no')->unique()->comment('工单编号');
            $table->unsignedTinyInteger('type')->default(0)->comment('工单类型0默认工单1申诉工单');
            $table->unsignedInteger('parent_id')->default(0)->comment('所属问题');
            $table->unsignedInteger('order_id')->default(0)->comment('相关订单');
            $table->string('title')->nullable()->comment('标题内容');
            $table->text('content')->nullable()->comment('内容');
            $table->string('atlas')->nullable()->comment('图集');


            $table->unsignedInteger('grade')->default(9)->comment('评分');
            $table->unsignedInteger('finish_status')->default(9)->comment('完成状态9进行中，0已完结');
            $table->dateTime('finish_time')->nullable()->comment('完成时间');
            $table->unsignedTinyInteger('status')->default(0)->comment('状态 0:正常 1:禁用 2:冻结');
            $table->unsignedTinyInteger('sort')->default(0)->comment('排序');
            $table->text('remark')->nullable()->comment('备注');
            $table->unsignedInteger('versions')->default(0)->comment('版本号');
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
        Schema::dropIfExists('platform_questions');
    }
}

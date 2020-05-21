<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * 评论
 * Class CreateMerchantsTable
 */
class CreateDealCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deal_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('relevance');//会员评论，动态评论，通话评论，订单评论
            $table->unsignedInteger('member_id')->default(0)->comment('评论者');
            $table->unsignedInteger('to_member_id')->default(0)->comment('被评论者');
            $table->text('content')->nullable()->comment('内容');
            $table->unsignedTinyInteger('grade')->default(0)->comment('综合评分');
            $table->unsignedTinyInteger('real_grade')->default(0)->comment('内容真实评分');
            $table->unsignedTinyInteger('serve_grade')->default(0)->comment('服务态度评分');
            $table->unsignedTinyInteger('quality_grade')->default(0)->comment('内容质量评分');

            $table->unsignedInteger('total')->default(0)->comment('消费合计');
            $table->unsignedTinyInteger('platform_way')->default(1)->comment('佣金分层方式，0能量，1金币');
            $table->unsignedDecimal('platform_rate', 12, 4)->default(0)->comment('分层比例');
            $table->unsignedDecimal('platform_commission', 12, 2)->default(0)->comment('平台佣金');
            $table->unsignedDecimal('received', 12, 2)->default(0.00)->comment('接收方实到能量');


            $table->unsignedTinyInteger('status')->default(9)->comment('状态 0:正常 1:禁用  9待审');
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
        Schema::dropIfExists('deal_comments');
    }
}

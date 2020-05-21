<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealSocialsTable extends Migration
{
    /**
     * 会员社交动态发布
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deal_socials', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('member_id')->default(0)->comment('会员ID');

            $table->string('name')->nullable()->comment('标题');
            $table->text('content')->comment('内容');
            $table->string('pic')->nullable()->comment('图片');
            $table->string('vido')->nullable()->comment('视频');

            $table->string('city')->nullable()->comment('所在城市');
            $table->string('lat')->nullable()->comment('经度');
            $table->string('lng')->nullable()->comment('纬度');

            $table->unsignedInteger('total')->default(0)->comment('消费合计');
            $table->unsignedTinyInteger('platform_way')->default(1)->comment('佣金分层方式，0能量，1金币');
            $table->unsignedDecimal('platform_rate', 12, 4)->default(0)->comment('分层比例');
            $table->unsignedDecimal('platform_commission', 12, 2)->default(0)->comment('平台佣金');
            $table->unsignedDecimal('received', 12, 2)->default(0.00)->comment('接收方实到能量');

            $table->unsignedInteger('audit_uid')->nullable()->comment('审核人');
            $table->dateTime('audit_time')->nullable()->comment('审核时间');
            $table->string('audit_reason')->nullable()->comment('审核意见');

            $table->unsignedTinyInteger('status')->default(0)->comment('状态 0:正常 1:禁用 2:冻结 ');
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
        Schema::dropIfExists('deal_socials');
    }
}

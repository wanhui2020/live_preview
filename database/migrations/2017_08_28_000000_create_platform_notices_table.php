<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * 系统公告
 * Class CreateMerchantsWalletTable
 */
class CreatePlatformNoticesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //系统公告
        Schema::create('platform_notices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedTinyInteger('type')->default(0)->comment('通知类型0系统1用户');
            $table->unsignedInteger('member_id')->default(0)->comment('所属会员');
            $table->string('name')->nullable()->comment('公告名称');
            $table->text('content')->nullable()->comment('公告内容');
            $table->string('url')->nullable()->comment('外部连接');
            $table->unsignedTinyInteger('sort')->default(0)->comment('排序');
            $table->unsignedTinyInteger('status')->default(0)->comment('状态0 正常 1禁用');
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
        Schema::dropIfExists('platform_notices');
    }
}

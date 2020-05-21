<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * 系统日志
 * Class CreateMerchantsTable
 */
class CreateSystemLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 系统日志
        Schema::create('system_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('relevance');
            $table->string('name')->nullable()->comment('标题');
            $table->string('type')->nullable()->comment('类型:0系统日志1策略日志2委托日志3券商日志4充值日志5提现日志6注册日志');
            $table->text('content')->nullable()->comment('日志内容');
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
        Schema::dropIfExists('system_logs');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWechatPayMoneyToMemberUserRateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('member_user_rate', function (Blueprint $table) {
            $table->decimal('wechat_pay_money', 12, 2)->default(0)->comment('微信查看花费金币');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('member_user_rate', function (Blueprint $table) {
            //
        });
    }
}

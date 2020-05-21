<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberWalletConversionsTable extends Migration
{
    /**
     * 会员金币兑换记录
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_wallet_conversions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('member_id')->default(0)->comment('所属会员');
//            $table->unsignedInteger('cash_id')->default(0)->comment('现金账户');
//            $table->unsignedInteger('gold_id')->default(0)->comment('金币账户');
            $table->string('no')->unique()->comment('单号');

            $table->tinyInteger('way')->default(1)->comment('兑换方式(0金币兑换现金，1现金兑换金币)');

            $table->unsignedDecimal('quantity',12,2)->default(0.00)->comment('兑换数量');
            $table->unsignedDecimal('received_quantity',12,2)->default(0.00)->comment('实到数量');
            $table->unsignedDecimal('commission_rate',12,2)->default(0.00)->comment('手续费费率');
            $table->unsignedDecimal('commission_quantity',12,2)->default(0.00)->comment('手续费数量');


            $table->unsignedInteger('audit_uid')->nullable()->comment('经办人');
            $table->string('audit_name')->nullable()->comment('经办人姓名');
            $table->dateTime('audit_time')->nullable()->comment('经办时间');
            $table->string('audit_reason')->nullable()->comment('经办意见');

            $table->unsignedTinyInteger('status')->default(9)->comment('状态(0审核通过 1审核拒绝 9待审核)');
            $table->unsignedTinyInteger('sort')->default(0)->comment('排序');
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
        Schema::dropIfExists('member_wallet_conversions');
    }
}

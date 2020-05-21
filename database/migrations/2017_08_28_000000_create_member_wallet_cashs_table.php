<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberWalletCashsTable extends Migration
{
    /**
     * 会员现金账户
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_wallet_cashs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('member_id')->default(0)->comment('所属会员');

            $table->decimal('balance',12,2)->default(0.00)->comment('余额');
            $table->decimal('usable',12,2)->default(0.00)->comment('可用金额包含锁定金额');
            $table->decimal('lock',12,2)->default(0.00)->comment('锁定金额');
            $table->decimal('freeze',12,2)->default(0.00)->comment('现金冻结余额(消费)');
            $table->decimal('platform',12,2)->default(0.00)->comment('现金平台冻结余额');

            $table->unsignedTinyInteger('status')->default(0)->comment('状态0 禁用 1正常');
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
        Schema::dropIfExists('member_wallet_cashs');
    }
}

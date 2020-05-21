<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberWalletGoldsTable extends Migration
{
    /**
     * 会员金币账户
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_wallet_golds', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('member_id')->default(0)->comment('所属会员');

            $table->decimal('balance',12,2)->default(0.00)->comment('余额');

            $table->decimal('usable',12,2)->default(0.00)->comment('可用余额包含锁定金币');
            $table->decimal('lock',12,2)->default(0.00)->comment('锁定不可兑换金币');

            $table->decimal('freeze',12,2)->default(0.00)->comment('金币通话冻结');
            $table->decimal('platform',12,2)->default(0.00)->comment('金币平台冻结');



            $table->unsignedTinyInteger('status')->default(0)->comment('状态0正常  1禁用');
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
        Schema::dropIfExists('member_wallet_golds');
    }
}

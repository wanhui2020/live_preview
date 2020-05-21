<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldToMemberWalletRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('member_wallet_records', function (Blueprint $table) {
            $table->unsignedInteger('to_member_id')->default(0)->comment('创建会员');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('member_wallet_records', function (Blueprint $table) {
            //
        });
    }
}

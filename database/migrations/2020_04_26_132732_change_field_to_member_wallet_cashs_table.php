<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeFieldToMemberWalletCashsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('member_wallet_cashs', function (Blueprint $table) {
            $table->unsignedDecimal('balance')->default(0.00)->comment('余额')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('member_wallet_cashs', function (Blueprint $table) {
            //
        });
    }
}

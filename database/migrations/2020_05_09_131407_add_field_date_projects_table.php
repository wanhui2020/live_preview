<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldDateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('date_projects', function (Blueprint $table) {
            $table->unsignedDecimal('price',10,2)->comment('默认价格');
            $table->unsignedDecimal('min_price',10,2)->comment('最低价');
            $table->unsignedDecimal('max_price',10,2)->comment('最高价');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('date_projects', function (Blueprint $table) {
            //
        });
    }
}

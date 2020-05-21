<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldToPlatformConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('platform_config', function (Blueprint $table) {
            $table->unsignedTinyInteger('is_screencap')->default(1)->comment('是否允许录屏,0是1否');
            $table->unsignedTinyInteger('is_deduction_calling_fee')->default(1)->comment('是否开启扣主要费用,0是1否');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('platform_config', function (Blueprint $table) {
            //
        });
    }
}

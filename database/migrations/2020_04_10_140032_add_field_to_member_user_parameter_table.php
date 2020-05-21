<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldToMemberUserParameterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('member_user_parameter', function (Blueprint $table) {
            $table->unsignedTinyInteger('is_screencap')->default(1)->comment('是否允许录屏,0是1否');
            $table->unsignedTinyInteger('is_answer_host_phonep')->default(0)->comment('是否接听主播呼叫,0是1否');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('member_user_parameter', function (Blueprint $table) {
            //
        });
    }
}

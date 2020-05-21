<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldToPlatformFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('platform_files', function (Blueprint $table) {
            $table->string('title')->nullable()->comment('标题');
            $table->string('describe')->nullable()->comment('描述');
            $table->string('front_cover')->nullable()->comment('封面图');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('platform_files', function (Blueprint $table) {
            //
        });
    }
}

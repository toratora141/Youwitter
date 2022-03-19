<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddThumnailFistVideoMovieListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('movie_lists', function (Blueprint $table) {
            $table->string('thumnail');
            $table->string('first_video');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('movie_lists', function (Blueprint $table) {
            //
            $table->dropColumn('thumnail');
            $table->dropColumn('first_video');
        });
    }
}

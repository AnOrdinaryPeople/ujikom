<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('replies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('post_id')->references('id')->on('posts');
            $table->bigInteger('user_id')->references('id')->on('users');
            $table->longText('desc');
            $table->integer('votes')->default(0);
            $table->tinyInteger('is_best')->default(0);
            $table->bigInteger('has_child_id')->nullable();
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
        Schema::dropIfExists('replies');
    }
}

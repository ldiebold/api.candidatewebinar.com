<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTutorialVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tutorial_videos', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->boolean('admin')->default(1);
            $table->string('title')->nullable();
            $table->text('body')->nullable();
            $table->string('video_url')->nullable();
            $table->string('display_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tutorial_videos');
    }
}

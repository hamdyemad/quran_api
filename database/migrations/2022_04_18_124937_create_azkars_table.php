<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAzkarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('azkars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('azkar_category_id');
            $table->text('elzekr');
            $table->text('about');
            $table->foreign('azkar_category_id')->on('azkar_categories')->references('id')->onDelete('cascade');
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
        Schema::dropIfExists('azkars');
    }
}

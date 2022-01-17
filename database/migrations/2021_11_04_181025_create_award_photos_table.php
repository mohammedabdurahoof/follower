<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAwardPhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('award_photos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_award_id');
            $table->string('photo')->nullable();
            $table->timestamps();
            
            $table->foreign('client_award_id')
                    ->references('id')
                    ->on('client_awards')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('award_photos');
    }
}

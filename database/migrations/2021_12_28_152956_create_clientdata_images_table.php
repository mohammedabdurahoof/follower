<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientdataImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientdata_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_data_id');
            $table->text('image_name')->nullable();
            $table->text('image_link')->nullable();
            $table->timestamps();
            
            $table->foreign('client_data_id')
                    ->references('id')
                    ->on('client_datas')
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
        Schema::dropIfExists('clientdata_images');
    }
}

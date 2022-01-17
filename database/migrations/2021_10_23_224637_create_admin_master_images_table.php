<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminMasterImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_master_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_master_id');
            $table->text('image_link')->nullable();
            $table->timestamps();
            
            $table->foreign('admin_master_id')
                    ->references('id')
                    ->on('admin_masters')
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
        Schema::dropIfExists('admin_master_images');
    }
}

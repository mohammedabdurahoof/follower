<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_masters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('service_id');
            $table->date('file_uploaded_date')->nullable();
            $table->tinyInteger('user_display_type')->nullable();
            $table->tinyInteger('file_type')->nullable();
            $table->text('file_link');
            $table->timestamps();
            
            $table->foreign('organization_id')
                    ->references('id')
                    ->on('organizations')
                    ->onDelete('cascade');
            
            $table->foreign('service_id')
                    ->references('id')
                    ->on('services')
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
        Schema::dropIfExists('admin_master_models');
    }
}

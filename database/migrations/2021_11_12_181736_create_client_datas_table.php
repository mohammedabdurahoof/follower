<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_datas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('service_id');
            $table->date('file_uploaded_date')->nullable();
            $table->tinyInteger('file_type')->nullable();
            $table->text('file_link')->nullable();
            $table->timestamps();
            
            $table->foreign('client_id')
                    ->references('id')
                    ->on('clients')
                    ->onDelete('cascade');
            
            $table->foreign('organization_id')
                    ->references('id')
                    ->on('organizations')
                    ->onUpdate('cascade');
            
            $table->foreign('service_id')
                    ->references('id')
                    ->on('services')
                    ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_data');
    }
}

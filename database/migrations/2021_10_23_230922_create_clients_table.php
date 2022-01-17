<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id');
            $table->string('name')->nullable();
            $table->bigInteger('mobile')->nullable();
            $table->date('dob')->nullable();
            $table->string('password');
            $table->date('dom')->nullable();
            $table->string('email')->nullable();
            $table->string('place')->nullable();
            $table->string('cadre')->nullable();
            $table->string('product')->nullable();
            $table->date('p_date')->nullable();
            $table->date('r_date')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->rememberToken();
            $table->timestamps();
            
            $table->foreign('organization_id')
                    ->references('id')
                    ->on('organizations')
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
        Schema::dropIfExists('clients');
    }
}

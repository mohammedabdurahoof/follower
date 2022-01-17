<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->bigInteger('mobile')->nullable();
            $table->date('dob')->nullable();
            $table->string('password');
            $table->string('policy_number', 200)->nullable();
            $table->decimal('sum_assured')->nullable();
            $table->date('ptt')->nullable();
            $table->string('mode', 50)->nullable();
            $table->decimal('premium')->nullable();
            $table->date('fup')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('customers');
    }
}

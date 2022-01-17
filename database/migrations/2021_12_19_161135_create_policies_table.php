<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePoliciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('policies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('organization_id');
            $table->string('policy_number', 200)->nullable();
            $table->date('doc')->nullable();
            $table->string('plan_no', 10)->nullable();
            $table->integer('term')->nullable();
            $table->string('ppt', 50)->nullable();
            $table->string('prem_mode', 50)->nullable();
            $table->unsignedBigInteger('sum_assured')->nullable();
            $table->unsignedBigInteger('premium')->nullable();
            $table->date('fup_date')->nullable();
            $table->string('policy_status', 10)->nullable();
            $table->date('maturity_date')->nullable();
            $table->date('prem_end_date')->nullable();
            $table->string('d_no', 50)->nullable();
            $table->string('address', 100)->nullable();
            $table->string('place', 50)->nullable();
            $table->unsignedBigInteger('pin')->nullable();
            $table->string('nominee', 100)->nullable();
            $table->string('nominee_r', 50)->nullable();
            $table->string('branch', 50)->nullable();
            $table->unsignedBigInteger('agent_code')->nullable();
            $table->timestamps();
            
            $table->foreign('customer_id')
                    ->references('id')
                    ->on('customers')
                    ->onDelete('cascade');
            
            $table->foreign('client_id')
                    ->references('id')
                    ->on('clients')
                    ->onDelete('cascade');
            
            $table->foreign('service_id')
                    ->references('id')
                    ->on('services')
                    ->onDelete('cascade');
            
            $table->foreign('organization_id')
                    ->references('id')
                    ->on('organizations')
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
        Schema::dropIfExists('policies');
    }
}

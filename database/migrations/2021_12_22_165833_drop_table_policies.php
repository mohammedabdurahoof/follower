<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropTablePolicies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('policies');
        Schema::table('customers', function(Blueprint $table){
            $table->dropColumn('policy_number');
            $table->dropColumn('sum_assured');
            $table->dropColumn('ptt');
            $table->dropColumn('mode');
            $table->dropColumn('premium');
            $table->dropColumn('fup');
            $table->dropColumn('status');
        });
    }

}

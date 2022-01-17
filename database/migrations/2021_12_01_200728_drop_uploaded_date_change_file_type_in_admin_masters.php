<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropUploadedDateChangeFileTypeInAdminMasters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admin_masters', function (Blueprint $table) {
            $table->dropColumn('file_uploaded_date');
            $table->string('file_type', 20)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admin_masters', function (Blueprint $table) {
            //
        });
    }
}

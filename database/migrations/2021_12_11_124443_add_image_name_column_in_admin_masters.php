<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImageNameColumnInAdminMasters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admin_masters', function (Blueprint $table) {
            $table->string('file_name', 50)->nullable()->after('file_type');
        });
        Schema::table('admin_master_images', function (Blueprint $table) {
            $table->string('image_name', 50)->nullable()->after('admin_master_id');
        });
    }
}

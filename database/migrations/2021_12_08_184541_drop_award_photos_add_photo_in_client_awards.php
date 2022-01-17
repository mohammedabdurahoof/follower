<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropAwardPhotosAddPhotoInClientAwards extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('award_photos');
        Schema::table('client_awards', function (Blueprint $table) {
            $table->text('photo')->after('achieved_date')->nullable();
        });
    }

}

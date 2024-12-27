<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code',16);
            $table->json('device_info')->nullable(); // Allows storage of JSON data
            $table->string('app_version',255)->nullable();
            $table->string('screen_w',16)->nullable();
            $table->string('screen_h',16)->nullable();
            $table->string('pin',4)->unique();
           


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
        Schema::dropIfExists('devices_codes');
    }
};

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
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('code',16);
            $table->string('pin',4)->unique();
            $table->string('name');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('form_id')->nullable()->constrained('forms')->onDelete('cascade');
            $table->string('remark')->nullable();
            $table->string('tag');
            $table->string('rotation');

    $table->json('device_info')->nullable(); // Allows storage of JSON data
            $table->string('app_version',255)->nullable();
            $table->string('screen_w',16)->nullable();
            $table->string('screen_h',16)->nullable();

            $table->string('background_music_path')->nullable();


            $table->timestamps();


            $table->index('user_id');
            $table->index('form_id');  
    

        });

       
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('devices');
    }
};

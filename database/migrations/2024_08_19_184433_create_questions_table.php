<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained('forms')->onDelete('cascade');
            $table->foreignId('type_id')->constrained('questions_types')->onDelete('cascade');
            $table->boolean('with_answers')->default(true);
            $table->boolean('optional')->default(false);
            $table->string('answers_view_mode')->nullable();
            $table->integer('order');
            $table->timestamps();

            $table->index('form_id'); 

        });


        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};

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
        Schema::create('forms_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained('forms')->onDelete('cascade');
            $table->enum('language', ['lang1', 'lang2','lang3'])->default('lang1');
            $table->string('start_header');
            $table->string('start_message');
            $table->string('end_header');
            $table->string('end_message');
            $table->string('language_name');
            $table->boolean('enable')->default(true);
            $table->timestamps();

            $table->index('form_id'); 

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forms_translations');
    }
};

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
        Schema::create('questions_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('questions_categories')->onDelete('cascade');
            $table->string('type_text',50);
            $table->string('question_type_details',150);
            $table->string('question_type_details_ar',150);
            $table->string('image',1000);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions_types');
    }
};

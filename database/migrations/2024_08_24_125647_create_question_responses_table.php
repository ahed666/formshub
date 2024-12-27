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
        Schema::create('question_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('response_id')->constrained('responses')->onDelete('cascade'); // Links to the form response
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade'); // The question that was answered
            $table->foreignId('answer_id')->nullable()->constrained('answers')->onDelete('cascade'); // The predefined answer (nullable for text responses)
            $table->text('text_response')->nullable(); // The text response for text-based questions
            $table->boolean('skip')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_responses');
    }
};

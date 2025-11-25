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
        Schema::create('file_of_recommendations', function (Blueprint $table) {
            $table->id();
            //file _path
            $table->string('file_path',100);
            //recommendation_id
            $table->foreignId('recommendation_id')
                  ->constrained('recommendations')
                  ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_of_recommendations');
    }
};

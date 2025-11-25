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
        Schema::create('influencers', function (Blueprint $table) {
               $table->foreignId('id')
          ->constrained('users')
          ->cascadeOnDelete()
          ->primary();
          $table->double('rating')->default(0);
          $table->string('bio',512)->nullable();
          $table->string('gender',10)->nullable();
          $table->date('date_of_birth')->nullable();
          $table->integer('shake_number')->nullable();
          $table->foreignId('type_id')
                  ->constrained('influencer_types')
                  ->cascadeOnDelete();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('influencers');
    }
};

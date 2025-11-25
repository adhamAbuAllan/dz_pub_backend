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
        Schema::create('influancher_movments', function (Blueprint $table) {
            $table->id();
            //location
            $table->string('location',100);
            //promation_id
            $table->foreignId('promation_id')
                  ->constrained('promations')
                  ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('influancher_movments');
    }
};

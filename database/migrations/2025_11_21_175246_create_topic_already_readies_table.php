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
        Schema::create('topic_already_readies', function (Blueprint $table) {
            $table->id();
            //promation_id
            $table->foreignId('promation_id')
                  ->constrained('promations')
                    ->cascadeOnDelete();
           
                  //file_path
            $table->string('file_path',100);

            

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topic_already_readies');
    }
};

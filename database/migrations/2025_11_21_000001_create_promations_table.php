<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// Schema::disableForeignKeyConstraints();

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('promations', function (Blueprint $table) {
            $table->id();
            //client_id
            $table->foreignId('client_id')
                  ->constrained('clients')
                  ->cascadeOnDelete();
                  //influencer_id
            $table->foreignId('influencer_id')
                  ->constrained('influencers')
                  ->cascadeOnDelete();
                  // requirements
            $table->text('requirements',512)->nullable();
            //status_id  from promation_statuses table
            $table->foreignId('status_id')
                  ->constrained('promation_statuses')
                  ->cascadeOnDelete()->default(1);
                  //price
            $table->double('price')->default(0);
            //time_line
            $table->string('time_line',50)->nullable();
            //should_influencer_movment
            $table->string('should_influencer_movment',3)->default('no');//yes/no



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promations');
    }
};

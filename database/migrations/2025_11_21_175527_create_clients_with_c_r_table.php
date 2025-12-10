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
        Schema::create('clients_with_cr', function (Blueprint $table) {
            // id and client id both are same
            $table->foreignId('client_id')
                  ->constrained('clients')
                  ->cascadeOnDelete();
                  //reg_owner_name
            $table->string('reg_owner_name',50);
            //institution_name
            $table->string('institution_name',50);
            //branch_address
            $table->string('branch_address',100);
            //institution_address
            $table->string('institution_address',100);
            //rc_number unique
            $table->text('rc_number');
            //nis number
            $table->text('nis_number');
            $table ->text('nif_number');
            //iban unique
            $table->text('iban');
            //image_of_license
            $table->text('image_of_license');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_with_c_r_s');
    }
};

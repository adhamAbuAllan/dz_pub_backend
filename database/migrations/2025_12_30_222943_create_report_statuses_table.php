<?php   
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('report_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // pending, reviewed, rejected, resolved
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_statuses');
    }
};

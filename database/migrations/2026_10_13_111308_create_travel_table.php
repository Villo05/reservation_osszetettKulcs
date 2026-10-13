<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void
    {
        Schema::create('travel', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained();
            $table->foreignId('flight_id')->constrained();
            $table->string('evaluation');
            $table->primary(['user_id', 'flight_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('travel'); }
};

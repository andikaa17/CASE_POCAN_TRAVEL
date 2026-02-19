<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buses', function (Blueprint $table) {
            $table->id();
            $table->string('bus_number')->unique();
            $table->string('name');
            $table->enum('type', ['eksekutif', 'bisnis', 'ekonomi'])->default('eksekutif');
            $table->integer('total_seats');
            $table->json('facilities')->nullable();
            $table->enum('status', ['active', 'maintenance'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buses');
    }
};
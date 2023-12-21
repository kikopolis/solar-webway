<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('providers', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->string('price');
            $table->dateTimeTz('flight_start');
            $table->dateTimeTz('flight_end');
            $table->foreignId('company_id')->constrained();
            $table->foreignId('leg_id')->constrained();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('providers');
    }
};

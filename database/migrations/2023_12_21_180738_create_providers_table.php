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
            $table->text('price');
            $table->dateTime('flight_start');
            $table->dateTime('flight_end');
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('leg_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('providers');
    }
};

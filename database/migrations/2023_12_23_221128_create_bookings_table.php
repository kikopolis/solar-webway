<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('first_name');
            $table->string('last_name');
            $table->integer('total_price');
            $table->integer('total_distance');
            $table->integer('duration_in_minutes');
            $table->timestamp('departure');
            $table->timestamp('arrival');
            $table->json('legs');
            $table->foreignId('company_id')->constrained();
            $table->foreignId('price_list_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }
    
    public function down(): void {
        Schema::dropIfExists('bookings');
    }
};

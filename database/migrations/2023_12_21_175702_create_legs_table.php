<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('legs', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->foreignId('price_list_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }
    
    public function down(): void {
        Schema::dropIfExists('legs');
    }
};

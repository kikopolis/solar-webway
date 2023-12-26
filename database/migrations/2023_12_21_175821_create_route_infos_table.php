<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('route_infos', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->foreignId('from_id')->constrained()->references('id')->on('planets');
            $table->foreignId('to_id')->constrained()->references('id')->on('planets');
            $table->string('distance');
            $table->foreignId('leg_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }
    
    public function down(): void {
        Schema::dropIfExists('route_infos');
    }
};

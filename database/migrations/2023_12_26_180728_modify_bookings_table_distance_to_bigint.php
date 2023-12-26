<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('bigint', function (Blueprint $table) {
            $table->bigInteger('total_distance')->change();
        });
    }
    
    public function down(): void {
        Schema::table('bigint', function (Blueprint $table) {
            $table->integer('total_distance')->change();
        });
    }
};

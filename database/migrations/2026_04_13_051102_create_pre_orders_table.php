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
        Schema::create('pre_orders', function (Blueprint $table) {
            $table->id();
            $table->date('actual_periode')->nullable();
            $table->string('status', 30);
            $table->date('start_periode')->nullable();
            $table->date('end_periode')->nullable();
            $table->string('send_type', 50);
            $table->char('tracking_number', 50)->nullable();
            $table->string('choosen_expedition', 90)->nullable();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pre_orders');
    }
};

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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trainer_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description');
            $table->integer('price');
            $table->enum('plan_type', ['single', 'ticket', 'monthly']); // 単発・回数券・月額
            $table->integer('duration_minutes')->nullable(); // 1回あたりの時間(分)
            $table->boolean('is_active')->default(true); // 有効/無効フラグ
            $table->integer('session_count')->nullable(); // 回数券の場合の回数
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};

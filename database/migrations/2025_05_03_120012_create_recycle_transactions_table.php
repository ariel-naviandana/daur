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
        Schema::create('recycle_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('bank_id')->nullable()->constrained()->onDelete('set null');
            $table->text('pickup_address')->nullable();
            $table->datetime('appointment_time');
            $table->text('note')->nullable();
            $table->enum('method', ['pickup', 'dropoff']);
            $table->enum('status', ['waiting', 'process', 'cancel', 'success'])->default('waiting');
            $table->decimal('total_quantity', 10, 2)->nullable();
            $table->decimal('total_amount', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recycle_transactions');
    }
};

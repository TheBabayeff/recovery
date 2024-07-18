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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('number', 32)->unique();
            $table->foreignId('operator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('engineer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->foreignId('diagnostic_id')->nullable()->constrained('diagnostics')->nullOnDelete();
            $table->enum('status', ['new', 'technical_examination', 'processing', 'in_agreement', 'cancelled', 'ready' , 'done'])->default('new');
            $table->longText('note')->nullable();
            $table->longText('engineer_note')->nullable();
            $table->decimal('total_price', 12, 2)->nullable();
            $table->string('device_model')->nullable();
            $table->string('device_serial_number')->nullable();
            $table->longText('device_appearance')->nullable();
            $table->date('finished_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};

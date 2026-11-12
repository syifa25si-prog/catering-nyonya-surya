<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->string('number')->unique();
            $table->string('customer_name');
            $table->string('phone', 25);
            $table->text('address');
            $table->date('event_date');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('total');
            $table->string('status')->default('Menunggu')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

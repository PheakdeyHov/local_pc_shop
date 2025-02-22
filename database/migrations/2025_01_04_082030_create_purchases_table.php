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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('purchase_code')->unique();
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->string('shipping_company');
            $table->decimal('shipping_price',10,2);
            $table->enum('shipping_status' , ['ordered','pending','recieved']);
            $table->decimal('paid_price',10,2);
            $table->decimal('notpaid_price',10,2);
            $table->decimal('total_price',10,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};

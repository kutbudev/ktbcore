<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('financial_transactions', function (Blueprint $table) {
            $table->id();
            $table->date('transaction_date');
            $table->timestamps();
            $table->text('description');
            $table->decimal('amount', 10, 2);
            $table->foreignId('category_id')->constrained('transaction_categories');
            $table->enum('type', ['income', 'expense', 'both']);
            $table->foreignId('account_id')->constrained('accounts');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_transactions');
    }
};

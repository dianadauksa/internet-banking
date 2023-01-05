<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_from_id');
            $table->unsignedBigInteger('account_to_id');
            $table->decimal('amount', 8, 2);
            $table->string('currency', 3);
            $table->string('type', 8);
            $table->timestamps();
            $table->foreign('account_from_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('account_to_id')->references('id')->on('accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};

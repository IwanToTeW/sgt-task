<?php

use App\Enums\CurrencyPair;
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
        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->enum('pair', array_column(CurrencyPair::cases(), 'value'))->index();
            $table->dateTime('date')->index();
            $table->decimal('ask')->index();
            $table->decimal('bid')->index();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prices');
    }
};

<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockTables extends Migration
{
    public function up(): void
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->string('id_hash')->unique();
            $table->string('ticker')->unique();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('stock_prices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('id_hash')->unique();
            $table->string('stock_id');
            $table->string('name');
            $table->foreign('stock_id')->references('ticker')->on('stocks')->onDelete('cascade');
            $table->date('price_date');
            $table->string('isin');
            $table->string('currency');
            $table->string('market_place')->nullable();
            $table->string('list_segment')->nullable();
            $table->string('average_price')->nullable();
            $table->string('open_price')->nullable();
            $table->string('high_price')->nullable();
            $table->string('low_price')->nullable();
            $table->string('last_close_price')->nullable();
            $table->string('last_price')->nullable();
            $table->string('price_change')->nullable();
            $table->string('best_bid')->nullable();
            $table->string('best_ask')->nullable();
            $table->string('trades')->nullable();
            $table->string('volume')->nullable();
            $table->string('turnover')->nullable();
            $table->string('industry')->nullable();
            $table->string('supersector')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('stock_prices', function (Blueprint $table) {
            $table->dropForeign('stock_prices_stock_id_foreign');
            $table->dropColumn('stock_id');
        });

        Schema::dropIfExists('stocks');
        Schema::dropIfExists('stock_prices');
    }
}

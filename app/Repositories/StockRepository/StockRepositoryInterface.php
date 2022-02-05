<?php

declare(strict_types=1);

namespace App\Repositories\StockRepository;

use App\Models\Date;
use App\Models\Stock;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Collection;

interface StockRepositoryInterface
{
    public function createStock(string $ticker, string $name): Stock;

    public function getStocks(array $stocks): array;

    public function getDate(DateTimeInterface $date): Date;

    public function createDate(DateTimeInterface $date): Date;

    /**
     * @param array<string, string|DateTimeInterface> $stockPrices
     */
    public function stockPricesCreate(array $stockPrices): void;

    public function findStock(string $stock): ?Stock;

    public function findStockPrices(Stock $stock): Collection;
}

<?php

declare(strict_types=1);

namespace App\Repositories\StockRepository;

use App\Models\Date;
use App\Models\Stock;
use App\Models\StockPrice;
use DateTimeInterface;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StockRepository implements StockRepositoryInterface
{
    public function createStock(string $ticker, string $name): Stock
    {
        return Stock::create([
            'id_hash' => mb_substr(md5(uniqid('', true)), 0, 28),
            'ticker' => $ticker,
            'name' => $name
        ]);
    }

    /**
     * @param array<string> $stocks
     * @return array<Stock>
     */
    public function getStocks(array $stocks): array
    {
        return Stock::whereIn('ticker', $stocks)->get()->toArray();
    }

    public function getDate(DateTimeInterface $date): Date
    {
        return Date::where('date', $date->format('Y-m-d'))->firstOrFail();
    }

    public function createDate(DateTimeInterface $date): Date
    {
        return Date::create([
            'id_hash' => mb_substr(md5(uniqid('', true)), 0, 28),
            'date' => $date
        ]);
    }

    public function stockPricesCreate(array $stockPrices): void
    {
        StockPrice::insert($stockPrices);
    }
}

<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Date;
use App\Models\Stock;
use App\Repositories\StockRepository\StockRepository;
use Carbon\Carbon;
use Database\Factories\DateFactory;
use Database\Factories\StockFactory;
use Database\Factories\StockPriceFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class StockRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    public function testCreateStock(): void
    {
        Mockery::mock(StockRepository::class,
            static function (MockInterface $mock) {
            $mock->shouldReceive('createStock')
                ->with('ticker', 'name')
                ->andReturn(new Stock(['ticker', 'name']));
        });

        $repository = new StockRepository();
        $stock = $repository->createStock('ticker', 'name');
        $this->assertInstanceOf(Stock::class, $stock);
        $this->assertEquals('ticker', $stock->ticker);

        $this->assertDatabaseHas('stocks',[
            'ticker' => 'ticker'
        ]);
    }

    public function testGetStocks(): void
    {
        $stock = StockFactory::new()->create([
            'ticker' => 'test'
        ]);

        $stock2 = StockFactory::new()->create([
            'ticker' => 'testing2',
            'name' => 'test'
        ]);

        $repository = new StockRepository();
        $stocks = $repository->getStocks(['test', 'testing2']);
        $this->assertIsArray($stocks);
        $this->assertCount(2, $stocks);
        $this->assertEquals('test', $stocks[0]['ticker']);
    }

    public function testCreateDate(): void
    {
        $repository = new StockRepository();
        $date = $repository->createDate(Carbon::createFromFormat('Y-m-d', '2022-02-05'));
        $this->assertInstanceOf(Date::class, $date);
        $this->assertEquals('2022-02-05', $date->date->format('Y-m-d'));

        $this->assertDatabaseHas('processed_dates', [
            'date' => '2022-02-05'
        ]);
    }

    public function testGetDate(): void
    {
        DateFactory::new()->create();

        $repository = new StockRepository();
        $date = $repository->getDate(Carbon::createFromFormat('Y-m-d', '2022-02-05'));
        $this->assertInstanceOf(Date::class, $date);
        $this->assertEquals('2022-02-05', $date->date->format('Y-m-d'));
    }

    public function testDoesntHaveDateStore(): void
    {
        $this->expectException(ModelNotFoundException::class);
        $repository = new StockRepository();
        $repository->getDate(Carbon::createFromFormat('Y-m-d', '2022-02-05'));
    }

    public function testCreateStockPrices(): void
    {
        $stock = StockFactory::new()->create();

        $stocksPrice = [
            'id_hash' => mb_substr(md5(uniqid('', true)), 0, 28),
            'stock_id' => 'AMG1L',
            'name' => 'Amber Grid',
            'price_date' => '2022-02-05',
            'isin' => 123,
            'currency' => 1234,
            'market_place' => 214215,
            'list_segment' => 1231231,
            'average_price' => 12312321,
            'open_price' => 123213,
            'high_price' => 123123123,
            'low_price' => 213123311,
            'last_close_price' => 123123213,
            'last_price' => 12312111,
            'price_change' => 33333,
            'best_bid' => 12321322,
            'best_ask' => 1231211,
            'trades' => 213111,
            'volume' => 4242321,
            'turnover' => 121111,
            'industry' => 23123344,
            'supersector' => 123211122,
        ];

        $repository = new StockRepository();
        $repository->stockPricesCreate($stocksPrice);

        $this->assertDatabaseHas('stock_prices', [
            'stock_id' => 'AMG1L'
        ]);
    }

    public function testFindStockByName(): void
    {
        StockFactory::new()->create([
            'name' => 'test',
            'ticker' => 'dasdasd'
        ]);

        $repository = new StockRepository();
        $stock = $repository->findStock('test');
        $this->assertInstanceOf(Stock::class, $stock);
        $this->assertEquals('test', $stock->name);
    }

    public function testFindStockByTicker(): void
    {
        StockFactory::new()->create([
            'name' => 'test',
            'ticker' => 'dasdasd'
        ]);

        $repository = new StockRepository();
        $stock = $repository->findStock('dasdasd');
        $this->assertInstanceOf(Stock::class, $stock);
        $this->assertEquals('dasdasd', $stock->ticker);
    }

    public function testStockNotFound(): void
    {
        StockFactory::new()->create([
            'name' => 'test',
            'ticker' => 'dasdasd'
        ]);

        $repository = new StockRepository();
        $stock = $repository->findStock('pew');
        $this->assertNull($stock);
    }

    public function testFindStockPrices(): void
    {
        $stock = StockFactory::new()->create(['ticker' => 'test']);
        StockPriceFactory::new()->create(['stock_id' => 'test']);

        $repository = new StockRepository();
        $stock = $repository->findStockPrices($stock);
        $this->assertEquals(1,$stock->count());
    }

    public function testThereAreNoStockPrices(): void
    {
        $stock = StockFactory::new()->create(['ticker' => 'test']);

        $repository = new StockRepository();
        $stock = $repository->findStockPrices($stock);
        $this->assertEquals(0,$stock->count());
    }
}

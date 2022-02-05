<?php

namespace App\Console\Commands;

use App\Models\Date;
use App\Models\Stock;
use App\Models\StockPrice;
use App\Services\ApiService\ApiClientInterface;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use RuntimeException;

class DownloadStocksCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'download:stocks {date? : Enter date in format: 2022-01-31.}';

    /**
     * @var string
     */
    protected $description = 'Download stock prices from nasdaq baltics.';

    private ApiClientInterface $apiClient;
    private Xlsx $xlsReader;
    private ?DateTimeInterface $date;

    public function __construct(ApiClientInterface $apiClient, Xlsx $xlsReader)
    {
        parent::__construct();

        $this->apiClient = $apiClient;
        $this->xlsReader = $xlsReader;
    }

    public function handle(): int
    {
        $this->date = Carbon::createFromFormat('Y-m-d', $this->argument('date'));
        if (null === $this->date) {
            $this->date = Carbon::createFromFormat('Y-m-d', Carbon::now());
            $this->info('No date were specified, using today\'s day: ' . $this->date->format('Y-m-d'));
        }

        try {
            if (Date::where('date', $this->date->format('Y-m-d'))->firstOrFail()) {
                return 1;
            }
        } catch (ModelNotFoundException $e) {
            Date::create([
                'id_hash' => mb_substr(md5(uniqid('', true)), 0, 28),
                'date' => $this->date
            ]);
        }

        $this->apiClient->downloadStocks($this->date);

        $this->processFile('storage/stocks.xlsx');

        unlink('storage/stocks.xlsx');

        return 1;
    }

    private
    function processFile(string $fileName): void
    {
        if (!$this->xlsReader->canRead($fileName)) {
            throw new RuntimeException('Cannot read the input file');
        }
        $book = $this->xlsReader->load($fileName);
        $sheet = $book->getActiveSheet();

        $stockNames = [];
        $stockTickers = [];
        $rowCounter = 0;
        foreach ($sheet->getColumnIterator() as $column) {
            foreach ($column->getCellIterator(2) as $cell) {
                if ($rowCounter === 0) {
                    $stockTickers[] = $cell->getValue();
                } elseif ($rowCounter === 1) {
                    $stockNames[] = $cell->getValue();
                }
            }
            $rowCounter++;

            if ($rowCounter > 1) {
                break;
            }
        }

        $stocks = array_combine($stockTickers, $stockNames);

        $savedStocks = Stock::whereIn('ticker', $stockTickers)->get()->toArray();

        if (count($savedStocks) !== count($stockTickers)) {
            foreach ($stocks as $stockTicker => $stockName) {
                if (false === array_search($stockTicker, array_column($savedStocks, 'ticker'))) {
                    Stock::create([
                        'id_hash' => mb_substr(md5(uniqid('', true)), 0, 28),
                        'ticker' => $stockTicker,
                        'name' => $stockName
                    ]);
                }
            }
        }

        $rows = $sheet->toArray();
        array_shift($rows);
        foreach ($rows as $row) {
            StockPrice::create([
                'id_hash' => mb_substr(md5(uniqid('', true)), 0, 28),
                'stock_id' => $row[0],
                'price_date' => $this->date,
                'isin' => $row[2],
                'currency' => $row[3],
                'market_place' => $row[4],
                'list_segment' => $row[5],
                'average_price' => $row[6],
                'open_price' => $row[7],
                'high_price' => $row[8],
                'low_price' => $row[9],
                'last_close_price' => $row[10],
                'last_price' => $row[11],
                'price_change' => $row[12],
                'best_bid' => $row[13],
                'best_ask' => $row[14],
                'trades' => $row[15],
                'volume' => $row[16],
                'turnover' => $row[17],
                'industry' => $row[18],
                'supersector' => $row[19]
            ]);
        }
    }
}

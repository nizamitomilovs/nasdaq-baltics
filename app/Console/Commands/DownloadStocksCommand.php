<?php

namespace App\Console\Commands;

use App\Repositories\StockRepository\StockRepositoryInterface;
use App\Services\ApiService\ApiClientInterface;
use DateTime;
use DateTimeInterface;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use RuntimeException;

class DownloadStocksCommand extends Command
{
    public const FILE_NAME = 'storage/stocks.xlsx';

    /**
     * @var string
     */
    protected $signature = 'download:stocks {date? : Enter date in format: 2022-01-31.}';

    /**
     * @var string
     */
    protected $description = 'Download stock prices from nasdaq baltics.';

    private ApiClientInterface $apiClient;
    private StockRepositoryInterface $stockRepository;
    private Xlsx $xlsReader;
    private ?DateTimeInterface $date;

    public function __construct(ApiClientInterface $apiClient, Xlsx $xlsReader, StockRepositoryInterface $stockRepository)
    {
        parent::__construct();

        $this->apiClient = $apiClient;
        $this->stockRepository = $stockRepository;
        $this->xlsReader = $xlsReader;
    }

    public function handle(): int
    {
        $inputDate = $this->argument('date');
        if (null !== $inputDate && DateTime::createFromFormat('Y-m-d', $inputDate) == false) {
            //check for valid date format
            $this->info('Please provide valid data format 2022-01-05.');
            return 1;
        } elseif (null === $inputDate) {
            //if not date specified will use current date
            $this->date = date_create();
            $this->info('No date were specified, using today\'s day: ' . $this->date->format('Y-m-d'));
        } else {
            $this->date = date_create($inputDate);
        }

        try {
            if ($this->stockRepository->getDate($this->date)) {
                $this->info('This date was already downloaded: ' . $this->date->format('Y-m-d'));
                return 1;
            }
        } catch (ModelNotFoundException $e) {
            //just continue
        }

        $this->info('Downloading stocks...');

        //will throw exception, if can't download file
        $this->apiClient->downloadStocks($this->date->format('Y-m-d'));

        $this->info('Download complete, starting processing.');
        $stockPrices = [];
        $this->processFile(
            static function (array $row) use (&$stockPrices) {
                $stockPrices[] = $row;
            });

        $this->stockRepository->stockPricesCreate($stockPrices);

        //when processed save date
        $this->stockRepository->createDate($this->date);
        $this->info('Processing complete.');

        unlink(self::FILE_NAME);

        return 1;
    }

    private function processFile(callable $operation): void
    {
        if (!$this->xlsReader->canRead(self::FILE_NAME)) {
            throw new RuntimeException('Cannot read the input file.');
        }

        $book = $this->xlsReader->load(self::FILE_NAME);
        $sheet = $book->getActiveSheet();

        if (3 > $sheet->getHighestRow()) {
            throw new RuntimeException('Didn\'t find any stocks in the file for date: ' . $this->date->format('Y-m-d'));
        }

        $this->checkAndSaveStockNames($sheet);

        for ($row = 2; $row <= $sheet->getHighestRow(); $row++) {
            $operation($this->convertStockPriceRows($sheet, $row));
        }
    }

    private function checkAndSaveStockNames(Worksheet $sheet): void
    {
        $stockNames = [];
        $stockTickers = [];

        for ($row = 2; $row <= $sheet->getHighestRow(); $row++) {
            $stockTickers[] = $sheet->getCellByColumnAndRow(1, $row)->getValue();
            $stockNames[] = $sheet->getCellByColumnAndRow(2, $row)->getValue();
        }

        $stocks = array_combine($stockTickers, $stockNames);
        $savedStocks = $this->stockRepository->getStocks($stockTickers);

        if (count($savedStocks) !== count($stockTickers)) {
            foreach ($stocks as $stockTicker => $stockName) {
                if (false === array_search($stockTicker, array_column($savedStocks, 'ticker'))) {
                    $this->stockRepository->createStock($stockTicker, $stockName);
                }
            }
        }
    }

    /**
     * @return array<string, string|DateTimeInterface>
     */
    private function convertStockPriceRows(Worksheet $sheet, int $row): array
    {
        return [
            'id_hash' => mb_substr(md5(uniqid('', true)), 0, 28),
            'stock_id' => (string)$sheet->getCell('A' . $row)->getValue(),
            'name' => (string)$sheet->getCell('B' . $row)->getValue(),
            'price_date' => $this->date,
            'isin' => (string)$sheet->getCell('C' . $row)->getValue(),
            'currency' => (string)$sheet->getCell('D' . $row)->getValue(),
            'market_place' => (string)$sheet->getCell('E' . $row)->getValue(),
            'list_segment' => (string)$sheet->getCell('F' . $row)->getValue(),
            'average_price' => (string)$sheet->getCell('G' . $row)->getValue(),
            'open_price' => (string)$sheet->getCell('H' . $row)->getValue(),
            'high_price' => (string)$sheet->getCell('I' . $row)->getValue(),
            'low_price' => (string)$sheet->getCell('J' . $row)->getValue(),
            'last_close_price' => (string)$sheet->getCell('K' . $row)->getValue(),
            'last_price' => (string)$sheet->getCell('L' . $row)->getValue(),
            'price_change' => (string)$sheet->getCell('M' . $row)->getValue(),
            'best_bid' => (string)$sheet->getCell('N' . $row)->getValue(),
            'best_ask' => (string)$sheet->getCell('O' . $row)->getValue(),
            'trades' => (string)$sheet->getCell('P' . $row)->getValue(),
            'volume' => (string)$sheet->getCell('Q' . $row)->getValue(),
            'turnover' => (string)$sheet->getCell('R' . $row)->getValue(),
            'industry' => (string)$sheet->getCell('S' . $row)->getValue(),
            'supersector' => (string)$sheet->getCell('T' . $row)->getValue(),
        ];
    }
}

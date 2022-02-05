<?php

declare(strict_types=1);

namespace App\Services\ApiService;

use App\Console\Commands\DownloadStocksCommand;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use RuntimeException;

class ApiClient implements ApiClientInterface
{
    private string $stockUrl;
    private ClientInterface $http;

    public function __construct(string $stockUrl, ClientInterface $http)
    {
        $this->stockUrl = $stockUrl;
        $this->http = $http;
    }

    public function downloadStocks(string $date): void
    {
        try {
            $resource = fopen(DownloadStocksCommand::FILE_NAME, 'w');
            $this->http->request('GET', $this->stockUrl . $date, [
                'sink' => $resource,
            ]);

        } catch (GuzzleException $e) {
            throw new RuntimeException($e->getMessage());
        }
    }
}

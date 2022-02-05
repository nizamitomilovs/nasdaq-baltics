<?php

declare(strict_types=1);

namespace App\Services\ApiService;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;

class ApiClient implements ApiClientInterface
{
    private const API_URL = 'https://nasdaqbaltic.com/statistics/lv/shares?download=1&date=';
    private const FILE_NAME = 'storage/stocks.xlsx';

    private ClientInterface $http;

    public function __construct(ClientInterface $http)
    {
        $this->http = $http;
    }

    public function downloadStocks(string $date): void
    {
        try {
            $resource = fopen(self::FILE_NAME, 'w');
            $this->http->request('GET', self::API_URL . $date, [
                'sink' => $resource,
            ]);

        } catch (GuzzleException $e) {
        }
    }
}

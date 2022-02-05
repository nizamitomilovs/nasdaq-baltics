<?php

declare(strict_types=1);

namespace App\Services\ApiService;

interface ApiClientInterface
{
    public function downloadStocks(string $date): void;
}

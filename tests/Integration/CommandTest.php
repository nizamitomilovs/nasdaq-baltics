<?php

declare(strict_types=1);

namespace Tests\Integration;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CommandTest extends TestCase
{
    use DatabaseMigrations;

    public function testWithoutDate(): void
    {
        $this->artisan('download:stocks')
            ->expectsOutput('No date were specified, using today\'s day: ' . date_create()->format('Y-m-d'))
            ->expectsOutput('Downloading stocks...')
            ->expectsOutput('Download complete, starting processing.')
            ->expectsOutput('Processing complete.');

        $this->assertDatabaseHas('stocks', [
            'ticker' => 'AMG1L'
        ]);
    }

    public function testWithDate(): void
    {
        $this->artisan('download:stocks 2022-02-03')
            ->expectsOutput('Downloading stocks...')
            ->expectsOutput('Download complete, starting processing.')
            ->expectsOutput('Processing complete.');
        $this->assertDatabaseHas('stocks', [
            'ticker' => 'AMG1L'
        ]);
    }

    public function testWhenInvalidDate(): void
    {
        $this->artisan('download:stocks 2022-02-03aaaa')
            ->expectsOutput('Please provide valid data format 2022-01-05.');
    }
}

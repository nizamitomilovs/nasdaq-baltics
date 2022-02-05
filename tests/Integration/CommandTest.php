<?php

declare(strict_types=1);

namespace Tests\Integration;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CommandTest extends TestCase
{
    use DatabaseMigrations;

    public function testFileIsRequired(): void
    {
        $this->artisan('download:stocks');
        $this->assertDatabaseHas('stocks', [
            'ticker' => 'AMG1L'
        ]);
    }

    public function testWithDate(): void
    {
        $this->artisan('download:stocks 2022-02-03');
        $this->assertDatabaseHas('stocks', [
            'ticker' => 'AMG1L'
        ]);
    }
}

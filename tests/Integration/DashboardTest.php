<?php

declare(strict_types=1);

namespace Tests\Integration;

use Database\Factories\StockFactory;
use Database\Factories\StockPriceFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use DatabaseMigrations;

    public function testShowDashboard(): void
    {
        $user = UserFactory::new()->create();
        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $this->assertAuthenticated();
    }

    public function testStockNotFound(): void
    {
        $user = UserFactory::new()->create();
        $response = $this->actingAs($user)->post('/stock', [
            'stock' => 'notvalied',
        ]);

        $response->assertStatus(302)
            ->assertRedirect('/');

        $response->assertSessionHas('error', 'Didn\'t find the stock: ');
        $this->assertTrue(session()->hasOldInput('stock'));
    }

    public function testMissingInput(): void
    {
        $user = UserFactory::new()->create();
        $response = $this->actingAs($user)->post('/stock', [
            'pew' => 'notvalied',
        ]);

        $response->assertStatus(302)
            ->assertRedirect('/');

        $response->assertSessionHas('error');
    }

    public function testReturnsStockPrices(): void
    {
        $user = UserFactory::new()->create();
        StockFactory::new()->create();
        StockPriceFactory::new()->count(4)->create();

        $response = $this->actingAs($user)->post('/stock', [
            'stock' => 'AMG1L',
        ]);

        $response->assertStatus(200);
        $content = json_decode($response->getOriginalContent()->getData()['stockPrices'], true);
        $this->assertEquals('AMG1L', $content[0]['stock_id']);
    }
}

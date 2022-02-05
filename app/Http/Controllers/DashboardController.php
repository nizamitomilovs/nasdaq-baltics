<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\StockPrice;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }

    public function showStock(Request $request)
    {
        $stock = $request->get('stock');

        $stock = Stock::where('ticker', strtoupper($stock))->first();

        $stockPrices = StockPrice::where('stock_id', $stock->ticker)->get();

        return view('dashboard', ['stockPrices' => $stockPrices->toJson()]);
    }
}

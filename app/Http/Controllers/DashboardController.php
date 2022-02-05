<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\StockPrice;
use App\Repositories\StockRepository\StockRepositoryInterface;
use Illuminate\Contracts\Validation\Validator as ContractValidation;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class DashboardController extends Controller
{
    private StockRepositoryInterface $stockRepository;

    public function __construct(StockRepositoryInterface $stockRepository)
    {
        $this->stockRepository = $stockRepository;
    }

    public function index()
    {
        return view('dashboard');
    }

    public function showStock(Request $request)
    {
        try {
            $validator = $this->validator($request::all());
            $payload = $validator->validate();
        } catch (ValidationException $e) {
            return redirect('/')
                ->with('error', $e->errors());
        }

        $stock = $this->stockRepository->findStock($payload['stock']);
        if (null === $stock) {
            return redirect('/')
                ->with('error', 'Didn\'t find the stock: ')
                ->withInput(['stock' => $payload['stock']]);
        }

        $stockPrices = $this->stockRepository->findStockPrices($stock);

        return view('dashboard', ['stockPrices' => $stockPrices->toJson()]);
    }

    /**
     * @param array<string, string> $data
     */
    protected function validator(array $data): ContractValidation
    {
        return Validator::make($data, [
            'stock' => ['required', 'string', 'max:255'],
        ]);
    }
}

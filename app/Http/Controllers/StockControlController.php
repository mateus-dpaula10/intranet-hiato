<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StockControl;

class StockControlController extends Controller
{
    public function index()
    {
        $stocks = StockControl::all();

        return view ('stock_control.index', compact('stocks'));
    }

    public function updateAll(Request $request)
    {
        $stocks = $request->input('stocks', []);
        $newStocks = $request->input('new', []);
        
        $idsMantidos = array_keys($stocks);

        StockControl::whereNotIn('id', $idsMantidos)->delete();

        foreach ($stocks as $id => $data) {
            $stock = StockControl::find($id);
            if ($stock) {
                $stock->update($data);
            }
        }

        foreach ($newStocks as $data) {
            StockControl::create($data);
        }

        return redirect()->route('estoque.index')->with('success', 'Estoque atualizado!');
    }
}

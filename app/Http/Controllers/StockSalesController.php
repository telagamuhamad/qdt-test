<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\StockSales;
use App\Models\StockType;
use Illuminate\Http\Request;

class StockSalesController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $stockSales = StockSales::with('stock.type');
    
        // filter by name
        if (!empty($request->name)) {
            $stockSales = $stockSales->whereHas('stock', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->name . '%');
            });
        }
    
        // filter by qty
        if (!empty($request->qty)) {
            $stockSales = $stockSales->where('stock_qty', $request->qty);
        }
    
        // filter by sold
        if (!empty($request->sold)) {
            $stockSales = $stockSales->where('stock_sold', $request->sold);
        }
    
        // filter by date
        if (!empty($request->date)) {
            $stockSales = $stockSales->whereDate('transaction_date', $request->date);
        }
    
        // filter by type
        if (!empty($request->type)) {
            $stockSales = $stockSales->whereHas('stock.type', function ($query) use ($request) {
                $query->where('type_name', $request->type);
            });
        }
    
        // sort
        if (!empty($request->sort_by)) {
            if ($request->sort_by == 'name') {
                $stockSales = $stockSales->orderBy(function($query) {
                    $query->select('name')
                          ->from('stocks')
                          ->whereColumn('stocks.id', 'stock_sales.stock_id')
                          ->limit(1);
                }, 'asc');
            } else if ($request->sort_by == 'date') {
                $stockSales = $stockSales->orderBy('transaction_date', 'asc');
            }
        }

        // get most and least sold
        $mostSold = null;
        $leastSold = null;
        if (!empty($request->date_from) && !empty($request->date_to)) {
            $mostSold = StockSales::select('stock_sales.*')
                ->join('stocks', 'stock_sales.stock_id', '=', 'stocks.id')
                ->join('stock_types', 'stocks.type_id', '=', 'stock_types.id')
                ->whereBetween('stock_sales.transaction_date', [$request->date_from, $request->date_to])
                ->orderByDesc('stock_sales.stock_sold')
                ->first();

            $leastSold = StockSales::select('stock_sales.*')
                ->join('stocks', 'stock_sales.stock_id', '=', 'stocks.id')
                ->join('stock_types', 'stocks.type_id', '=', 'stock_types.id')
                ->whereBetween('stock_sales.transaction_date', [$request->date_from, $request->date_to])
                ->orderBy('stock_sales.stock_sold')
                ->first();
        }
    
        $stockSales = $stockSales->orderBy('id', 'asc')->get();
    
        return view('stock-sales.index', [
            'stockSales' => $stockSales,
            'mostSold' => $mostSold,
            'leastSold' => $leastSold
        ]);
    }    

    /**
     * Show details of the resource.
     * 
     * @param id $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // get stock sales
        $stockSales = StockSales::with('stock')->find($id);
        if (empty($stockSales)) {
            return back()->with('error_message', 'No stock sales found');
        }

        $stockTypes = StockType::orderBy('type_name', 'asc')->get();

        return view('stock-sales.show', [
            'stockSales' => $stockSales,
            'stockTypes' => $stockTypes
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * 
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // get stock types
        $stockTypes = StockType::orderBy('type_name', 'asc')->get();
        return view('stock-sales.create', [
            'stockTypes' => $stockTypes
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'type' => 'required',
            'stock_qty' => 'required',
            'stock_sold' => 'required',
            'transaction_date' => 'required|date',
        ]);

        // check if stock type exists
        $stockTypeCheck = StockType::where('type_name', $request->type)->first();
        if (empty($stockTypeCheck)) {
            return back()->with('error_message', 'No stock type found');
        }

        // save stock
        $stock = new Stock();
        $stock->name = $request->name;
        $stock->type_id = $stockTypeCheck->id;
        $stock->save();

        // save stock sales
        $stockSales = new StockSales();
        $stockSales->stock_id = $stock->id;
        $stockSales->stock_qty = $request->stock_qty;
        $stockSales->stock_sold = $request->stock_sold;
        $stockSales->transaction_date = $request->transaction_date;
        $stockSales->save();

        return redirect()->route('index')->with('success_message', 'Stock sales created successfully');
    }

    /**
     * Store updated resource in storage.
     * 
     * @param \Illuminate\Http\Request $request
     * @param id $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'type' => 'required',
            'stock_qty' => 'required',
            'stock_sold' => 'required',
            'transaction_date' => 'required|date',
        ]);

        // check if stock type exists
        $stockSales = StockSales::find($id);
        if (empty($stockSales)) {
            return back()->with('error_message', 'No stock sales found');
        }

        // check if stock exists
        $stock = Stock::find($stockSales->stock_id);
        if (empty($stock)) {
            return back()->with('error_message', 'No stock found');
        }

        // check if stock type exists
        $stockTypeCheck = StockType::where('type_name', $request->type)->first();
        if (empty($stockTypeCheck)) {
            return back()->with('error_message', 'No stock type found');
        }

        // update stock
        $stock->name = $request->name;
        $stock->type_id = $stockTypeCheck->id;
        $stock->save();

        // update stock sales
        $stockSales->stock_id = $stock->id;
        $stockSales->stock_qty = $request->stock_qty;
        $stockSales->stock_sold = $request->stock_sold;
        $stockSales->transaction_date = $request->transaction_date;
        $stockSales->save();

        return redirect()->route('index')->with('success_message', 'Stock sales updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param id $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // check if stock sales exists
        $stockSales = StockSales::find($id);
        if (empty($stockSales)) {
            return back()->with('error_message', 'No stock sales found');
        }

        // delete stock sales
        $stockSales->delete();
        return redirect()->route('index')->with('success_message', 'Stock sales deleted successfully');
    }
}

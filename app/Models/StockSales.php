<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockSales extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'stock_sales';

    protected $fillable = [
        'stock_id',
        'stock_qty',
        'stock_sold',
        'transaction_date'
    ];

    protected $appends = [
        'formatted_transaction_date'
    ];

    /**
     * Return relation to stock
     */
    public function stock()
    {
        return $this->belongsTo(Stock::class, 'stock_id');
    }

    /**
     * Return formatted transaction date
     */
    public function getFormattedTransactionDateAttribute()
    {
        return Carbon::parse($this->transaction_date)->format('d M, Y');
    }
}

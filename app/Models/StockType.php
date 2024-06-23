<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockType extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'stock_types';

    protected $fillable = [
        'type_name',
    ];
}

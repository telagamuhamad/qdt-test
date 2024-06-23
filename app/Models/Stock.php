<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stock extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'stocks';

    protected $fillable = [
        'name',
        'type_id'
    ];

    /**
     * Return relation to stock type
     */
    public function type()
    {
        return $this->belongsTo(StockType::class, 'type_id');
    }
}

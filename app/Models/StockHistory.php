<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockHistory extends Model
{
    protected $fillable = [
        'stock_id', 'day_of_week', 'month', 
        'usage_count', 'record_date'
    ];
}

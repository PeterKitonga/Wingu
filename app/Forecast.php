<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Forecast extends Model
{
    protected $fillable = ['forecast_day', 'forecast_max_temperature', 'forecast_min_temperature', 'forecast_average_temperature', 'forecast_spread'];
}

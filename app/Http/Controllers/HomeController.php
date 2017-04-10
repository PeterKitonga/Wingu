<?php

namespace App\Http\Controllers;

use App\Forecast;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $forecasts = Forecast::query();

        $averageMaxTemp = $forecasts->avg('forecast_max_temperature');
        $averageMinTemp = $forecasts->avg('forecast_min_temperature');

        return view('home', compact('averageMaxTemp', 'averageMinTemp'));
    }
}

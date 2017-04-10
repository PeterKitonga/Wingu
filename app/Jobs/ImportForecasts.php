<?php

namespace App\Jobs;

use App\Forecast;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ImportForecasts implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $datum;

    /**
     * Create a new job instance.
     *
     * @param $datum
     */
    public function __construct($datum)
    {
        $this->datum = $datum;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Forecast::create(
            [
                'forecast_day' => intval($this->datum['Dy']),
                'forecast_max_temperature' => intval($this->datum['MxT']),
                'forecast_min_temperature' => intval($this->datum['MnT']),
                'forecast_average_temperature' => intval($this->datum['AvT']),
                'forecast_spread' => intval($this->datum['MxT']) - intval($this->datum['MnT']),
            ]
        );
    }
}

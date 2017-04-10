<?php

namespace App\Http\Controllers;

use App\Forecast;
use App\Jobs\ImportForecasts;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\Datatables\Facades\Datatables;

class ForecastsController extends Controller
{
    /**
     * Added middleware to prevent access from unauthorised users
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Displays view for forecasts listing
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function listForecasts()
    {
        $forecasts = Forecast::query();

        $max = $forecasts->max('forecast_spread');

        $maxSpread = $forecasts->where('forecast_spread', '=', $max)->first();

        return view('forecasts.index', compact('maxSpread'));
    }

    /**
     * Gets forecasts data and passes it to the datatable facade
     * Will return a json for the datatable service
     *
     * @return Datatables
     */
    public function getForecastsData()
    {
        $forecasts = Forecast::query();

        return Datatables::of($forecasts)
            ->make(true);
    }

    public function fetchForecastsData(Request $request)
    {
        if ($request->ajax())
        {
            $forecasts = Forecast::query();

            $days = $forecasts->pluck('forecast_day');

            $max = $forecasts->pluck('forecast_max_temperature');

            $min = $forecasts->pluck('forecast_min_temperature');

            $avg = $forecasts->pluck('forecast_average_temperature');

            $response = [
                'days' => $days,
                'max' => $max,
                'min' => $min,
                'avg' => $avg
            ];

            return response()->json($response);
        }
    }

    /**
     * Import forecasts file input
     *
     * @param Request $request
     * @return mixed
     */
    public function importDatFile(Request $request)
    {
        // Get the file input
        $upload = $request->file('dat');

        // Get the extension of the file
        $extension = $upload->getClientOriginalExtension();

        $validator = Validator::make($request->all(), [
            'dat' => 'required',
        ]);

        // Validate if a file is uploaded
        if ($validator->fails()) {
            return Redirect::back()->with('error', 'Please upload a dat file!');
        }

        // Check if it is a valid dat file
        if ($extension != 'dat') {
            return Redirect::back()->with('error', 'Please upload a valid dat file!');
        }

        // Rename the file
        $fileName = str_random(10).'.'.$extension;

        // Move the file to the public
        $upload->move('storage', $fileName);

        // Get the path of the file
        $path = public_path('storage/'.$fileName);

        // Read the uploaded file
        $file = fopen($path, "r");

        // Initialize an empty array for the rows
        $dataArray = [];

        // Strip file and convert row data into arrays
        while ($read = fgets($file)) {
            $dataArray[] = trim($read);
        }

        // Strip data
        $data = self::stripData($dataArray);

        // Loop through data and dispatch to job
        foreach ($data as $datum)
        {
            $this->dispatch(new ImportForecasts($datum));
        }

        // Close the file
        fclose($file);

        // Delete the file
        File::delete($path);

        return Redirect::back()->with('success', 'Successfully imported forecasts data');
    }

    /**
     * Exports data to an excel sheet
     *
     * @return mixed
     */
    public function exportData()
    {
        // Get the data
        $forecasts = Forecast::query()->select(['forecast_day AS Day', 'forecast_max_temperature AS MaximumTemp', 'forecast_min_temperature AS MinimumTemp', 'forecast_average_temperature AS AverageTemp', 'forecast_spread AS TempSpread'])->get();

        // Create the excel and export the data
        Excel::create('Forecast'.'-'.Carbon::now()->format('j-F-Y h:i A'), function($excel) use($forecasts) {
            // Text Align Workbook to right
            $excel->getDefaultStyle()->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            $excel->sheet('Forecast', function($sheet) use($forecasts) {
                // Set font
                $sheet->setStyle(array(
                    'font' => array(
                        'name' => 'Calibri',
                        'size' => 12
                    )
                ));

                // Freeze first row
                $sheet->freezeFirstRow();

                // Manipulate cells
                $sheet->cells('A1:E1', function($cells) {
                    // Set font weight to bold
                    $cells->setFontWeight('bold');
                });

                // Set orientation
                $sheet->setOrientation('landscape');
                $sheet->fromModel(collect($forecasts));
            });
        })->export('xls');

        return Redirect::back();
    }

    /**
     * Strips and cleans up the data
     *
     * @param $dataArray
     * @return static
     */
    public function stripData($dataArray)
    {
        // Convert array into collection
        $dataCollection = collect($dataArray);

        // Get the key of the last row which contains the average
        $key = $dataCollection->count() - 1;

        // Filter and Reject empty rows
        $filtered = $dataCollection->reject(function ($value) {
            return empty($value);
        })->except($key)->values();

        // Pull data headers and reject empty values
        $dataHeaders = collect(explode(' ', $filtered->pull(0)))->reject(function ($value) {
            return empty($value);
        })->values()->only([0, 1, 2, 3])->toArray();

        // Initialize empty array for the data
        $data = [];

        // Loop through each row and combine with respective header
        foreach ($filtered->toArray() as $row) {
            // Explode row data and reject any empty array values
            $rowData = collect(explode(' ', $row))->reject(function ($value) {
                return empty($value);
            })->values()->only([0, 1, 2, 3])->toArray();

            // Combine headers with row data
            $combined = array_combine($dataHeaders, $rowData);

            // Push combined row data to empty array
            array_push($data, $combined);
        }

        // Return data
        return $data;
    }
}

@extends('layouts.auth')

@push('styles')
    <link rel="stylesheet" href="{{ asset('plugins/chartist/css/chartist.min.css') }}">
@endpush

@section('content')
    <div class="row">
        <div class="col-sm-12">
            @include('partials.alerts')
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading">Average Maximum Temperature
                    <i class="fa fa-ellipsis-v pull-right" aria-hidden="true"></i>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="text-center col-sm-12">
                            <i class="fa fa-thermometer-full fa-5x" aria-hidden="true"></i>
                            @if($averageMaxTemp !== null)
                            <p class="lead">{!! round($averageMaxTemp, 1) !!}</p>
                            @else
                            <p class="lead">No data! Please upload dat file <a id="trigger-import" data-url="{!! route('forecasts.import') !!}">here</a></p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading">Average Minimum Temperature
                    <i class="fa fa-ellipsis-v pull-right" aria-hidden="true"></i>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="text-center col-sm-12">
                            <i class="fa fa-thermometer-quarter fa-5x" aria-hidden="true"></i>
                            @if($averageMinTemp !== null)
                            <p class="lead">{!! round($averageMinTemp, 1) !!}</p>
                            @else
                            <p class="lead">No data! Please upload dat file</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">Forecast This Month
                    <i class="fa fa-ellipsis-v pull-right" aria-hidden="true"></i>
                </div>
                <div class="panel-body">
                    <div class="ct-chart ct-golden-section" id="forecasts-chart"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        @include('partials.modals')
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('plugins/chartist/js/chartist.min.js') }}"></script>
    <script src="{!! asset('js/custom.js') !!}"></script>
    <script type="text/javascript">
        $(function () {
            custom.initCharts();
        });
    </script>
@endpush

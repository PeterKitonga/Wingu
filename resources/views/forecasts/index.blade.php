@extends('layouts.auth')

@push('styles')
    <link rel="stylesheet" href="{{ asset('plugins/jquery.dataTables/css/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/jquery.dataTables/css/responsive.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/jquery.dataTables/css/buttons.bootstrap.min.css') }}">
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
                <div class="panel-heading">Day with maximum spread
                    <i class="fa fa-ellipsis-v pull-right" aria-hidden="true"></i>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="text-center col-sm-12">
                            <i class="fa fa-calendar fa-5x" aria-hidden="true"></i>
                            @if(count($maxSpread) !== 0)
                            <p class="lead">{!! $maxSpread->forecast_day !!}</p>
                            @else
                            <p class="lead">No data! Please upload dat file</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading">Maximum Spread
                    <i class="fa fa-ellipsis-v pull-right" aria-hidden="true"></i>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="text-center col-sm-12">
                            <i class="fa fa-thermometer-three-quarters fa-5x" aria-hidden="true"></i>
                            @if(count($maxSpread) !== 0)
                            <p class="lead">{!! $maxSpread->forecast_spread !!}</p>
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
                <div class="panel-heading">Forecasts Listing
                    <i class="fa fa-ellipsis-v pull-right" aria-hidden="true"></i>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered forecasts-datatable dt-responsive nowrap" id="forecasts-datatable" cellspacing="0" width="100%">
                            <caption>Forecasts this month</caption>
                            <thead>
                                <tr>
                                    <td></td>
                                    <td>Day</td>
                                    <td>Max Temp</td>
                                    <td>Min Temp</td>
                                    <td>Average Temp</td>
                                    <td>Spread</td>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        @include('partials.modals')
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('plugins/jquery.dataTables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery.dataTables/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery.dataTables/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery.dataTables/js/responsive.bootstrap.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery.dataTables/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery.dataTables/js/buttons.bootstrap.min.js') }}"></script>
    <script src="{!! asset('js/custom.js') !!}"></script>
    <script type="text/javascript">
        $(function () {
            custom.initDatatables();
        });
    </script>
@endpush
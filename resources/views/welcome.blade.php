@extends('layouts.guest')

@section('content')
    <section class="slide-wrapper">
        <div class="container">
            <div id="myCarousel" class="carousel slide">
                <!-- Indicators -->
                <ol class="carousel-indicators">
                    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                    <li data-target="#myCarousel" data-slide-to="1"></li>
                    <li data-target="#myCarousel" data-slide-to="2"></li>
                </ol>

                <!-- Wrapper for slides -->
                <div class="carousel-inner">
                    <div class="item item1 active">
                        <div class="fill" style="background-color:#74afad;">
                            <div class="inner-content">
                                <div class="carousel-img">
                                    <i class="fa fa-cloud fa-5x" aria-hidden="true"></i>
                                </div>
                                <div class="carousel-desc">
                                    <h2>Welcome to Wingu</h2>
                                    <p>Your one stop for all things 'Weather'</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="item item2">
                        <div class="fill" style="background-color:#d9853b;">
                            <div class="inner-content">
                                <div class="carousel-img">
                                    <i class="fa fa-circle-o-notch fa-spin fa-5x fa-fw"></i>
                                </div>
                                <div class="carousel-desc">
                                    <h2>Who are we?</h2>
                                    <p>We are a platform that provides weather updates to our esteemed clients</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="item item3">
                        <div class="fill" style="background-color:#74afad;">
                            <div class="inner-content">
                                <div class="carousel-img">
                                    <i class="fa fa-cog fa-spin fa-5x fa-fw"></i>
                                </div>
                                <div class="carousel-desc">
                                    <h2>Where do we get our data?</h2>
                                    <p>Our data is sourced from the various local weather stations spread out across the country</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{!! asset('js/custom.js') !!}"></script>
    <script>
        custom.initCarousel();
    </script>
@endpush
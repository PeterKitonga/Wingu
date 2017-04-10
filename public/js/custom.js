$(".push_menu").click(function(){
    $(".wrapper").toggleClass("active");
});

$(':file').on('change', function() {
    var input = $(this), numFiles = input.get(0).files ? input.get(0).files.length : 1, label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
    input.trigger('fileselect', [numFiles, label]);
    $('.import-read').val(label);
});

$('#trigger-import').on('click', function () {
    var url = $(this).attr('data-url');

    $("#import-dat-form").attr('action', url);
    $("#modal-import-dat").modal('show');
});

custom = {
    initDatatables: function() {
        var table = $('#forecasts-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/datatables/get/forecasts',
                data: function (d) {
                    d.forecast_day = $('input[name="forecast_day"]').val();
                    d.forecast_max_temperature = $('input[name="forecast_max_temperature"]').val();
                    d.forecast_min_temperature = $('input[name="forecast_min_temperature"]').val();
                    d.forecast_average_temperature = $('input[name="forecast_average_temperature"]').val();
                    d.forecast_spread = $('input[name="forecast_spread"]').val();
                }
            },
            order: [[ 1, 'asc' ]],
            dom: '<"row"<"col-md-6"B><"custom-filter col-md-6"f>>rtip',
            deferRender: true,
            orderClasses: false,
            buttons: [
                {
                    extend: 'pageLength',
                    className: 'btn-default'
                },
                {
                    text: 'Import .dat File',
                    className: 'btn-default',
                    action: function (e, dt, node, config) {
                        var url = '/forecasts/import';
                        $("#import-dat-form").attr('action', url);
                        $("#modal-import-dat").modal('show');
                    }
                },
                {
                    text: 'Export Excel',
                    className: 'btn-default',
                    action: function (e, dt, node, config) {
                        window.location = '/forecasts/export';
                    }
                }
            ],
            responsive: {
                details: {
                    type: 'column'
                }
            },
            columnDefs: [
                {
                    className: 'control',
                    orderable: false,
                    targets:   0
                }
            ],
            columns: [
                {data: null, searchable: false},
                {data: 'forecast_day', name: 'forecast_day', sClass:"numericCol"},
                {data: 'forecast_max_temperature', name: 'forecast_max_temperature', sClass:"numericCol"},
                {data: 'forecast_min_temperature', name: 'forecast_min_temperature', sClass:"numericCol"},
                {data: 'forecast_average_temperature', name: 'forecast_average_temperature', sClass:"numericCol"},
                {data: 'forecast_spread', name: 'forecast_spread', sClass:"numericCol"}
            ]
        });

        $('div.custom-filter').html('' +
            '<div class="row">' +
                '<div class="col-sm-4">' +
                    '<div class="dropdown category-dropdown pull-right"> ' +
                        '<a href="#" class="btn btn-default dropdown-toggle category-text" data-toggle="dropdown">Search by: Day ' +
                            '<b class="caret"></b> ' +
                        '</a> ' +
                        '<ul class="dropdown-menu dropdown-menu-left"> ' +
                            '<li><a href="#" class="category-selection" data-name="forecast_day" data-node="1">Search by: Day</a></li> ' +
                            '<li class="divider"></li> ' +
                            '<li><a href="#" class="category-selection" data-name="forecast_max_temperature" data-node="2">Search by: Max Temp</a></li> ' +
                            '<li class="divider"></li> ' +
                            '<li><a href="#" class="category-selection" data-name="forecast_min_temperature" data-node="3">Search by: Min temp</a></li> ' +
                            '<li class="divider"></li> ' +
                            '<li><a href="#" class="category-selection" data-name="forecast_average_temperature" data-node="4">Search by: Average Temp</a></li> ' +
                            '<li class="divider"></li> ' +
                            '<li><a href="#" class="category-selection" data-name="forecast_spread" data-node="5">Search by: Spread</a></li> ' +
                        '</ul> ' +
                    '</div>' +
                '</div>' +
                '<div class="col-sm-6">' +
                    '<form class="form-inline pull-right" id="search-form">' +
                        '<div class="form-group custom-search-input-form-group">' +
                            '<input type="text" class="form-control custom-search-input" name="phone" placeholder="Search by: Day" data-node="1">' +
                        '</div>' +
                    '</form>' +
                '</div>' +
                '<div class="col-sm-2">' +
                    '<button type="button" class="btn btn-default pull-left reset-table-button" data-toggle="tooltip" data-placement="left" title="Reset table">' +
                        '<i class="fa fa-undo"></i>' +
                    '</button>' +
                '</div>' +
            '</div>'
        );

        $('.category-selection').on('click', function (e) {
            e.preventDefault();
            var text = $(this).text();
            var name = $(this).attr('data-name');
            var node = $(this).attr('data-node');
            $('.category-dropdown').find('.category-text').html(text+' <b class="caret"></b> ');
            $('.form-inline').find('.custom-search-input').attr('name', name)
                .end()
                .find('.custom-search-input').attr('placeholder', text)
                .end()
                .find('.custom-search-input').attr('data-node', node);
        });

        $('input.custom-search-input').on('keyup click', function () {
            var i = $(this).attr('data-node');
            var value = $(this).val();
            table.column(i).search(value).draw();
        });

        $('.reset-table-button').on('click', function() {
            table.state.clear();
            window.location.reload();
        });
    },

    initCharts: function () {
        $.get('/forecasts/fetch', function (data) {
            var fdays = data.days;
            var fmax = data.max;
            var fmin = data.min;
            var favg = data.avg;
            
            var chart = new Chartist.Line('#forecasts-chart', {
                labels: fdays,
                series: [
                    fmax,
                    fmin,
                    favg
                ]
            }, {
                low: 0
            });

            // Let's put a sequence number aside so we can use it in the event callbacks
            var seq = 0,
                delays = 80,
                durations = 500;

            // Once the chart is fully created we reset the sequence
            chart.on('created', function() {
                seq = 0;
            });

            // On each drawn element by Chartist we use the Chartist.Svg API to trigger SMIL animations
            chart.on('draw', function(data) {
                seq++;

                if(data.type === 'line') {
                    // If the drawn element is a line we do a simple opacity fade in. This could also be achieved using CSS3 animations.
                    data.element.animate({
                        opacity: {
                            // The delay when we like to start the animation
                            begin: seq * delays + 1000,
                            // Duration of the animation
                            dur: durations,
                            // The value where the animation should start
                            from: 0,
                            // The value where it should end
                            to: 1
                        }
                    });
                } else if(data.type === 'label' && data.axis === 'x') {
                    data.element.animate({
                        y: {
                            begin: seq * delays,
                            dur: durations,
                            from: data.y + 100,
                            to: data.y,
                            // We can specify an easing function from Chartist.Svg.Easing
                            easing: 'easeOutQuart'
                        }
                    });
                } else if(data.type === 'label' && data.axis === 'y') {
                    data.element.animate({
                        x: {
                            begin: seq * delays,
                            dur: durations,
                            from: data.x - 100,
                            to: data.x,
                            easing: 'easeOutQuart'
                        }
                    });
                } else if(data.type === 'point') {
                    data.element.animate({
                        x1: {
                            begin: seq * delays,
                            dur: durations,
                            from: data.x - 10,
                            to: data.x,
                            easing: 'easeOutQuart'
                        },
                        x2: {
                            begin: seq * delays,
                            dur: durations,
                            from: data.x - 10,
                            to: data.x,
                            easing: 'easeOutQuart'
                        },
                        opacity: {
                            begin: seq * delays,
                            dur: durations,
                            from: 0,
                            to: 1,
                            easing: 'easeOutQuart'
                        }
                    });
                } else if(data.type === 'grid') {
                    // Using data.axis we get x or y which we can use to construct our animation definition objects
                    var pos1Animation = {
                        begin: seq * delays,
                        dur: durations,
                        from: data[data.axis.units.pos + '1'] - 30,
                        to: data[data.axis.units.pos + '1'],
                        easing: 'easeOutQuart'
                    };

                    var pos2Animation = {
                        begin: seq * delays,
                        dur: durations,
                        from: data[data.axis.units.pos + '2'] - 100,
                        to: data[data.axis.units.pos + '2'],
                        easing: 'easeOutQuart'
                    };

                    var animations = {};
                    animations[data.axis.units.pos + '1'] = pos1Animation;
                    animations[data.axis.units.pos + '2'] = pos2Animation;
                    animations['opacity'] = {
                        begin: seq * delays,
                        dur: durations,
                        from: 0,
                        to: 1,
                        easing: 'easeOutQuart'
                    };

                    data.element.animate(animations);
                }
            });
        });
    },

    initCarousel: function () {
        $('#myCarousel').carousel({
            interval: 10000
        });

        // scroll slides on mouse scroll
        $('#myCarousel').bind('mousewheel DOMMouseScroll', function(e){
            if(e.originalEvent.wheelDelta > 0 || e.originalEvent.detail < 0) {
                $(this).carousel('prev');
            }
            else{
                $(this).carousel('next');
            }
        });

        //scroll slides on swipe for touch enabled devices
        $("#myCarousel").on("touchstart", function(event){

            var yClick = event.originalEvent.touches[0].pageY;
            $(this).one("touchmove", function(event){
                var yMove = event.originalEvent.touches[0].pageY;
                if( Math.floor(yClick - yMove) > 1 ){
                    $(".carousel").carousel('next');
                }
                else if( Math.floor(yClick - yMove) < -1 ){
                    $(".carousel").carousel('prev');
                }
            });
            $(".carousel").on("touchend", function(){
                $(this).off("touchmove");
            });
        });

        //to add  start animation on load for first slide
        $(function(){
            $.fn.extend({
                animateCss: function (animationName) {
                    var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
                    this.addClass('animated ' + animationName).one(animationEnd, function() {
                        $(this).removeClass(animationName);
                    });
                }
            });
            $('.item1.active i').animateCss('slideInDown');
            $('.item1.active h2').animateCss('zoomIn');
            $('.item1.active p').animateCss('fadeIn');

        });

        //to start animation on  mousescroll , click and swipe
        $("#myCarousel").on('slide.bs.carousel', function () {
            $.fn.extend({
                animateCss: function (animationName) {
                    var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
                    this.addClass('animated ' + animationName).one(animationEnd, function() {
                        $(this).removeClass(animationName);
                    });
                }
            });

            // add animation type from animate.css on the element which you want to animate
            $('.item1 i').animateCss('slideInDown');
            $('.item1 h2').animateCss('zoomIn');
            $('.item1 p').animateCss('fadeIn');

            $('.item2 i').animateCss('pulse');
            $('.item2 h2').animateCss('flash');
            $('.item2 p').animateCss('fadeIn');

            $('.item3 i').animateCss('fadeInLeft');
            $('.item3 h2').animateCss('fadeInDown');
            $('.item3 p').animateCss('fadeIn');
        });
    }
};

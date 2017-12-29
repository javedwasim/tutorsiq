<article class="a-padd ">

    <div class="container">

        <div class="row">

            <div class="col-md-6">
                <!-- DONUT CHART -->
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">Tuitions By Gender</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                        class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body chart-responsive">
                        <canvas id="chart-gender" style="height: 100px; position: relative;" height="150" ></canvas>

                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>

            <div class="col-md-6">
                <!-- DONUT CHART -->
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">Tuitions By Categories</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                        class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body chart-responsive">
                        <canvas id="chart-categories" class="chartjs" style="height: 100px; position: relative;" height="150"></canvas>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>

        </div>

        <div class="row">
            <div class="col-md-12">

                <!-- DONUT CHART -->
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">Tuitions By Locations</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                        class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body chart-responsive">
                        <canvas id="chart-locations" style="height: 100px; position: relative;" height="150" ></canvas>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->

            </div>
        </div>

    </div>
</article>

@section('page_specific_inline_scripts')

    <!-- Create locations chart -->
    <script>

        var colors = getRandomColor();
        var colorCounter = 0;

        var config = {
            type: 'bar',
            data: {
                datasets: [
                    {data: [{{$location_values}}],
                        backgroundColor: [
                            @foreach($locations as $location)
                                colors[colorCounter++].borderColor,
                            @endforeach
                        ],
                        label: 'Dataset 1',

                    }],
                    labels: [
                        <?php echo $location_name; ?>
                    ],
            },
            options: {

                responsive: true,
                //Boolean - Whether we should show a stroke on each segment
                segmentShowStroke: true,
                //String - The colour of each segment stroke
                segmentStrokeColor: "#fff",
                //Number - The width of each segment stroke
                segmentStrokeWidth: 2,
                //Number - The percentage of the chart that we cut out of the middle
                percentageInnerCutout: 50, // This is 0 for Pie charts
                //Number - Amount of animation steps
                animationSteps: 100,
                //String - Animation easing effect
                animationEasing: "easeOutBounce",
                //Boolean - Whether we animate the rotation of the Doughnut
                animateRotate: true,
                //Boolean - Whether we animate scaling the Doughnut from the centre
                animateScale: true,
                //Boolean - whether to make the chart responsive to window resizing
                responsive: true,
                // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
                maintainAspectRatio: true,
                legend: {
                    "display": false
                },

                scales: {
                    yAxes: [{
                        display: true,
                        ticks: {
                            suggestedMin: 0,    // minimum will be 0, unless there is a lower value.
                            // OR //
                            beginAtZero: true,   // minimum value will be 0.
                            suggestedMax: '{{$TotalTuitions}}',
                        }
                    }]
                }


            }
        };

        var ctx = document.getElementById("chart-locations").getContext("2d");
        ctx.canvas.height = 100;
        window.myPie = new Chart(ctx, config);


        function getRandomColor() {
            var colors = [];
            var arrayColors = [
                "255, 99, 132",
                "54, 162, 235",
                "255, 205, 86",
                "146, 101, 194",
                "220, 220, 170",
                "206, 120, 255",
                "71, 160, 220",
                "218, 255, 0",
                "91, 200, 84",
                "255, 194, 193",
                "255, 66, 68",
                "217, 129, 80"
            ];

            for (var i = 0, countColors = arrayColors.length; i < countColors; i++) {
                var rgb = arrayColors[i];
                colors.push({
                    'fill': "false",
                    'backgroundColor': "rgba(0,0,0,0)",
                    'borderColor': "rgba(" + rgb + ",1)",
                    'pointBackgroundColor': "rgba(" + rgb + ",1)",
                    'pointHoverBackgroundColor': "rgba(" + rgb + ",0.8)",
                    'pointBorderColor': "#fff",
                    'pointHoverBorderColor': "rgba(" + rgb + ",1)",
                });
            }
            //console.log(colors[0].borderColor);
            return colors;
        }

    </script>
    <!-- Create locations chart -->

    <!-- Create category chart -->
    <script>

        var colors = getRandomColor();
        var colorCounter = 0;

        var config = {
            type: 'bar',
            data: {
                datasets: [{data: [{{$category_values}}],
                    backgroundColor: [
                        @foreach($category as $cat)
                            colors[colorCounter++].borderColor,
                        @endforeach
                    ],
                    label: 'Dataset 1'
                }],
                labels: [
                    <?php echo $category_name; ?>
                ],
            },
            options: {

                responsive: true,
                //Boolean - Whether we should show a stroke on each segment
                segmentShowStroke: true,
                //String - The colour of each segment stroke
                segmentStrokeColor: "#fff",
                //Number - The width of each segment stroke
                segmentStrokeWidth: 2,
                //Number - The percentage of the chart that we cut out of the middle
                percentageInnerCutout: 50, // This is 0 for Pie charts
                //Number - Amount of animation steps
                animationSteps: 100,
                //String - Animation easing effect
                animationEasing: "easeOutBounce",
                //Boolean - Whether we animate the rotation of the Doughnut
                animateRotate: true,
                //Boolean - Whether we animate scaling the Doughnut from the centre
                animateScale: true,
                //Boolean - whether to make the chart responsive to window resizing
                responsive: true,
                // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
                maintainAspectRatio: true,
                legend: {
                    "display": false
                },

                scales: {
                    yAxes: [{
                        display: true,
                        ticks: {
                            suggestedMin: 0,    // minimum will be 0, unless there is a lower value.
                            // OR //
                            beginAtZero: true,   // minimum value will be 0.
                            suggestedMax: '{{$TotalTuitions}}',
                        }
                    }]
                }


            }
        };

        var ctx = document.getElementById("chart-categories").getContext("2d");
        new Chart(ctx, config);

    </script>
    <!-- Create category chart -->

    <!-- Create gender chart -->
    <script>
        var config = {
            type: 'pie',
            data: {
                datasets: [{
                    data: [

                        @if(isset($gender[0]))

                        {{$gender[0]->gender_count}},

                        @endif

                        @if(isset($gender[1]))

                        {{$gender[1]->gender_count}}

                        @endif

                    ],
                    backgroundColor: [
                        window.chartColors.blue,
                        window.chartColors.red
                    ],
                    label: 'Dataset 1'
                }],
                labels: [
                    @if(isset($gender[0]))
                        "{{$gender[0]->name}}",
                    @endif
                    @if(isset($gender[1]))
                        "{{$gender[1]->name}}"
                    @endif
                ]
            },
            options: {
                responsive: true,
                //Boolean - Whether we should show a stroke on each segment
                segmentShowStroke: true,
                //String - The colour of each segment stroke
                segmentStrokeColor: "#fff",
                //Number - The width of each segment stroke
                segmentStrokeWidth: 2,
                //Number - The percentage of the chart that we cut out of the middle
                percentageInnerCutout: 50, // This is 0 for Pie charts
                //Number - Amount of animation steps
                animationSteps: 100,
                //String - Animation easing effect
                animationEasing: "easeOutBounce",
                //Boolean - Whether we animate the rotation of the Doughnut
                animateRotate: true,
                //Boolean - Whether we animate scaling the Doughnut from the centre
                animateScale: true,
                //Boolean - whether to make the chart responsive to window resizing
                responsive: true,
                // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
                maintainAspectRatio: true,
                legend: {
                    "display": true
                },
            }
        };

        var ctx = document.getElementById("chart-gender").getContext("2d");
        window.myPie = new Chart(ctx, config);

        Chart.plugins.register({
            afterDatasetsDraw: function(chart, easing) {
                // To only draw at the end of animation, check for easing === 1
                var ctx = chart.ctx;
                chart.data.datasets.forEach(function (dataset, i) {
                    var meta = chart.getDatasetMeta(i);
                    if (!meta.hidden) {
                        meta.data.forEach(function (element, index) {
                            // Draw the text in black, with the specified font
                            ctx.fillStyle = 'rgb(0, 0, 0)';
                            var fontSize = 16;
                            var fontStyle = 'normal';
                            var fontFamily = 'Helvetica Neue';
                            ctx.font = Chart.helpers.fontString(fontSize, fontStyle, fontFamily);
                            // Just naively convert to string for now
                            var tmpR = dataset.data[index].toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
                            var dataString = "" + tmpR;
                            if (chart.config.type == 'doughnut') {
                                dataString = tmpR + "%";
                            } else {
                                if (tmpR.indexOf('.') != -1) {
                                    tmpR = tmpR.substring(0, tmpR.indexOf('.'));
                                }
                            }
                            // Make sure alignment settings are correct
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';
                            var padding = 0;
                            var position = element.tooltipPosition();
                            ctx.fillText(dataString, position.x, position.y - (fontSize / 2) - padding);
                        });
                    }
                });
            }
        });
    </script>
    <!-- Create gender chart -->

@endsection

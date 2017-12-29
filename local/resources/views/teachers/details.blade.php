<div class="box box-primary">
    <a href="#">
        <div class="box-header with-border" data-widget="">
            <!--<i class="fa fa-minus pull-right" style="font-size:12px; margin-top: 5px;"></i>-->

            <h1 class="box-title">Tuition</h1>
        </div>
    </a>

    <div class="box-body" style="padding: 0px;">
        <div class="row">

            <div class="col-md-6">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Categories</h3>

                        {{--<div class="box-tools pull-right">--}}
                            {{--<button type="button" class="btn btn-box-tool" data-widget="collapse"><i--}}
                                        {{--class="fa fa-minus"></i>--}}
                            {{--</button>--}}
                        {{--</div>--}}

                        <!-- /.box-tools -->
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <ul class="nav nav-stacked">
                            <?php foreach($categories as $c): ?>
                            <li><a href="matched/<?php echo $c->id; ?>" class="send-btn"
                                   id="<?php echo $c->id; ?>"><?php echo $c->name; ?><span
                                            class="pull-left badge bg-green"></span></a></li>
                            <?php endforeach; ?>

                        </ul>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <div class="col-md-6">

                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Search Tuition By Subjects</h3>

                        {{--<div class="box-tools pull-right">--}}
                            {{--<button type="button" class="btn btn-box-tool" data-widget="collapse"><i--}}
                                        {{--class="fa fa-minus"></i>--}}
                            {{--</button>--}}
                        {{--</div>--}}
                        <!-- /.box-tools -->
                    </div>
                    <div class="box-body">
                        <form method="post" action="{{ url('tuition/subject') }}">
                            <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
                            <div class="form-group">

                                <div class="input-group margin">
                                    <input type="text" id="subject" name="subject" class="form-control"
                                           placeholder="Enter Subject Name..." required>
                                    <span class="input-group-btn">
                                      <button type="submit" class="btn btn-warning btn-flat subject-btn"><i
                                                  class="fa fa-search"></i>
                                      </button>
                                    </span>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Search Tuition By Grade</h3>

                        {{--<div class="box-tools pull-right">--}}
                            {{--<button type="button" class="btn btn-box-tool" data-widget="collapse"><i--}}
                                        {{--class="fa fa-minus"></i>--}}
                            {{--</button>--}}
                        {{--</div>--}}
                        <!-- /.box-tools -->
                    </div>
                    <div class="box-body">

                        <form method="post" action="{{ url('tuition/class') }}">
                            <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <div class="input-group margin">
                                    <input type="text" class="form-control" id="class_name" name="class_name"
                                           placeholder="Grade Search..." required>
                                <span class="input-group-btn">
                                  <button type="submit" class="btn btn-warning btn-flat class-btn"><i
                                              class="fa fa-search"></i>
                                  </button>
                                </span>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>

                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Search Tuition By Locations</h3>

                        {{--<div class="box-tools pull-right">--}}
                            {{--<button type="button" class="btn btn-box-tool" data-widget="collapse"><i--}}
                                        {{--class="fa fa-minus"></i>--}}
                            {{--</button>--}}
                        {{--</div>--}}
                        <!-- /.box-tools -->
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form method="post" action="{{ url('tuition/location') }}">
                            <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <div class="input-group margin">
                                    <input type="text" id="location" name="location" class="form-control"
                                           placeholder="Location Search..." required>
                                    <span class="input-group-btn">
                                      <button type="submit" class="btn btn-warning btn-flat location-btn"><i
                                                  class="fa fa-search"></i>
                                      </button>
                                    </span>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->

                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Search Tuition By Gender</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form method="post" action="{{ url('tuition/gender') }}">
                            <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <div class="input-group margin">
                                    <input type="text" id="gender" name="gender" class="form-control"
                                           placeholder="Gender Search..." required>
                                    <span class="input-group-btn">
                                      <button type="submit" class="btn btn-warning btn-flat location-btn"><i
                                                  class="fa fa-search"></i>
                                      </button>
                                    </span>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.partials.modal')
@section('page_specific_styles')
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
@endsection


@section('page_specific_scripts')
    <script src="{{ asset('plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('plugins/fastclick/fastclick.js') }}"></script>
    <script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>
@endsection
@section('page_specific_inline_scripts')
    <script>
        jQuery(document).ready(function ($) {
            //Initialize Select2 Elements
            $(".select2").select2();

            $('.send-btnee').click(function () {
                var id = this.id;
                var link = '{{url("tuition/matched/")}}/' + id;
                document.getElementById('myFrame').setAttribute('src', link);
                $("#wait").modal();
                $('#tuition').modal();
                $('#wait').modal('hide');

            });

            $('#targetd').on('submit', function (e) {

                e.preventDefault();
                var location_preference = $("#location_preference").val();
                var subject_preference = $("#subject_preference").val();
                var formData = new FormData($(this)[0]);

                $.ajax({
                    url: '{{url("automatched")}}',
                    type: "POST",
                    data: {'location_preference': location_preference, '_token': $("#_token").val()},
                    async: true,
                    beforeSend: function () {
                        $("#wait").modal();
                    },

                    success: function (data) {

                        var link = '{{url("tuition/automatched")}}';
                        document.getElementById('myFrame').setAttribute('src', link);
                        $("#wait").modal();
                        $('#tuition').modal();
                        $('#wait').modal('hide');


                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });

            });


        });
    </script>
@endsection
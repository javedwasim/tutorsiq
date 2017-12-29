<div class="box box-primary">
    <a href="#">
        <div class="box-header with-border" data-widget="collapse"><i class="fa fa-minus pull-right" style="font-size:12px;
        margin-top: 5px;"></i>

            <h1 class="box-title">Search Filters</h1>
        </div>
    </a>

    <!-- /.box-header -->
    <div class="box-body">
        <!-- form start -->
        <form class="" method="post" action="{{ url('tuition/search') }}" id="filterform">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="locations_list" id="locations_list" value="<?php echo $location_list; ?>">

            <!-- /.row -->
            <div class="row" id="first-row">
                <!-- /.col -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Zones</label>
                        <select class="form-control select2" id="zone" name="zone"
                                data-placeholder="Select Zones">
                            <option value="">All</option>
                            <?php foreach($zones as $z){ ?>
                                <option value="<?php echo $z->id; ?>"
                                <?php if (isset($filters['zone']) && ($z->id==$filters['zone'])) echo "selected";  ?>>
                                    <?php echo $z->name; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Locations</label>
                        <select class="form-control select2" multiple="multiple" id="locations" name="locations[]"
                                data-placeholder="Select Location" style="width: 100%;">
                            <?php    foreach($locations as $l){ ?>
                            <option value="<?php echo $l->lid; ?>"
                            <?php if (isset($filters['locations']) && in_array($l->lid, $filters['locations'])) echo "selected";  ?>>
                                <?php echo $l->locations; ?>
                            </option>
                            <?php } ?>
                        </select>

                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Fee Range</label>
                        <select name="fee_range" id="fee_range" class="form-control select2"
                                data-placeholder="Select Fee Range">
                            <option value=""></option>
                            <option value="4-8"
                            <?php if (isset($filters['fee_range']) && $filters['fee_range'] == '4-8') echo "selected"; ?> >4000 to 8000</option>
                            <option value="8-12"
                            <?php if (isset($filters['fee_range']) && $filters['fee_range'] == '8-12') echo "selected"; ?> >8000 to 12000</option>
                            <option value="12-15"
                            <?php if (isset($filters['fee_range']) && $filters['fee_range'] == '12-15') echo "selected"; ?> >12000 to 15000</option>
                            <option value="15-20"
                            <?php if (isset($filters['fee_range']) && $filters['fee_range'] == '15-20') echo "selected"; ?> >15000 to 20000</option>
                            <option value="20-30"
                            <?php if (isset($filters['fee_range']) && $filters['fee_range'] == '20-30') echo "selected"; ?> >20000 to 30000</option>
                            <option value="30-40"
                            <?php if (isset($filters['fee_range']) && $filters['fee_range'] == '30-40') echo "selected"; ?> >30000 to 40000</option>

                        </select>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <!-- /.row -->
            <div class="row" id="second-row">

                <!-- /.col -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Institutes</label>
                        <select class="form-control select2" multiple="multiple" id="institutes" name="institutes[]"
                                data-placeholder="Select Institutes" style="width: 100%;">
                            <?php    foreach($institutes as $inst){ ?>
                                <option value="<?php echo $inst->id; ?>"
                                <?php if (isset($filters['institutes']) && in_array($inst->id, $filters['institutes'])) echo "selected";  ?>>
                                    <?php echo $inst->name; ?>
                                </option>
                            <?php } ?>
                        </select>

                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Gender</label>
                        <select name="gender" id="gender" class="form-control select2"
                                data-placeholder="Select Gender">
                            <option value=""></option>
                            <?php foreach($genders as $gender): ?>
                                <option value="<?php echo $gender->id; ?>"
                                    <?php if(isset($filters['gender']) && $filters['gender'] == $gender->id) echo "selected"; ?> ><?php echo $gender->name; ?></option>
                            <?php endforeach; ?>

                        </select>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->

                <!-- /.col -->
                <div class="col-md-4">

                    <div class="form-group">
                        <label>Code</label>
                        <input type="text" class="form-control " id="tuition_code" name="tuition_code"
                               placeholder="Tuition Code"
                               value="<?php echo isset($filters['tuition_code']) ? $filters['tuition_code'] : '' ?>">
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->

            </div>
            <!-- /.row -->
            <div class="row" id="third-row">

                <!-- /.col -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Suitable Timing</label>
                        <select class="form-control select2" id="suitable_timings"
                                name="suitable_timings" data-placeholder="Select Suitable Timings">
                            <option value=""></option>
                            <option value="morning"
                            <?php if (isset($filters['suitable_timings']) && $filters['suitable_timings'] == 'morning') echo "selected"; ?>>
                                Morning
                            </option>
                            <option value="evening"
                            <?php if (isset($filters['suitable_timings']) && $filters['suitable_timings'] == 'evening') echo "selected"; ?>>
                                Evening
                            </option>
                            <option value="anytime"
                            <?php if (isset($filters['suitable_timings']) && $filters['suitable_timings'] == 'anytime') echo "selected"; ?>>
                                Both(Morning & Evening)
                            </option>
                        </select>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->

                <!-- /.col -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Teaching Duration</label>
                        <select name="teaching_duration" id="teaching_duration"
                                class="form-control  select2" data-placeholder="Select Teaching Duration">
                            <option value=""></option>
                            <option value="15"
                                <?php if(isset($filters['teaching_duration'])&&($filters['teaching_duration']==15)) {echo "selected";} ?>>15 Mins</option>
                            <option value="30"
                                <?php if(isset($filters['teaching_duration'])&&($filters['teaching_duration']==30)) {echo "selected";} ?>>30 Mins</option>
                            <option value="45"
                                <?php if(isset($filters['teaching_duration'])&&($filters['teaching_duration']==45)) {echo "selected";} ?>>45 Mins</option>
                            <option value="60"
                                <?php if(isset($filters['teaching_duration'])&&($filters['teaching_duration']==60)) {echo "selected";} ?>>1 Hour</option>
                            <option value="75"
                                <?php if(isset($filters['teaching_duration'])&&($filters['teaching_duration']==75)) {echo "selected";} ?>>1 Hour 15 Mins</option>
                            <option value="90"
                                <?php if(isset($filters['teaching_duration'])&&($filters['teaching_duration']==90)) {echo "selected";} ?>>1 Hour 30 Mins</option>
                            <option value="105"
                                <?php if(isset($filters['teaching_duration'])&&($filters['teaching_duration']==105)) {echo "selected";} ?>>1 Hour 45 Mins</option>
                            <option value="120"
                                <?php if(isset($filters['teaching_duration'])&&($filters['teaching_duration']==120)) {echo "selected";} ?>>2 Hours</option>
                            <option value="135"
                                <?php if(isset($filters['teaching_duration'])&&($filters['teaching_duration']==135)) {echo "selected";} ?>>2 Hours 15 Mins</option>
                            <option value="150"
                                <?php if(isset($filters['teaching_duration'])&&($filters['teaching_duration']==150)) {echo "selected";} ?>>2 Hours 30 Mins</option>
                            <option value="165"
                                <?php if(isset($filters['teaching_duration'])&&($filters['teaching_duration']==165)) {echo "selected";} ?>>2 Hours 45 Mins</option>
                            <option value="180"
                                <?php if(isset($filters['teaching_duration'])&&($filters['teaching_duration']==180)) {echo "selected";} ?>>3 Hours</option>
                            <option value="195"
                                <?php if(isset($filters['teaching_duration'])&&($filters['teaching_duration']==195)) {echo "selected";} ?>>3 Hours 15 Mins</option>
                            <option value="210"
                                <?php if(isset($filters['teaching_duration'])&&($filters['teaching_duration']==210)) {echo "selected";} ?>>3 Hours 30 Mins</option>
                            <option value="225"
                                <?php if(isset($filters['teaching_duration'])&&($filters['teaching_duration']==225)) {echo "selected";} ?>>3 Hours 45 Mins</option>
                            <option value="240"
                                <?php if(isset($filters['teaching_duration'])&&($filters['teaching_duration']==240)) {echo "selected";} ?>>4 Hours</option>
                            <option value="255"
                                <?php if(isset($filters['teaching_duration'])&&($filters['teaching_duration']==255)) {echo "selected";} ?>>4 Hours 15 Mins</option>
                            <option value="270"
                                <?php if(isset($filters['teaching_duration'])&&($filters['teaching_duration']==270)) {echo "selected";} ?>>4 Hours 30 Mins</option>
                            <option value="285"
                                <?php if(isset($filters['teaching_duration'])&&($filters['teaching_duration']==285)) {echo "selected";} ?>>4 Hours 45 Mins</option>
                            <option value="300"
                                <?php if(isset($filters['teaching_duration'])&&($filters['teaching_duration']==300)) {echo "selected";} ?>>5 Hours</option>
                            <option value="315"
                                <?php if(isset($filters['teaching_duration'])&&($filters['teaching_duration']==315)) {echo "selected";} ?>>5 Hours 15 Mins</option>
                            <option value="330"
                                <?php if(isset($filters['teaching_duration'])&&($filters['teaching_duration']==330)) {echo "selected";} ?>>5 Hours 30 Mins</option>
                            <option value="345"
                                <?php if(isset($filters['teaching_duration'])&&($filters['teaching_duration']==345)) {echo "selected";} ?>>5 Hours 45 Mins</option>
                            <option value="360"
                                <?php if(isset($filters['teaching_duration'])&&($filters['teaching_duration']==360)) {echo "selected";} ?>>6 Hours</option>
                        </select>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->

                <!-- /.col -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Age</label>
                        <select class="form-control select2 select2-hidden-accessible" id="age" name="age"
                                data-placeholder="Select Age">
                            <option value=""></option>
                            <option value="15"
                                <?php if(isset($filters['age'])&&($filters['age']=='15')) echo "selected"; ?>>
                                15 plus</option>
                            <option value="25"
                                <?php if(isset($filters['age'])&&($filters['age']=='25')) echo "selected";  ?>>
                                25 plus
                            </option>
                            <option value="30"
                                <?php if(isset($filters['age'])&&($filters['age']=='30')) echo "selected";  ?>>
                                30 plus
                            </option>
                            <option value="35"
                                <?php if(isset($filters['age'])&&($filters['age']=='35')) echo "selected";  ?>>
                                35 plus
                            </option>
                            <option value="40"
                                <?php if(isset($filters['age'])&&($filters['age']=='40')) echo "selected";  ?>>
                                40 plus
                            </option>
                            <option value="50"
                                <?php if(isset($filters['age'])&&($filters['age']=='50')) echo "selected";  ?>>
                                50 plus
                            </option>
                            <option value="60"
                                <?php if(isset($filters['age'])&&($filters['age']=='60')) echo "selected";  ?>>
                                60 plus
                            </option>
                        </select>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->

            </div>

            <div class="row" id="fourth-row">

                <!-- /.col -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Grade</label>
                        <select class="form-control select2" id="class" name="class"
                            data-placeholder="Select Class">
                            <option value="">ALL</option>
                            <?php foreach($classes as $class): ?>
                            <option value="<?php echo $class->id; ?>"<?php if (isset($filters['class']) && $class->id == $filters['class']) echo "selected" ?>><?php echo $class->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Subject</label>
                        <select class="form-control select2" id="subject" name="subject"
                                data-placeholder="Select Subject">
                            <option value="">ALL</option>
                            <?php foreach($subjects as $subject): ?>
                            <option value="<?php echo $subject->id; ?>"<?php if (isset($filters['subject']) && $subject->id == $filters['subject']) echo "selected" ?>><?php echo $subject->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <!-- /.form-group -->
                </div>
                <!-- /.col -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Categories</label>
                        <select class="form-control select2" multiple="multiple" id="categories" name="categories[]"
                                data-placeholder="Select Categories" style="width: 100%;">
                            <?php    foreach($categories as $c){ ?>
                            <option value="<?php echo $c->id; ?>"
                            <?php if (isset($filters['categories']) && in_array($c->id, $filters['categories'])) echo "selected";  ?>>
                                <?php echo $c->name; ?>
                            </option>
                            <?php } ?>

                        </select>

                    </div>
                    <!-- /.form-group -->
                </div>

            </div>

            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" name="reset" value="reset" id="reset" class="btn btn-warning pull-right"><i
                            class="fa fa-fw fa-undo"></i> Reset
                </button>
                <button type="submit" id="submit_pagesize" class="btn btn-success pull-right" style="margin-right: 5px;"><i
                            class="fa fa-fw fa-search"></i> Search
                </button>

            </div>
            <input type="hidden" name="pagesize" id="pagesize" value="9" />
            <!-- /.box-footer -->
        </form>
        <!-- end form -->
    </div>
</div>


@section('page_specific_styles')
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
@endsection

@section('page_specific_scripts')

    <script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('plugins/jQuerymask/jquery.mask.min.js') }}"></script>
    <script src="{{ asset('plugins/iCheck/icheck.min.js') }}" type="text/javascript"></script>

@endsection
            <!-- /.box -->
@section('page_specific_inline_scripts')

    @if (session('message'))
        <script>
            jQuery(document).ready(function ($) {
                toastr.success('Applied Successfully!');
            });
        </script>
    @endif

    <script>
        //load mathced teachers
        jQuery(document).ready(function ($) {

            $("#tuition_date").change(function () {

                var date_filter = $("#tuition_date").val();
                var rightNow = new Date();
                var resultDate = rightNow.toISOString().slice(0,10).replace(/-/g,"-");

                $('#start_date').attr("readonly", true);
                $('#end_date').attr("readonly", true);
                $('#start_date').datepicker({autoclose: false});
                $('#end_date').datepicker({autoclose: false});

                if(date_filter==0){

                    start_date = resultDate;
                    end_date = resultDate;
                    $("#start_date").val(start_date);
                    $("#end_date").val(end_date);


                }
                else if(date_filter==7){

                    end_date = resultDate;
                    newDate  = rightNow.setDate(rightNow.getDate() - 7);
                    rightNow = new Date(newDate);
                    start_date = rightNow.toISOString().slice(0,10).replace(/-/g,"-");
                    $("#start_date").val(start_date);
                    $("#end_date").val(end_date);

                }
                else if(date_filter==14){

                    end_date = resultDate;
                    newDate  = rightNow.setDate(rightNow.getDate() - 14);
                    rightNow = new Date(newDate);
                    start_date = rightNow.toISOString().slice(0,10).replace(/-/g,"-");
                    $("#start_date").val(start_date);
                    $("#end_date").val(end_date);
                }
                else if(date_filter==30){
                    end_date = resultDate;
                    newDate  = rightNow.setDate(rightNow.getDate() - 30);
                    rightNow = new Date(newDate);
                    start_date = rightNow.toISOString().slice(0,10).replace(/-/g,"-");
                    $("#start_date").val(start_date);
                    $("#end_date").val(end_date);


                }
                else if(date_filter==90){

                    end_date = resultDate;
                    newDate  = rightNow.setDate(rightNow.getDate() - 90);
                    rightNow = new Date(newDate);
                    start_date = rightNow.toISOString().slice(0,10).replace(/-/g,"-");
                    $("#start_date").val(start_date);
                    $("#end_date").val(end_date);

                }
                else{

                    $('#start_date').attr("readonly", false);
                    $('#end_date').attr("readonly", false);
                    $('#start_date').datepicker({autoclose: true});
                    $('#end_date').datepicker({autoclose: true});

                }


            });


        });
    </script>

    <script>

        jQuery(document).ready(function ($) {
            //Initialize Select2 Elements
            $(".select2").select2();

            //Load locations on change zone
            $("#zone").change(function () {

                var zone_id = $("#zone").val();
                $.ajax({

                    url: '{{url("zone/locations")}}/'+zone_id,
                    type: "GET",
                    data: {'zone_id':zone_id},
                    async: false,

                    beforeSend: function () {
                        $("#wait").modal();
                    },
                    success: function (response) {

                        var test = JSON.stringify(response);
                        var result = JSON.parse(test);
                        var data = result['options'];
                        var location_list = result['locations_list'];

                        $('#locations')
                            .find('option')
                            .remove()
                            .end()
                            .append(data);
                        $('#locations_list').val(location_list);
                        $('#wait').modal('hide');

                    },
                    cache: false,
                    contentType: false,
                    processData: false

                });

            });

            //Load subjects on change grade
            $("#class").change(function () {

                var class_id = $("#class").val();
                $.ajax({

                    url: '{{url("class/subjects")}}/'+class_id,
                    type: "GET",
                    data: {'class_id':class_id},
                    async: false,

                    beforeSend: function () {
                        $("#wait").modal();
                    },
                    success: function (response) {

                        var test = JSON.stringify(response);
                        var result = JSON.parse(test);
                        var data = result['options'];
                        var subjects_list = result['subjects_list'];
                        //console.log(result);

                        $('#subject')
                            .find('option')
                            .remove()
                            .end()
                            .append(data);
                        $('#subjects_list').val(subjects_list);
                        $('#wait').modal('hide');

                    },
                    cache: false,
                    contentType: false,
                    processData: false

                });

            });

            $('.viewdetail').on('submit', function (e) {

                e.preventDefault();
                var formData = new FormData($(this)[0]);

                $.ajax({
                    url: '{{url("viewdetail")}}',
                    type: "POST",
                    data: formData,
                    async: false,
                    beforeSend: function () {
                        $("#wait").modal();
                    },
                    success: function (response) {

                        $('#wait').modal('hide');
                        $(".applied").remove();
                        $(".btn-primary").remove();
                        $(".btn-default").remove();
                        $(".btn-warning").remove();

                        var test = JSON.stringify(response);
                        var data = JSON.parse(test);

                        console.log(data);
                        var class_name = data['class_name'];
                        var location = data['location'];
                        var special_notes = data['special_notes'];
                        var subject_name = data['subject_name'];
                        var teacher_id = data['teacher_id'];
                        var tuition_id = data['tuition_id'];
                        var application_count = data['application_count'];
                        var notes = data['application'];
                        var suitable_timing = data['details'][0]['suitable_timings'];
                        var institutes = data['details'][0]['institute_names'];
                        var teaching_duration = data['details'][0]['teaching_duration'];
                        var tuition_initial_fee = data['details'][0]['tuition_fee'] * 1000;
                        var teaching_final_fee = data['details'][0]['tuition_max_fee'] * 1000;
                        var no_of_students = data['details'][0]['no_of_students'];

                        $('#applications').append('<div class="form-group applied">' +
                                '<label  class="col-sm-3 control-label text-align-left">No of Students</label>' +
                                '<div class="col-sm-9">' +
                                '<label  class="control-label">' + no_of_students + '</label>' +
                                '</div>' +
                                '</div>' +
                                '<div class="form-group applied">' +
                                '<label  class="col-sm-3 control-label text-align-left">Class</label>' +
                                '<div class="col-sm-9">' +
                                '<label  class="control-label">' + class_name + '</label>' +
                                '</div>' +
                                '</div>' +
                                '<div class="form-group applied">' +
                                '<label  class="col-sm-3 control-label text-align-left">Subject</label>' +
                                '<div class="col-sm-9">' +
                                '<label  class="control-label text-align-left">' + subject_name + '</label>' +
                                '</div>' +
                                '</div>' +
                                '<div class="form-group applied">' +
                                '<label  class="col-sm-3 control-label text-align-left">Institutes</label>' +
                                '<div class="col-sm-9">' +
                                '<label  class="control-label">' + institutes + '</label>' +
                                '</div>' +
                                '</div>' +
                                '<div class="form-group applied">' +
                                '<label  class="col-sm-3 control-label text-align-left">Location</label>' +
                                '<div class="col-sm-9">' +
                                '<label class="control-label">' + location + '</label>' +
                                '</div>' +
                                '</div>' +
                                '<div class="form-group applied">' +
                                '<label  class="col-sm-3 control-label text-align-left">Special Notes</label>' +
                                '<div class="col-sm-9">' +
                                '<label class="control-label" style="text-align: left;">' + special_notes + '</label>' +
                                '</div>' +
                                '</div>' +
                                '<div class="form-group applied">' +
                                '<label  class="col-sm-3 control-label text-align-left">Suitable Timing</label>' +
                                '<div class="col-sm-9">' +
                                '<label  class="control-label">' + suitable_timing + '</label>' +
                                '</div>' +
                                '</div>' +
                                '<div class="form-group applied">' +
                                '<label  class="col-sm-3 control-label text-align-left">Teaching Duration</label>' +
                                '<div class="col-sm-9">' +
                                '<label  class="control-label">' + teaching_duration + ' ' +'mins' +'</label>' +
                                '</div>' +
                                '</div>' +
                                '<div class="form-group applied">' +
                                '<label  class="col-sm-3 control-label text-align-left">Fee Range</label>' +
                                '<div class="col-sm-9">' +
                                '<label  class="control-label text-align-left">'
                                + tuition_initial_fee + ' ' +'to' + ' ' + teaching_final_fee +'</label>' +
                                '</div>' +
                                '</div>' +
                                '<div class="form-group applied">' +
                                '<label  class="col-sm-3 control-label text-align-left">Application Notes</label>' +
                                '<div class="col-sm-9">' +
                                '<textarea class="form-control" name="application_note" rows="3" placeholder="Enter Application Note...">' + notes + '</textarea>' +
                                '<input type="hidden" name="tuition_id" value="' + tuition_id + '" > <input type="hidden" name="teacher_id" value = "' + teacher_id + '">' +
                                '<input type="hidden" name = "advance_search" value = "advanced" >' +
                                '</div>' +
                                '</div>'
                        );

                        if (application_count > 0) {

                            $('.modal-footer').append('<button type="button" class="btn btn-medium btn-default pull-left" value="cancel" data-dismiss="modal"' +
                                    'name="cancel"><i class="fa fa-fw fa-remove"></i>Close' +
                                    ' </button>' +
                                    '<button type="button" class="btn btn-medium btn-warning pull-right" value="save" name="save"' +
                                    '><i class="fa fa-fw fa-external-link-square"></i>Already Applied' +
                                    '</button>'
                            );

                        } else {

                            $('.modal-footer').append('<button type="button" class="btn btn-medium btn-default pull-left" value="cancel" data-dismiss="modal"' +
                                    'name="cancel"><i class="fa fa-fw fa-remove"></i>Close' +
                                    ' </button>' +

                                    '<button type="submit" class="btn btn-medium btn-primary pull-right" value="save" name="save"' +
                                    '><i class="fa fa-fw fa-external-link-square"></i>Apply Now' +
                                    '</button>');

                        }


                        $('#teacher_applications').modal();

                    },
                    cache: false,
                    contentType: false,
                    processData: false

                });

            });
        });

        $(document).on('show.bs.modal', '.modal', function () {
            var zIndex = 1040 + (10 * $('.modal:visible').length);
            $(this).css('z-index', zIndex);
            setTimeout(function () {
                $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
            }, 0);
        });
        $(function () {

            $(".select2").select2();

            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });


        $('#reset').click(function () {
            $(this).closest('form').find("input[type=text], select").val("");
        });
    </script>
@endsection
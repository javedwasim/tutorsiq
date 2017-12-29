<div style="margin-bottom: 5px; overflow: hidden;">
<input type="checkbox" class="minimal1 followup_select_all" name="followup_select_all" id="followup_select_all" value="">
Select All <span class="text" style="font-weight: 700; padding-left: 5px">Number of tuitions selected:<small class="label label-warning badge slelected-followup-tuitions"></small></span>
    <a href="{{ url('#') }}" class="btn btn-primary pull-right" id="followup-changeLabelbtn" style="margin-left: 10px;">
        <i class="fa fa-stack-exchange fa-lg"></i> Change Labels
    </a>
    <a href="{{ url('#') }}" class="btn btn-primary pull-right" id="followup-changeStatusbtn" style="margin-left: 10px;">
        <i class="fa fa-exchange fa-lg"></i> Update  Status
    </a>
    <a href="{{ url('#') }}" class="btn btn-primary pull-right" id="followup-isStarred" style="margin-left: 10px;">
        <i class="fa fa-star-half-full"></i> Star/UnStar
    </a>
    <a href="{{ url('#') }}" class="btn btn-primary pull-right" id="followup-summary" style="margin-left: 10px;">
        <i class="fa fa-fw fa-list-ul"></i> Show Summary
    </a>
        <a href="{{ url('#') }}" class="btn btn-primary pull-right" id="followup-shortView" style="margin-left: 10px;">
            <i class="fa fa-fw fa-eye"></i> Short View
        </a>
</div>
{{--Followup Select All End--}}
<!-- SELECT2 EXAMPLE -->
<div class="box box-primary"  id="filter-heading">
    <a href="#">
        <div class="box-header with-border test" data-widget="collapse"><i class="fa fa-plus pull-right" style="font-size:12px;
        margin-top: 5px;"></i>

            <h1 class="box-title">Search Filters</h1>
        </div>
    </a>
    <!-- /.box-header -->
    <div class="box-body">

        {!! Form::open(array('url'=>'admin/tuitions/followup','method'=>'POST', 'id'=>'myform')) !!}
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="row">
            <!-- /.col -->
            <div class="col-md-4">
                <div class="form-group">
                    <label>Labels</label>
                    <select class="form-control select2"  id="label_p" name="label_p[]"
                            multiple data-placeholder="Select Labels">
                        <?php foreach ($labels as $label): ?>
                        <option value="<?php echo $label->id; ?>"
                        <?php if(isset($filter['label_p']) &&  in_array($label->id,$filter['label_p'])) echo "selected";?> >
                            <?php echo $label->name; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <!-- /.col -->

            <!-- /.col -->
            <div class="col-md-4">
                <div class="form-group">
                    <label>Locations</label>
                    <select class="form-control select2"  id="locations_p" name="locations_p[]"
                            multiple data-placeholder="Select Locations">
                        <?php foreach ($locations as $location): ?>
                        <option value="<?php echo $location->id; ?>"
                        <?php if(isset($filter['locations_p']) &&  in_array($location->id,$filter['locations_p'])) echo "selected";?> >
                            <?php echo $location->locations; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <!-- /.col -->

            <!-- /.col -->
            <div class="col-md-4">
                <div class="form-group">
                    <label>Tuition Status</label>
                    <select class="select2 form-control" id="assign_status" name="assign_status"
                            data-placeholder="Select Status">
                        <option></option>
                        <?php foreach ($tuitionStatus as $status): ?>
                        <option value="<?php echo $status->id ?>"<?php if (isset($filter['assign_status']) && $filter['assign_status'] == $status->id) echo "selected" ?>>
                            <?php echo $status->name ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <!-- /.col -->

        </div>
        <div class="row" id="second-row">

            <!-- /.col -->
            <div class="col-md-4">
                <div class="form-group">
                    <label>Grade</label>
                    <select class="select2 form-control" id="class" name="class">
                        <option value="0">All</option>
                        <?php foreach($classes as $class): ?>
                        <option value="<?php echo $class->id; ?>"
                        <?php if (isset($filter['class']) && $class->id == $filter['class']) echo "selected" ?>>
                            <?php echo $class->name; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <!-- /.col -->

            <!-- /.col -->
            <div class="col-md-4">
                <div class="form-group">
                    <label>Subjects</label>
                    <select class="select2 form-control" id="subject" name="subject">
                        <option value="0">All</option>
                        <?php foreach($subjects as $subject): ?>
                        <option value="<?php echo $subject->sid; ?>"
                        <?php   if (isset($filter['subject']) && $subject->sid == $filter['subject'] ) echo "selected" ?>>
                            <?php echo $subject->name; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <!-- /.col -->

            <!-- /.col -->
            <div class="col-md-4">
                <div class="form-group">
                    <label>Contact No</label>
                    <input type="text" name="contactNo" id="contactNo" class="form-control" value="<?php if(isset($filter['contactNo'])) echo $filter['contactNo']; ?>">
                </div>
            </div>
            <!-- /.col -->

        </div>
        <div class="row" id="third-row">

            <!-- /.col -->
            <div class="col-md-4">
                <div class="form-group">
                    <label>Contact Person</label>
                    <input type="text" name="contactPerson" id="contactPerson" class="form-control" value="<?php if(isset($filter['contactPerson'])) echo $filter['contactPerson']; ?>">
                </div>
            </div>
            <!-- /.col -->

        </div>

        <!-- /.box-footer -->
        <div class="box-footer">
            <button type="submit" name="reset" value="reset" id="reset" class="btn btn-warning pull-right"><i
                        class="fa fa-fw fa-undo"></i> Reset
            </button>
            <button type="submit" id="submit_pagesize" class="btn btn-success pull-right" style="margin-right: 5px;"><i
                        class="fa fa-fw fa-search"></i> Search
            </button>

        </div>
        <!-- /.box-footer -->

        {!! Form::close() !!}

    </div>
</div>

@include('layouts.partials.modal')
@section('page_specific_styles')
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@endsection


@section('page_specific_scripts')
    <script src="{{ asset('plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('plugins/fastclick/fastclick.js') }}"></script>
    <script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('/plugins/iCheck/icheck.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/js/tuitions.js') }}" type="text/javascript"></script>
@endsection
@section('page_specific_inline_scripts')
    @if (session('status'))
        <script>
            jQuery(document).ready(function ($) {
                toastr.success('{{session('status')}}');
            });
        </script>
    @endif
    <script>

        //update Tuition Status
        $(document).on('change',".tuition_status_p", function(){

            var str = this.value;
            var res = str.split("|");
            var statusid = res[0];
            var tuitionid = res[1];
            var color = res[2];
            $('.tuitionFinalFee').val(tuitionid);

            $.ajax({

                url: '{{url("admin/tuition/status/update")}}',
                type: "post",
                data: {'tuitionid': tuitionid, 'statusid':statusid,'color':color, '_token': $('input[name=_token]').val()},

                success: function (response) {

                    var test = JSON.stringify(response);
                    var data = JSON.parse(test);

                    var tuitionid = response['statusColor']['tuitionid'];
                    var color = response['statusColor']['color'];


                    var status = data['success'];
                    console.log(status);
                    if((statusid == 10) && (status == false)){
                        $('#TuitionFinalFee').modal();
                    }else{
                        toastr.success('Status Change Successfully!');
                    }


                }

            });


        });

        //tuition short view
        $('.short-view1').click(function () {

            var id = this.id;
            $.ajax({

                url: 'shortview/'+id,
                type: "GET",
                data: {'tuitionid':id},
                beforeSend: function () {
                    $("#wait").modal();
                },
                success: function (data) {

                    var test = JSON.stringify(data);
                    var data = JSON.parse(test);

                    var smsText = data['smsText'];
                    var tuitionText = data['tuitionText'];

                    $('#wait').modal('hide');
                    $('#sms_text').val(smsText);
                    $('#tuition_text').val(tuitionText);
                    $('#tuition_short_view').modal();

                },
                cache: false,
                contentType: false,
                processData: false

            });


        });

        jQuery(document).ready(function ($) {

            //Tuition follow search filter minimized by default
            $( "#filter-heading" ).addClass( "collapsed-box" );
            //Initialize Tooltip for subjects
            $('[data-toggle="tooltip"]').tooltip();
            //Initialize Select2 Elements
            $(".select2").select2();
            //Initialize date picker
            $('#qestartDate').datepicker();
            //fade out alert message
            $(".alert").fadeOut(6000);

            $('.send-btnee').click(function () {
                var id = this.id;
                var link = '{{url("tuition/matched/")}}/' + id;
                document.getElementById('myFrame').setAttribute('src', link);
                $("#wait").modal();
                $('#tuition').modal();
                $('#wait').modal('hide');

            });

            $('.quickEdit').on('click', function (e) {

                var tuitionid = this.id;
                $.ajax({

                    url: '{{url("admin/tuition/quick/edit")}}',
                    type: "post",
                    data: {'tuitionid': tuitionid, '_token': $('input[name=_token]').val()},

                    success: function (response) {

                        var test = JSON.stringify(response);
                        var data = JSON.parse(test);
                        var options = data['tuitionStatus'];
                        var tuition_start_date = data['tuition']['tuition_start_date'];
                        var tuition_final_fee = data['tuition']['tuition_final_fee'];
                        var partner_share = data['tuition']['partner_share'];
                        var agent_one_share = data['tuition']['agent_one_share'];
                        var agent_two_share = data['tuition']['agent_two_share'];
                        var tuition_id = data['tuition']['id'];

                        $('#qeTuitionStatus')
                            .find('option')
                            .remove()
                            .end()
                            .append(options);

                        $('#qetid').val(tuition_id);
                        $('#qestartDate').val(tuition_start_date);
                        $('#qetuitionFee').val(tuition_final_fee);
                        $('#qepartnerShare').val(partner_share);
                        $('#qeagentOneShare').val(agent_one_share);
                        $('#qeagentTwoShare').val(agent_two_share);


                        console.log(data['tuition']);

                        $('#quickEdit').modal();

                    }

                });

            });

            //Followup Select Counter
            var SelectCounter = 0;

            $('[name="tuitionFollowups[]"]').on('ifChecked', function(event){

                SelectCounter = $('[name="tuitionFollowups[]"]:checked').length;
                if(SelectCounter>0){
                    $(".slelected-followup-tuitions").empty();
                    $(".slelected-followup-tuitions").append(SelectCounter);
                }
            });

            $('[name="tuitionFollowups[]"]').on('ifUnchecked', function(event){

                SelectCounter = $('[name="tuitionFollowups[]"]:checked').length;
                $(".slelected-followup-tuitions").empty();
                $(".slelected-followup-tuitions").append(SelectCounter);

            });

            //Followup Select All

            //check on unched for bulk action.
            $('.followup_select_all').on('ifChecked', function(event){
                $('input').iCheck('check');

            });

            $('.followup_select_all').on('ifUnchecked', function(event){
                $('input').iCheck('uncheck');
            });
            //initialize icheck box
            $(function () {

                $('input.minimal1').iCheck({
                    checkboxClass: 'icheckbox_square-blue',
                    radioClass: 'iradio_square-blue',
                    increaseArea: '20%' // optional
                });
            });
//               Followup Select All End

        });
        //Change Grade Event
        $("#class").change(function () {

            var class_id = $("#class").val();
            $.ajax({

                url: '{{url("admin/tuition/subjects")}}/'+class_id,
                type: "GET",
                data: {'class_id':class_id},
                async: false,

                beforeSend: function () {
                    $("#wait").modal();
                },
                success: function (data) {
                    console.log(data);
                    $('#subject')
                        .find('option')
                        .remove()
                        .end()
                        .append(data);

                    $('#wait').modal('hide');


                },
                cache: false,
                contentType: false,
                processData: false

            });

        });
    </script>

    <script>
        $('.tuition-started').on('click', function (e) {
            var test = this.val;

            var tuitionid = this.id;
            var is_started = $('#is_started-'+tuitionid).val();
            var startchange = $(tuitionid+tuitionid);

            console.log("is_started from page"+is_started);

            $.ajax({
                url: '{{url("admin/tuition/starred")}}',
                type: "post",
                data: {'tuitionid': tuitionid, 'isStarted':is_started, '_token': $('input[name=_token]').val()},

                success: function (response) {
                    toastr.success('Status Updated Successfully!');
                    var test = JSON.stringify(response);
                    var data = JSON.parse(test);
                    console.log(data);
                    var tuitionid = data['tuitionid'];
                    var isStarted = data['isStarted'];

                    if (isStarted ==1){

                        $('#star-'+tuitionid).removeClass("fa fa-star");
                        $('#star-'+tuitionid).addClass( "fa fa-star-o" );
                        $('#is_started-'+tuitionid).val(0);

                    } else {

                        $('#star-'+tuitionid).removeClass("fa fa-star-o");
                        $('#star-'+tuitionid).addClass( "fa fa-star" );
                        $('#is_started-'+tuitionid).val(1);

                    }

                },
            })
        });

    </script>
@endsection
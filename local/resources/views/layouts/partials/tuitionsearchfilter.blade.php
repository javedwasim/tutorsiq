<div class="box box-primary" id="filter-heading">
    <a href="#" id ="collpased-btn">
        <div class="box-header with-border" data-widget="collapse"><i class="fa fa-plus pull-right" style="font-size:12px;
            margin-top: 5px;"></i>
            <h1 class="box-title">Search Filters</h1>
        </div>
    </a>

    <!-- /.box-header -->
    <div class="box-body filter-detail" style="display: none;">
        <!-- form start -->
        <form class="" method="post" action="{{ url('admin/tuitions') }}" id="filterform">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="row" id="first-row">

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Tuition Date</label>
                        <select class="form-control" id="tuition_date" name="tuition_date">
                            <option value="0">Today</option>
                            <option value="7"<?php  if (isset($tuition_filter) && $tuition_filter == 7) echo "selected";?>>
                                Last 7 Days
                            </option>
                            <option value="14"<?php  if (isset($tuition_filter) && $tuition_filter == 14) echo "selected";?>>
                                Last 14 Days
                            </option>
                            <option value="30"<?php  if (isset($tuition_filter) && $tuition_filter == 30) echo "selected";?>>
                                Last 30 Days
                            </option>
                            <option value="90"<?php  if (isset($tuition_filter) && $tuition_filter == 90) echo "selected"; ?> >
                                Last 90 Days
                            </option>
                            <option value="custom"<?php  if (isset($tuition_filter) && $tuition_filter == 'custom') echo "selected";?>>
                                Custom
                            </option>
                        </select>
                    </div>
                    <!-- /.form-group -->

                </div>
                <!-- /.col -->

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Start Date</label>
                        <input type="text" class="form-control" id="start_date" name="start_date"
                               placeholder="Tuition Date"
                               value="<?php echo isset($filters['start_date']) ? $filters['start_date'] : $tuition_start_date; ?>"
                               readonly>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->

                <div class="col-md-4">
                    <div class="form-group">
                        <label>End Date</label>
                        <input type="text" class="form-control" id="end_date" name="end_date" placeholder="Tuition Date"
                               value="<?php echo isset($filters['end_date']) ? $filters['end_date'] : $tuition_end_date ?>"
                               readonly>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->


            </div>
            <!-- /.row -->
            <div class="row" id="second-row">

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Grade</label>
                        <select class="form-control" id="class" name="class">
                            <option value="0">All</option>
                            <?php foreach($classes as $class): ?>
                                <option value="<?php echo $class->id; ?>"
                                    <?php if (isset($filters['class']) && $class->id == $filters['class']) echo "selected" ?>>
                                    <?php echo $class->name; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <!-- /.form-group -->
                </div>

                <!-- /.col -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Subjects</label>
                        <select class="form-control" id="subject" name="subject">
                            <option value="0">All</option>
                            <?php foreach($subjects as $subject): ?>
                                <option value="<?php echo $subject->sid; ?>"
                                    <?php   if (isset($filters['subject']) && $subject->sid == $filters['subject'] ) echo "selected" ?>>
                                    <?php echo $subject->name; ?>
                                </option>
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

            <!-- /.row -->
            <div class="row" id="third-row">

                <!-- /.col -->
                <div class="col-md-4">

                    <div class="form-group">
                        <label>Tuition Status</label>
                        <select class="form-control" id="assign_status" name="assign_status">
                            <option value="0">All</option>
                            <?php foreach ($assign_status as $status): ?>
                                <option value="<?php echo $status->id ?>"<?php if (isset($filters['assign_status']) && $filters['assign_status'] == $status->id) echo "selected" ?>>
                                    <?php echo $status->name ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->
                <!-- /.col -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Is Active ?</label>
                        <select class="form-control" id="is_active" name="is_active">
                            <option value="0">All</option>
                            <option value="1"<?php if (isset($filters['is_active']) && $filters['is_active'] == 1) echo "selected" ?>>
                                Yes
                            </option>
                            <option value="2"<?php if (isset($filters['is_active']) && $filters['is_active'] == 2) echo "selected" ?>>
                                No
                            </option>
                        </select>
                    </div>
                    <!-- /.form-group -->
                </div>

                <div class="col-md-4">

                    <div class="form-group">
                        <label>Is Approved ?</label>
                        <select class="form-control" id="is_approved" name="is_approved">
                            <option value="0">All</option>
                            <option value="1"<?php if (isset($filters['is_approved']) && $filters['is_approved'] == 1) echo "selected" ?>>
                                Yes
                            </option>
                            <option value="2"<?php if (isset($filters['is_approved']) && $filters['is_approved'] == 2) echo "selected" ?>>
                                No
                            </option>
                        </select>

                    </div>
                </div>

            </div>

            <!-- /.row -->
            <div class="row" id="fourth-row">
                <!-- /.col -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Labels</label>
                        <select class="form-control select2" multiple="multiple" id="labels" name="labels[]"
                                data-placeholder="Select Labels" style="width: 100%;">
                            <?php    foreach($labels as $label){ ?>
                            <option value="<?php echo $label->id; ?>"
                            <?php if (isset($filters['labels']) && in_array($label->id, $filters['labels'])) echo "selected";  ?>>
                                <?php echo $label->name; ?>
                            </option>
                            <?php } ?>

                        </select>
                        <input type="hidden" name="pagesize" id="pagesize" value="" />
                    </div>
                    <!-- /.form-group -->
                </div>

                <!-- /.col -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Categories</label>
                        <select class="form-control select2" multiple="multiple" id="categories" name="categories[]"
                                data-placeholder="Select Category" style="width: 100%;">
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

                <!-- /.col -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Locations</label>
                        <select class="form-control select2" multiple="multiple" id="locations" name="locations[]"
                                data-placeholder="Select Location" style="width: 100%;">
                            <?php    foreach($locations as $l){ ?>
                            <option value="<?php echo $l->id; ?>"
                            <?php if (isset($filters['locations']) && in_array($l->id, $filters['locations'])) echo "selected";  ?>>
                                <?php echo $l->locations; ?>
                            </option>
                            <?php } ?>

                        </select>

                    </div>
                    <!-- /.form-group -->
                </div>

            </div>

            <!-- /.row -->
            <div class="row" id="fifth-row">
                <!-- /.col -->
                <div class="col-md-4">

                    <div class="form-group">
                        <label>Contact Number</label>
                        <input type="text" class="form-control " id="contact_no" name="contact_no"
                               placeholder="Contact Number"
                               value="<?php echo isset($filters['contact_no']) ? $filters['contact_no'] : '' ?>">
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->
                <div class="col-md-4">

                    <div class="form-group">
                        <label>Contact Person</label>
                        <input type="text" class="form-control" id="contact_person" name="contact_person"
                               placeholder="Contact Person"
                               value="<?php echo isset($filters['contact_person']) ? $filters['contact_person'] : '' ?>">
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->

                <!-- /.col -->
                <div class="col-md-4">

                    <div class="form-group">
                        <label>Gender</label>
                        <Select name="teacher_gender" id="teacher_gender" class="form-control"  >
                            <option value="0">All</option>
                            <option value="1"<?php if(isset($filters['teacher_gender']) && $filters['teacher_gender'] == '1') echo "Selected"; ?>>Male</option>
                            <option value="2"<?php if(isset($filters['teacher_gender']) && $filters['teacher_gender'] == '2') echo "Selected"; ?>>Female</option>
                        </Select>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->

            </div>
            <!-- /.box-body -->

            <!-- /.row -->
            <div class="row" id="sixth-row">
                <!-- /.col -->
                <div class="col-md-4">

                    <div class="form-group">
                        <label>Suitable Timing</label>
                        <Select name="suitable_timings" id="suitable_timings" class="form-control"  >
                            <option value="">All</option>
                            <option value="morning"<?php if(isset($filters['suitable_timings']) && $filters['suitable_timings'] == 'morning') echo "Selected"; ?>>Morning</option>
                            <option value="evening"<?php if(isset($filters['suitable_timings']) && $filters['suitable_timings'] == 'evening') echo "Selected"; ?>>Evening</option>
                            <option value="anytime"<?php if(isset($filters['suitable_timings']) && $filters['suitable_timings'] == 'anytime') echo "Selected"; ?>>AnyTime</option>
                        </Select>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->

                <!-- /.col -->
                <div class="col-md-4">
                    <!-- /.form-group -->
                    <div class="form-group">
                        <label>Tutiion Fee</label>
                        <select class="form-control select2"  id="tuition_fee" name="tuition_fee"
                                data-placeholder="Select Tuition Fee" style="width: 100%;">
                            <option value="0">All</option>
                            <?php for($i=1; $i<=100; $i++){ ?>
                            <option value="<?php echo $i; ?>"
                            <?php if(isset($filters['tuition_fee']) && $filters['tuition_fee']== $i) echo "selected"; ?>><?php echo $i."K"; ?></option>
                            <?php } ?>
                        </select>

                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->

            </div>

            <div class="box-footer">
                <div class="pull-right">
                    <button type="submit" id="submit_pagesize" class="btn btn-success"><i
                                class="fa fa-fw fa-search"></i> Search
                    </button>

                    <button type="submit" name="reset" value="reset" id="reset" class="btn btn-warning"><i
                                class="fa fa-fw fa-undo" ></i> Reset
                    </button>
                </div>
            </div>
            <!-- /.box-footer -->
        </form>
        <!-- end form -->
    </div>
</div>


@section('page_specific_styles')
    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datepicker/datepicker3.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.0/css/responsive.dataTables.min.css">
@endsection
@section('page_specific_scripts')
    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <!-- FastClick -->
    <script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('plugins/jQuerymask/jquery.mask.min.js') }}"></script>
    <script src="{{ asset('/plugins/iCheck/icheck.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/js/tuitions.js') }}" type="text/javascript"></script>

    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js"></script>
    <script type="text/javascript" language="javascript"
            src="//cdn.datatables.net/plug-ins/1.10.11/dataRender/ellipsis.js"></script>
@endsection
            <!-- /.box -->
@section('page_specific_inline_scripts')
    @if (session('status'))
        <script>
            jQuery(document).ready(function ($) {
                toastr.success('{{session('status')}}');
            });
        </script>
    @endif
    @if (session('warning'))
        <script>
            jQuery(document).ready(function ($) {
                toastr.warning('{{session('warning')}}');
            });
        </script>
    @endif
    <!-- load mathced teachers -->
    <script>

        jQuery(document).ready(function ($) {

            var SelectCounter = 0;

            $('[name="globalTuition[]"]').on('ifChecked', function(event){

                SelectCounter = $('[name="globalTuition[]"]:checked').length;
                if(SelectCounter>0){
                    $(".slelected-tuitions").empty();
                    $(".slelected-tuitions").append(SelectCounter);
                }
            });

            $('[name="globalTuition[]"]').on('ifUnchecked', function(event){

                SelectCounter = $('[name="globalTuition[]"]:checked').length;
                $(".slelected-tuitions").empty();
                $(".slelected-tuitions").append(SelectCounter);

            });



            //check on unched for bulk action.
            $('.select_all').on('ifChecked', function(event){
                $('input').iCheck('check');

            });

            $('.select_all').on('ifUnchecked', function(event){
                $('input').iCheck('uncheck');
            });

            $('#start_date').datepicker({autoclose: false});
            $('#end_date').datepicker({autoclose: false});

            //initialize icheck box
            $(function () {

                $('input.minimal').iCheck({
                    checkboxClass: 'icheckbox_square-blue',
                    radioClass: 'iradio_square-blue',
                    increaseArea: '20%' // optional
                });
            });

            //fadeout message.
            $(".alert").fadeOut(6000);

            var date_filter = $("#tuition_date").val();

            if (date_filter == 'custom') {
                $('#start_date').attr("readonly", false);
                $('#end_date').attr("readonly", false);

            } else {
                $('#start_date').attr("readonly", true);
                $('#end_date').attr("readonly", true);
            }

            //set datatable attributes
            var table = $('#example1').DataTable({
                "paging": false,
                "info": false,
                'searching': false,
                "columnDefs": [

                    {
                        "targets": [ 0 ],
                        "orderable": false,

                    },

                    {
                        "targets": [ 1 ],
                        "visible": false,

                    },
                    {
                        "targets": -1,
                       "orderable": false,


                    },
                    {
                        targets: 4, render: $.fn.dataTable.render.ellipsis( 45, true )

                    }


                ],


            });

            //page size filter
            $("#page_size").change(function () {

                var page_size = $("#page_size").val();
                $('#pagesize').val(page_size);

                $('#submit_pagesize').trigger('click');

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


            $('#example1 tbody').on( 'click', 'tr', function () {

                if ( $(this).hasClass('selected') ) {
                    $(this).removeClass('selected');
                    $(this).addClass('selected');
                    $( "div" ).removeClass( "collapsed-box" );
                    $( ".teacher-detial" ).removeClass( "fa-minus" );
                    $( ".teacher-detial" ).addClass( "fa-plus" );
                    $("#teacher-detial").css("display", "block");

                }
                else {

                    $(this).addClass( "selected" );
                    table.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');

                    $( "div" ).removeClass( "collapsed-box" );
                    $( ".teacher-detial" ).removeClass( "fa-plus" );
                    $( ".teacher-detial" ).addClass( "fa-minus" );
                    $("#teacher-detial").css("display", "block");

                    var data = table.row( this ).data();
                    if(table.row( this ).index()==0){
                        $('#'+data[1]).trigger('click');
                    }

                    $( "#filter-heading" ).addClass( "collapsed-box" );

                    //$('#'+data[1]).trigger('click');
                    //alert( 'You clicked on '+data[1]+'\'s row' );
                }


            } );

            setTimeout(function(){$('#example1 tbody tr:eq(0)').click(); console.log('time in');   }, 1);
            //matched teachers
            $('.send-btn').click(function () {
                var id = this.id;
                var link = '{{url("admin/teachers/matched/")}}/'+id;
                document.getElementById('myFrame').setAttribute('src', link);
                $("#wait").modal();
                $('#tuition').modal();
                $('#wait').modal('hide');

            });

            //tuition short view
            $('.short-view').click(function () {

                var id = this.id;
                $.ajax({

                    url: 'tuition/short/view/'+id,
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

            //Assigned Tuitions
            $('#assign_tuition').on('submit', function (e) {

                e.preventDefault();

                var formData = new FormData($(this)[0]);
                var tuition_id = $("#tuitionid").val();
                var token =$('input[name=_token]').val();

                $.ajax({

                    url: '{{url("admin/tuitions/assigned")}}',
                    type: "POST",
                    data: formData,
                    async: false,
                    beforeSend: function () {
                        $("#wait").modal();
                    },
                    success: function (data) {

                        $(".child").remove();
                        $('#tuition_details_list').DataTable().clear().draw();

                        var test = JSON.stringify(data);
                        var data = JSON.parse(test);
                        //console.log(data);
                        $('#wait').modal('hide');
                        $('#assign_teacher').modal('hide');

                        var success = data['success'];

                        if (success == 'save') {

                            LoadTuitionDetails(tuition_id,token);
                            toastr.success('Tuition Assigned Successfully!');
                        }

                    },
                    cache: false,
                    contentType: false,
                    processData: false

                });


            });



        });

    </script>

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

        $(document).on('show.bs.modal', '.modal', function () {

            var zIndex = 1040 + (10 * $('.modal:visible').length);
            $(this).css('z-index', zIndex);
            setTimeout(function () {
                $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
            }, 0);



        });
    </script>

@endsection
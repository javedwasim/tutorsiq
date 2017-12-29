@section('page_specific_styles')
        <!-- DataTables -->
<link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables/jquery.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.0/css/responsive.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css">
{{--<link rel="stylesheet" href="{{ asset('plugins/datatables/bootstrap.min.css') }}">--}}
@endsection

@section('page_specific_scripts')

        <!-- DataTables -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
<!-- SlimScroll -->
<script src="{{ asset('plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>
<!-- FastClick -->
<script src="{{ asset('plugins/fastclick/fastclick.js') }}"></script>
<script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>
<script src="{{ asset('/js/tutors.js') }}"></script>
<script src="{{ asset('/plugins/iCheck/icheck.min.js') }}" type="text/javascript"></script>

<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js"></script>
<script type="text/javascript" language="javascript"
        src="//cdn.datatables.net/plug-ins/1.10.11/dataRender/ellipsis.js"></script>

@endsection

@section('page_specific_inline_scripts')
    @if (session('status'))
        <script>
            jQuery(document).ready(function ($) {
                toastr.success('{{session('status')}}');
            });
        </script>
    @endif
    @if (session('deleteTuitions'))
        <script>
            jQuery(document).ready(function ($) {
                toastr.success('{{session('deleteTuitions')}}');
            });
        </script>
    @endif
    @if (session('warning'))
        <script>
            jQuery(document).ready(function ($) {
                toastr.success('{{session('warning')}}');
            });
        </script>
    @endif

    <script>
        jQuery(document).ready(function ($) {

            //Counter for tuitions
            var SelectCounter = 0;

            $('[name="globalTuition[]"]').on('ifChecked', function(event){

                SelectCounter = $('[name="globalTuition[]"]:checked').length;
                if(SelectCounter>0){
                    $(".selected-g-tuition").empty();
                    $(".selected-g-tuition").append(SelectCounter);
                }
            });

            $('[name="globalTuition[]"]').on('ifUnchecked', function(event){

                SelectCounter = $('[name="globalTuition[]"]:checked').length;
                $(".selected-g-tuition").empty();
                $(".selected-g-tuition").append(SelectCounter);

            });

            //check on unched for bulk action.
            $('.select_all').on('ifChecked', function(event){
                $('input').iCheck('check');

            });

            $('.select_all').on('ifUnchecked', function(event){
                $('input').iCheck('uncheck');
            });

            $(function () {
                $(".select2").select2();
                $('input').iCheck({
                    checkboxClass: 'icheckbox_square-blue',
                    radioClass: 'iradio_square-blue',
                    increaseArea: '20%' // optional
                });
            });

            $('.teacher_photo').on('click', function () {

                $("#teacher_profile_image").attr("src", this.id);
                $("#teacher_profile_photo").modal();

            });
            //Initialize Select2 Elements
            $(".select2").select2();

            //set datatable attributes

            var table = $('#example1').DataTable({
                "paging": false,
                "info": false,
                'searching': false,
                "order": [[ 1, "asc" ]],
                "columnDefs": [
                    {
                        targets: 2, render: $.fn.dataTable.render.ellipsis( 45, true )

                    }
                ]
            });



            $("#page_size").change(function () {

                var page_size = $("#page_size").val();
                $('#pagesize').val(page_size);
                $('#submit_pagesize').trigger('click');

            });
            //confirm to delete
            $('.del-btn').click(function () {
                return confirm("Are you sure to delete this list!");
            });
            //delete selected ttuitions
            $('.delete-selected').on('click', function() {

                var confirms =  confirm("Are you sure to delete selected tuitions!");

                var globalTuition = [];
                $("input[name='globalTuition[]']:checked").each(function(){globalTuition.push(

                    $(this).val() );

                });

                if((confirms)  && (globalTuition.length>0) ){

                    $('#ids').val(globalTuition);
                    $("#globalTuitions").submit();

                }else if((confirms) && (globalTuition.length == 0) ){

                    alert('Select tuitions to be delete!');
                }


            });

            //Is Approved Button
            $('#is_approved').on('click', function() {


                var confirms =  confirm("Are you sure to change selected tuitions status?");

                var globalTuitions = [];
                $("input[name='globalTuition[]']:checked").each(function(){globalTuitions.push(

                    $(this).val() );

                });

                if((confirms)  && (globalTuitions.length>0) ){

                    $('#is_approved_popup_id').val(globalTuitions);
                    var ids = $('#starid').val();
                    $('#is_approved_popup').modal();
                    //console.log(ids);

                }else if((confirms) && (globalTuitions.length == 0) ){

                    alert('Please Select tuitions!');
                }
            });
        });

    </script>
    <script>
        $('#new_line').change(function() {

            if($(this).is(":checked")) {

                var str = $('#phone_list').val();
                var newStr = str.split(";").join("\n");
                $('#phone_list').val(newStr);


            }else{

                var str = $('#phone_list').val();
                $('#phone_list').val(str.replace(/\n/g, ";"));

            }
        });

        $(".sms-text").click(function(){
            var link = '{{url("admin/broadcast/tuittions/sms")}}';
            document.getElementById('smsbroadcast').setAttribute('src', link);
            $("#wait").modal();
            $('#sms').modal();
            $('#wait').modal('hide');
        });

        $('.send-btn').click(function () {

            var link = '{{url("admin/global/teachers/matched")}}';
            document.getElementById('myFrame').setAttribute('src', link);
            $("#wait").modal();
            $('#tuition').modal();
            $('#wait').modal('hide');

        });


    </script>


@endsection
@include('layouts.partials.modal')
<div class="box box-primary">

    <div class="box-header with-border">
        <i class=" pull-right" style="font-size:12px; margin-top: 5px;"></i>
        <h1 class="box-title">Tuitions BroadCast List</h1>
    </div>

    <div class="box-body">
        <div class="row">
            <div class="col-xs-12">

                <div class="box">

                    <div class="box-header" style="padding-bottom: 0px;">
                        <?php if(isset($tuitions) ): ?>

                            <div class="margin pull-right">

                                <form class="pull-right form-group" method="post" action="{{ url('admin/delete/selected/global/tuitions') }}" id="globalTuitions">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="ids" id="ids" value="">
                                    <button type="button" class="btn btn-danger pull-right delete-selected" >
                                        <i class="fa fa-fw fa-trash-o"></i>Delete Seletected</button>&nbsp;&nbsp;

                                </form>


                                <a class="btn btn-danger pull-right del-btn"  href="{{'empty/tuitions'}}"><i class="fa fa-fw fa-trash-o"></i>Empty Global List</a>

                            </div>

                            <div class="margin pull-left">
                                <div class="btn-group ">

                                    <a class="btn label-primary pull-right" title="Is Approved"
                                       id="is_approved" style="margin-right: 5px;">
                                        <span class="label label-primary" style="font-size: 13px;">
                                            <i class="fa fa-fw fa-check-square-o"></i>
                                             Is Approved
                                        </span>
                                    </a>

                                    <a class="btn label-primary send-btn pull-right" title="View Matched"
                                           id="global_teachers" style="margin-right: 5px;">
                                        <span class="label label-primary" style="font-size: 13px;">
                                            <i class="fa fa-fw fa-globe"></i>
                                             View Matched Teachers
                                        </span>
                                    </a>

                                    <form class="pull-right form-group phone_numbers" method="post" action="{{ url('admin/global/tuition/broadcast') }}" id="tuition_broadcast">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="tuition_id" id="tuition_id_pphone" value="<?php echo $tuition_ids; ?>">
                                        <button type="button" class="btn btn-primary sms-text" ><i class="fa fa-fw fa-file-text"></i>Get SMS Text</button>&nbsp;&nbsp;
                                    </form>


                                </div>
                            </div>


                        <?php endif; ?>
                    </div>
                                <!-- /.box-header -->
                        <div class="box-body" style="margin-top: -10px;">

                            <input type="checkbox" class="minimal select_all" name="select_all" id="select_all" value="">
                            Select All <span class="text" style="font-weight: 700; padding-left: 5px">Number of tuitions selected:<small class="label label-warning badge selected-g-tuition"></small></span>

                            <table id="example1" class="table table-bordered table-striped responsive nowrap"
                                   cellspacing="0" width="100%">
                                <thead>
                                <tr style="background-color: #367fa9; color: #fefefe;">
                                    <th>Code</th>
                                    <th>Contact Person</th>
                                    <th>Grade&Subjects</th>
                                    <th>Status</th>
                                    <th>Location</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>


                                    <tbody>
                                        <?php foreach($tuitions as $tuition): ?>

                                        <tr>
                                            <td>
                                                <div id="element1">
                                                    <input type="checkbox" class="minimal" name="globalTuition[]" value="<?php echo $tuition->id; ?>">
                                                </div>
                                                <div id="element2">
                                                    <?php echo $tuition->tuition_code; ?>
                                                </div>

                                            </td>
                                            <td>
                                                <div><?php echo $tuition->contact_person; ?></div>
                                                <div><span class=""><a href="tel:<?php echo $tuition->contact_no; ?>">
                                                            <?php echo $tuition->contact_no; ?></a></span></div>
                                                <div><span class=""><a href="tel:<?php echo $tuition->contact_no; ?>">
                                                            <?php echo $tuition->contact_no2; ?></a></span></div>
                                            </td>
                                            <td><?php echo $tuition->subjects; ?></td>
                                            <td><?php echo $tuition->tstatus; ?></td>
                                            <td><?php echo $tuition->locations; ?></td>

                                            <td>

                                                {!! Form::open(array('url'=>'admin/global/tuition/delete','method'=>'POST', 'id'=>'deleteGlobalTuition',
                                                'onsubmit'=>"return confirm('Are you sure to delete this tutiion!');")) !!}

                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="hidden" name="ids" value="<?php echo $tuition->id;  ?>">
                                                    <button type="submit" class="globalTuitionDeletebtn">
                                                        <span class="label label-danger">
                                                            <i class="fa fa-fw fa-trash-o"></i>
                                                    </span></button>

                                                {!! Form::close() !!}

                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>

                            </table>
                            <div class="box-footer">
                                <div style="display:inline-block;">
                                    <select class="form-control" id="page_size" name="page_size" style="width: 110px;">
                                        <option value="50"<?php if (isset($pagesize) && $pagesize == 50) echo "selected"; ?>>
                                            50
                                        </option>
                                        <option value="100"<?php if (isset($pagesize) && $pagesize == 100) echo "selected"; ?>>
                                            100
                                        </option>
                                        <option value="150"<?php if (isset($pagesize) && $pagesize == 150) echo "selected"; ?>>
                                            150
                                        </option>
                                        <option value="200"<?php if (isset($pagesize) && $pagesize == 200) echo "selected"; ?>>
                                            200
                                        </option>
                                    </select>
                                </div>
                                <?php  echo $tuitions->render(); ?>
                                <div class="" style="display:inline-block;">Showing
                                    <?php echo isset($offset) ? $offset : ''; ?> to
                                    <?php echo isset($perpage_record) ? $perpage_record : ''; ?> of
                                    <?php echo $totalRecords; ?> entries
                                </div>
                            </div>
                            <!-- /.box-body -->
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
<form class="" method="post" action="{{ url('admin/global/tuitions') }}" id="filterform" style="display: none;">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="pagesize" id="pagesize" value="" />
    <button type="submit" id="submit_pagesize" class="btn btn-success pull-right"><i
                class="fa fa-fw fa-search"></i> submit
    </button>
</form>
{{--Is Approved Button Popup Modal--}}
<div class="modal fade" id="is_approved_popup" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header popup-header">
                <h5 class="modal-title" id="exampleModalLabel">Change Selected Tuition Approvals</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- remove previous labels and add new lables -->
            <form action="{{ url('admin/tuition/broadcast/approval') }}" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="modal-body">
                    <input type="hidden" id="is_approved_popup_id"  name = "is_approved_popup_id" value="" >
                    <div class="form-group">
                        <label for="recipient-name" class="form-control-label">Select Approval Status</label>
                        <select class="form-control select2"  id="is_approved_popup" name="is_approved_popup"
                                data-placeholder="Select Approval Status"  required>
                            <option value=""></option>
                            <option value="1">Approved</option>
                            <option value="0">Not Approved</option>

                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-fw fa-plus-circle"></i>Change Approval Status</button>
                </div>
            </form>
        </div>
    </div>
</div>
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
    @if (session('deleted'))
        <script>
            jQuery(document).ready(function ($) {
                toastr.success('{{session('deleted')}}');
            });
        </script>
    @endif
    <script>
        jQuery(document).ready(function ($) {

            //Counter for teachers
            var SelectCounter = 0;

            $('[name="globalTeacher[]"]').on('ifChecked', function(event){

                SelectCounter = $('[name="globalTeacher[]"]:checked').length;
                if(SelectCounter>0){
                    $(".selected-g-teacher").empty();
                    $(".selected-g-teacher").append(SelectCounter);
                }
            });

            $('[name="globalTeacher[]"]').on('ifUnchecked', function(event){

                SelectCounter = $('[name="globalTeacher[]"]:checked').length;
                $(".selected-g-teacher").empty();
                $(".selected-g-teacher").append(SelectCounter);

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
                $('input.minimal').iCheck({
                    checkboxClass: 'icheckbox_square-blue',
                    radioClass: 'iradio_square-blue',
                    increaseArea: '20%' // optional
                });
            });


            //set datatable attributes
            var table = $('#example1').DataTable({
                "paging": false,
                "ordering": true,
                "info": false,
                'searching': false,
                "order": [[ 5, "asc" ]],
                "columnDefs": [
                      {
                        "targets": [ -1 ],
                        "orderable": false,
                    },

                ],


            });

            $("#page_size").change(function () {

                var page_size = $("#page_size").val();
                $('#pagesize').val(page_size);
                $('#submit_pagesize').trigger('click');

            });

            $('.del-btn').click(function () {
                return confirm("Are you sure to delete this list!");
            });

            //delete selected teachers
            $('.delete-selected').on('click', function() {

                var confirms =  confirm("Are you sure to delete selected teachers!");

                var globalTeacher = [];
                $("input[name='globalTeacher[]']:checked").each(function(){globalTeacher.push(

                    $(this).val() );

                });
                //console.log(globalTeacher);
                if((confirms)  && (globalTeacher.length>0) ){

                    $('#ids').val(globalTeacher);
                    $("#globalTeachers").submit();

                }else if((confirms) && (globalTeacher.length == 0) ){

                    alert('Select tuitions to be delete!');
                }


            });

            $(".phone_numbers").click(function(){

                document.getElementById("remove_first").checked= false;

                var str  = $('#contact_no').val();
                $('#phone_list').val(str);
                $("#new_line").prop("checked", false);

                $('#phone_number').modal('show');


            });

        });

    </script>

@endsection
@include('layouts.partials.modal')
<div class="box box-primary">

    <div class="box-header with-border">
        <i class="pull-right" style="font-size:12px;
    margin-top: 5px;"></i>
        <h1 class="box-title">Teachers BroadCast List</h1>
    </div>

    <div class="box-body">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">

                    <div class="box-header" style="padding-bottom: 0px;">
                        <?php if(!empty($contactNos) ): ?>
                            <div class="margin pull-right">
                                <form class="pull-right form-group" method="post" action="{{ url('admin/delete/selected/global/teachers') }}" id="globalTeachers">

                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="ids" id="ids" value="">
                                    <button type="button" class="btn btn-danger pull-right delete-selected" >
                                        <i class="fa fa-fw fa-trash-o"></i>Delete Seletected</button>&nbsp;&nbsp;

                                </form>

                                <a class="btn btn-danger pull-right del-btn" style="margin-right: 10px;" href="{{'empty'}}"><i class="fa fa-fw fa-trash-o"></i>Empty Global List</a>
                            </div>

                            <div class="margin pull-left">
                                <form class="pull-right form-group phone_numbers" method="post" action="{{ url('admin/teacher/phone/broadcast') }}" id="phone_numbers">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="teacher_id[]" id="teacher_id_pphone" value="">
                                    <input type="hidden" name="contact_no" id="contact_no" value="<?php echo isset($contactNos)? $contactNos:''; ?>">
                                    <button type="button" class="btn btn-primary phone_numbers" ><i class="fa fa-fw fa-phone-square"></i>Get Phone Numbers</button>&nbsp;&nbsp;
                                </form>

                                <a class="btn btn-warning pull-right" style="margin-right: 10px;" href="{{'email'}}"><i class="fa fa-fw fa-envelope"></i>Send Email</a>
                            </div>
                        <?php endif; ?>
                    </div>

                                <!-- /.box-header -->
                        <div class="box-body" style="margin-top: -10px;">

                            <input type="checkbox" class="minimal select_all" name="select_all" id="select_all" value="">
                            Select All <span class="text" style="font-weight: 700; padding-left: 5px">Number of teachers selected:<small class="label label-warning badge selected-g-teacher"></small></span>

                            <table id="example1" class="table table-bordered table-striped responsive nowrap"
                                   cellspacing="0" width="100%">
                                <thead>
                                <tr style="background-color: #367fa9; color: #fefefe;">
                                    <th>Picture</th>
                                    <th>Name</th>
                                    <th>Age</th>
                                    <th>Qualifications</th>
                                    <th>Exp(Years)</th>
                                    <th>Fee Package</th>
                                    <th>Band</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach($teachers as $teacher): ?>

                                <div id="element1" class="element1" style="display: none;">
                                    <input type="checkbox" name="teacher_broadcast_list" class="teacher_broadcast_list"
                                           value="<?php echo $teacher->id; ?>" checked />
                                </div>
                                <tr>

                                    <td>
                                        <div id="element1">
                                            <input type="checkbox" class="minimal" name="globalTeacher[]" value="<?php echo $teacher->id; ?>">

                                                <?php if(isset($teacher->teacher_photo) && !empty($teacher->teacher_photo)): ?>
                                                <a href="#" class="teacher_photo" title="Profile Photo"
                                                   id="{{URL::asset("/local/teachers/$teacher->id/photo/$teacher->teacher_photo")}}">
                                                    <img src="{{URL::asset("/local/teachers/$teacher->id/photo/$teacher->teacher_photo")}}"
                                                         alt="profile Pic" class="img-circle teacher-photo">
                                                </a>
                                                <?php endif; ?>
                                            </div>

                                    </td>
                                    <td>
                                        <div>
                                            {!! Form::open(array('url'=>'admin/teachers','method'=>'POST', 'id'=>'myform')) !!}
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" name="teacher_id" value="<?php echo $teacher->id;  ?>">
                                            <input type="hidden" name="email" value="<?php echo $teacher->email;  ?>">
                                            <button type="submit"
                                                    class="btn btn-link"><?php echo $teacher->fullname; ?></button>
                                            {!! Form::close() !!}
                                        </div>


                                        <div>
                                            <span class="mobile_number"><a href="tel:<?php echo $teacher->mobile1 ?>"><?php echo $teacher->mobile1 ?></a> </span>
                                        </div>

                                    </td>
                                    <td><?php echo $teacher->age."+";; ?></td>
                                    <td><?php echo $teacher->qualifications; ?></td>
                                    <td style="text-align: center"><?php echo $teacher->experience."+";?></td>
                                    <td style="text-align: center">
                                        <?php echo $teacher->expected_minimum_fee."K - ".$teacher->expected_max_fee."K";?></td>
                                    <td><?php echo $teacher->band_name; ?></td>

                                    <td style="text-align: center">
                                        <a class="btn  del-btn" href="teachers/delete/<?php echo $teacher->global_id; ?>"
                                           title="Delete" style="padding: 0 0;">
                                            <span class="label label-danger">
                                                <i class="fa fa-fw fa-trash-o" style="font-size: 10px;"></i>
                                            </span>
                                        </a>
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
                                <?php echo $teachers->render(); ?>
                                <div class="" style="display:inline-block;">Showing
                                    <?php echo isset($offset) ? $offset : ''; ?> to
                                    <?php echo isset($perpage_record) ? $perpage_record : ''; ?> of
                                    <?php echo $count_teachers; ?> entries
                                </div>
                            </div>
                            <!-- /.box-body -->
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
<form class="" method="post" action="{{ url('admin/global/teachers') }}" id="filterform" style="display: none;">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="pagesize" id="pagesize" value="" />
    <button type="submit" id="submit_pagesize" class="btn btn-success pull-right"><i
                class="fa fa-fw fa-search"></i> submit
    </button>
</form>
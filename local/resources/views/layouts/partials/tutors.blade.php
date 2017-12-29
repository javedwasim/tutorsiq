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
<div class="box box-primary">
    <a href="#">
        <div class="box-header with-border" data-widget="collapse">
            <i class="fa fa-minus pull-right" style="font-size:12px;
        margin-top: 5px;"></i>

            <h1 class="box-title">Teachers List</h1>
        </div>
    </a>

    <div class="box-body">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">

                    <div class="box-header">

                        <form class="pull-right form-group" method="post" action="{{ url('admin/global') }}" id="globalTeachers">

                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="ids" id="ids" value="">
                            <button type="button" class="btn btn-primary pull-right broadcast-selected" >
                                <i class="fa fa-fw fa-volume-up"></i>Add to BroadCast List</button>&nbsp;&nbsp;

                        </form>

                        <a href="{{ url('admin/teachers/add') }}" class="btn btn-primary pull-right">
                            <i class="fa fa-plus-circle fa-lg"></i> Add New
                        </a>

                        <span class="text" style="font-weight: 700;">Please select below <small
                                    class="label label-primary"><i class="fa fa fa-fw fa-reorder"></i></small>
                            &nbsp;to view teacher details
                        </span>

                    </div>

                       <div class="box-body" style="margin-top: -10px;">

                           <input type="checkbox" class="minimal select_all" name="select_all" id="select_all" value="">
                           Select All <span class="text" style="font-weight: 700; padding-left: 5px">Number of teachers selected:<small class="label label-warning badge slelected-tuitions"></small></span>

                            <table id="teachers" class="table table-bordered table-striped responsive nowrap"
                                   cellspacing="0" width="100%">
                                <thead>
                                <tr style="background-color: #367fa9; color: #fefefe;">
                                    <th>&nbsp;</th>
                                    <th>Picture</th>
                                    <th>Name</th>
                                    <th>Age</th>
                                    <th>Qualifications</th>
                                    <th>Exp(Years)</th>
                                    <th>ID</th>
                                    <th>Fee Package</th>
                                    <th>Band</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach($teachers as $teacher): ?>
                                <tr>
                                    <td style="width: 1%;">
                                        {!! Form::open(array('url'=>'#','method'=>'POST', 'id'=>'myform')) !!}
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                        <a class="btn  send-btn" id="<?php echo $teacher->id;  ?>" title="Details">
                                            <span class="label label-primary">
                                                 <i class="fa fa-fw fa-reorder" style="font-size: 10px;"></i>
                                            </span>
                                        </a>
                                        {!! Form::close() !!}
                                    </td>
                                    <td>
                                        <div id="element1">
                                            <input type="checkbox" class="minimal" name="globalTeachers[]" value="<?php echo $teacher->id; ?>">
                                            <?php if(isset($teacher->teacher_photo) && !empty($teacher->teacher_photo)): ?>
                                                <a href="javascript:void(0);" class="teacher_photo" title="Profile Photo"
                                                   id="{{URL::asset("/local/teachers/$teacher->id/photo/$teacher->teacher_photo")}}">
                                                    <img src="{{URL::asset("/local/teachers/$teacher->id/photo/$teacher->teacher_photo")}}"
                                                         alt="profile Pic" class="img-circle teacher-photo">
                                                </a>
                                            <?php endif; ?>

                                        </div>
                                        <div id="element2">
                                            <a href="javascript:void(0);" class="volume text-red global-list" title="Add to Global Teachers List" id="<?php echo $teacher->id; ?>">
                                                <i class="fa fa-fw fa-bullhorn"></i>
                                            </a>
                                        </div>

                                    </td>
                                    <td>
                                        <div>
                                            {!! Form::open(array('url'=>'admin/teachers','method'=>'POST', 'id'=>'myform')) !!}
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" name="teacher_id" value="<?php echo $teacher->id;  ?>">
                                            <input type="hidden" name="email" value="<?php echo $teacher->email;  ?>">
                                            <button type="submit"
                                                    class="btn btn-link button-padding"><?php echo $teacher->fullname; ?></button>
                                            {!! Form::close() !!}
                                        </div>
                                        <div>
                                            <span class="">
                                              <a href="tel:<?php echo $teacher->mobile1 ?>"><?php echo $teacher->mobile1 ?></a>
                                              <a href="tel:<?php echo $teacher->personal_contactno2 ?>"><?php echo $teacher->personal_contactno2 ?></a>
                                            </span>
                                        </div>

                                    </td>
                                    <td><?php echo $teacher->age."+"; ?></td>
                                    <td><?php echo $teacher->qualifications; ?></td>
                                    <td style="text-align: center"><?php echo $teacher->experience."+";?></td>
                                    <td><?php echo $teacher->id; ?></td>
                                    <td style="text-align: center">
                                        <?php echo $teacher->expected_minimum_fee."K - ".$teacher->expected_max_fee."K";?></td>
                                    <td><?php echo $teacher->band_name; ?></td>

                                    <td>
                                        <a class="btn  edit-btn" href="teachers/update/<?php echo $teacher->id; ?>"
                                           title="Edit" style="padding: 0 0;">
                                            <span class="label label-success">
                                                <i class="fa fa-fw fa-edit" style="font-size: 10px;"></i>
                                            </span>
                                        </a>
                                        <a class="btn short-view" title="Short View" id="<?php echo $teacher->id;  ?>"
                                           href="javascript:void(0);" style="padding: 0 0;">
                                            <span class="label label-primary">
                                                <i class="fa fa-fw fa-eye" style="font-size: 10px;"></i>
                                            </span>
                                        </a>
                                        <a class="btn  del-btn" href="teachers/delete/<?php echo $teacher->id; ?>"
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
                                        <option value="50"<?php if(isset($pagesize) && $pagesize==50) echo "selected"; ?>>50</option>
                                        <option value="100"<?php if(isset($pagesize) && $pagesize==100) echo "selected"; ?>>100</option>
                                        <option value="150"<?php if(isset($pagesize) && $pagesize==150) echo "selected"; ?>>150</option>
                                        <option value="200"<?php if(isset($pagesize) && $pagesize==200) echo "selected"; ?>>200</option>
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

@include('layouts.partials.modal')
<!-- /.box -->
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

    @if (session('warning'))
        <script>
            jQuery(document).ready(function ($) {
                toastr.warning('{{session('warning')}}');
            });
        </script>
    @endif

    <script>

        $(document).ready(function () {
            $(".alert").fadeOut(6000);
        });

        jQuery(document).ready(function ($) {

            //counter of selected teachers.
            var SelectCounter = 0;

            $('[name="globalTeachers[]"]').on('ifChecked', function(event){

                SelectCounter = $('[name="globalTeachers[]"]:checked').length;
                if(SelectCounter>0){
                    $(".slelected-tuitions").empty();
                    $(".slelected-tuitions").append(SelectCounter);
                }
            });

            $('[name="globalTeachers[]"]').on('ifUnchecked', function(event){

                SelectCounter = $('[name="globalTeachers[]"]:checked').length;
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

            //initialize icheck box
            $(function () {

                $('input.minimal').iCheck({
                    checkboxClass: 'icheckbox_square-blue',
                    radioClass: 'iradio_square-blue',
                    increaseArea: '20%' // optional
                });
            });

            //Initialize Select2 Elements
            $(".select2").select2();
            //set datatable attributes
            var table = $('#teachers').DataTable({
                "paging": false,
                "info": false,
                'searching': false,
                "columnDefs": [
                    {
                        "targets": [6],
                        "visible": false,
                    },

                    {
                        "targets": [ -1 ],
                        "orderable": false,
                    },
                    {
                        "targets": [ 0 ],
                        "orderable": false,
                    },

                    {
                        "targets": [ 1 ],
                        "orderable": false,
                    },
                   { targets: 4, render: $.fn.dataTable.render.ellipsis( 10, true ) },
                ],
                "order": [[ 9, "ASC" ]],
            });

            $('#teachers tbody').on('click', 'tr', function () {

                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                    $(this).addClass('selected');
                    $("div").removeClass("collapsed-box");
                    $(".teacher-detial").removeClass("fa-minus");
                    $(".teacher-detial").addClass("fa-plus");
                    $("#teacher-detial").css("display", "block");



                }
                else {
                    $(this).addClass("selected");
                    table.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                    $("div").removeClass("collapsed-box");
                    $(".teacher-detial").removeClass("fa-plus");
                    $(".teacher-detial").addClass("fa-minus");
                    $("#teacher-detial").css("display", "block");

                    var data = table.row(this).data();

                    //tigger click event for first row on page load
                    if(table.row( this ).index()==0){
                        $('#'+data[6]).trigger('click');
                    }

                    $( "#filter-heading" ).addClass( "collapsed-box" );
                    //alert( 'You clicked on '+data[6]+'\'s row' );
                }


            });
            setTimeout(function () {
                $('#teachers tbody tr:eq(0)').click();
                console.log('time in');
            }, 1);
            $("#page_size").change(function () {
                var page_size = $("#page_size").val();
                $('#pagesize').val(page_size);
                $('#submit_pagesize').trigger('click');
            });

            $('.del-btn').click(function () {
                return confirm("Are you sure to delete this item!");
            });

        });
        function ConfirmDelete() {
            return confirm("Are you sure to delete this item!");
        }
    </script>

@endsection




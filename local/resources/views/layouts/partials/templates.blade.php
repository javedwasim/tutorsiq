@section('page_specific_styles')
        <!-- DataTables -->

<link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables/jquery.dataTables.min.css') }}">
{{--<link rel="stylesheet" href="{{ asset('plugins/datatables/bootstrap.min.css') }}">--}}
@endsection
        <!-- SELECT2 EXAMPLE -->
@section('page_specific_scripts')

        <!-- DataTables -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
<!-- SlimScroll -->
<script src="{{ asset('plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>
<!-- FastClick -->
<script src="{{ asset('plugins/fastclick/fastclick.js') }}"></script>

@endsection


<div class="box box-primary">

    <div class="box-header with-border">
        <i class="pull-right" style="font-size:12px;
    margin-top: 5px;"></i>
        <h1 class="box-title">Template List</h1>
    </div>

    <div class="box-body" style="padding: 0px;">
        <div class="row">
            <div class="col-xs-12">

                <div class="box">

                    <form class="" method="post" action="{{ url('admin/templates') }}" id="filterform">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="pagesize" id="pagesize" value=""/>
                        <button type="submit" id="submit_pagesize" class="btn btn-success pull-right"
                                style="display: none;"><i
                                    class="fa fa-fw fa-search"></i> Search
                        </button>
                    </form>
                    <div class="box-header">
                        <a href="{{ url('admin/template/add') }}" class="btn btn-primary pull-right">
                            <i class="fa fa-plus-circle fa-lg"></i> Add New
                        </a>

                    </div>

                    <!-- /.box-header -->
                    <div class="box-body">


                        <table id="example1" class="table table-bordered table-striped responsive" cellspacing="0" width="100%">
                            <thead>
                            <tr style="background-color: #367fa9; color: #fefefe;">
                                <th>Title</th>
                                <th>Subject</th>
                                <th>Updated at</th>
                                <th>Is System ?</th>
                                <th style="width: 100px;">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($templates as $template): ?>
                            <tr>

                                <td><?php echo $template->title; ?></td>
                                <td><?php echo $template->subject; ?></td>
                                <td><?php echo $template->created_at; ?></td>
                                <td><?php echo ($template->is_active==1 ? 'Yes':'No'); ?></td>

                                <td>

                                    <a class="btn  edit-btn" href="template/update/<?php echo $template->id; ?>"
                                       title="Edit" style="padding: 0 0;">
                                        <span class="label label-success">
                                            <i class="fa fa-fw fa-edit" style="font-size: 10px;"></i>
                                        </span>
                                    </a>

                                    <a class="btn  del-btn" href="template/delete/<?php echo $template->id; ?>"
                                       onclick="return ConfirmDelete();"
                                       title="Delete" style="padding: 0 0;" <?php echo ($template->is_active==1)?  'disabled':''; ?>>
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
                                    <option value="50"<?php if (isset($pagesize) && $pagesize == 50) echo "selected"; ?>>50
                                    </option>
                                    <option value="100"<?php if (isset($pagesize) && $pagesize == 100) echo "selected"; ?>>100
                                    </option>
                                    <option value="150"<?php if (isset($pagesize) && $pagesize == 150) echo "selected"; ?>>150
                                    </option>
                                    <option value="200"<?php if (isset($pagesize) && $pagesize == 200) echo "selected"; ?>>200
                                    </option>
                                </select>
                            </div>

                            <?php echo $templates->render(); ?>
                            <div class="" style = "display:inline-block;">Showing
                                <?php echo isset($offset) ? $offset : ''; ?> to
                                <?php echo isset($perpage_record) ? $perpage_record : ''; ?> of
                                <?php echo $count; ?> entries
                            </div>


                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </div>

    </div>
</div>

<!-- /.box -->
@section('page_specific_inline_scripts')

    @if (session('status'))
        <script>
            jQuery(document).ready(function ($) {
                toastr.success('{{session('status')}}');
            });
        </script>
    @endif

    <script>
        function ConfirmDelete() {
            return confirm("Are you sure to delete this item!");
        }

        jQuery(document).ready(function ($) {

            var teacher_tuitions = $("#example1").DataTable({

                "paging": true,
                "ordering": true,
                "info": true,
                'searching': true,
                "paging": false,
                destroy: true,
                "bLengthChange": false,
                responsive: true,

            });

        });
        $("#page_size").change(function () {

            var page_size = $("#page_size").val();
            $('#pagesize').val(page_size);
            var start_date = $("#start_date").val();
            var formdata = $('#filterform').serialize();
            //alert(formdata);
            $('#submit_pagesize').trigger('click');

        });
    </script>

@endsection
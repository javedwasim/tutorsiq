<div class="box box-primary">
    <a href="#">
        <div class="box-header with-border" data-widget="">
            <!--<i class="fa fa-minus pull-right" style="font-size:12px;  margin-top: 5px;"></i>-->

            <h1 class="box-title">Subject Preference List</h1>
        </div>
    </a>

    <div class="box-body" style="padding: 0px;">

        <div class="row">
            <div class="col-xs-12">
                <div class="box" style="border-top: none;">

                    <div class="box-header">
                        <a href="{{'teacher/preferences'}}" class="btn btn-primary pull-right">
                            <i class="fa fa-plus-circle fa-lg"></i> Add New
                        </a>

                    </div>
                    <div class="box-body">

                        <table id="example1" class="table table-bordered table-striped responsive nowrap"
                               cellspacing="0" width="100%">
                            <thead>
                            <tr style="background-color: #3c8dbc; color: #fefefe;">
                                <th>Grade+Subjects</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($preferences as $e): ?>
                            <tr>
                                <td><?php echo $e->subjects; ?></td>
                                <td>
                                    <a class="btn  del-btn"
                                       href="preference/delete/<?php echo $e->teacher_id."-".$e->cid . "-front"; ?>" title="Delete"
                                       onclick="return confirm('Are you sure you want to delete this item?');">
                                        <span class="label label-danger">
                                            <i class="fa fa-fw fa-trash-o"></i>
                                        </span>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </div>
    </div>
</div>
@section('page_specific_styles')
    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables/jquery.dataTables.min.css') }}">
@endsection
@section('page_specific_scripts')
    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
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

        jQuery(document).ready(function ($) {

            //set datatable attributes
            var table = $('#example1').DataTable({
                "paging": true,
                "info": true,
                'searching': true,
                "columnDefs": [
                    {
                        "targets": -1,
                        "orderable": false,


                    },

                ],
            });
        });

    </script>
@endsection
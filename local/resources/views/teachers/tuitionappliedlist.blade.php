@include('../layouts.partials.modal')
@section('page_specific_styles')
    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.0/css/responsive.dataTables.min.css">
    @endsection
    @section('page_specific_scripts')
            <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>


    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js"></script>
@endsection

@section('page_specific_inline_scripts')
    @if (session('deleted'))
        <script>
            jQuery(document).ready(function ($) {
                toastr.success('{{session('deleted')}}');
            });
        </script>
    @endif
    @if (session('error'))
        <script>
            jQuery(document).ready(function ($) {
                toastr.error('{{session('error')}}');
            });
        </script>
    @endif
    <script>
        jQuery(document).ready(function ($) {

            $("#page_size").change(function () {

                var page_size = $("#page_size").val();
                $('#pagesize').val(page_size);
                $('#submit_pagesize').trigger('click');
            });

            $("#applied_tuition").DataTable({

                "paging": false,
                "ordering": true,
                "info": false,
                'searching': false,
                "pagingType": "full_numbers",
                destroy: true,
                "bLengthChange": false,
                responsive: true,

            });

        });
    </script>
    <script>
        $('.tutor_application_shortview').click(function () {

            var id = this.id;
            $.ajax({

                url: 'applicationshortview/'+id,
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
                    $('#tutor_application_sms_text').val(smsText);
                    $('#tutor_application_tuition_text').val(tuitionText);
                    $('#tutor_application_tuition_short_view').modal();

                },
                cache: false,
                contentType: false,
                processData: false
            });
        });
    </script>
@endsection


<div class="box box-primary">
    <a href="#">
        <div class="box-header with-border" data-widget="">
            <!--<i class="fa fa-minus pull-right" style="font-size:12px; margin-top: 5px;"></i>-->

            <h1 class="box-title">Application List</h1>
        </div>
    </a>

    <div class="box-body" style="padding: 0px;">

        <div class="row">
            <div class="col-xs-12">
                <div class="box" style="border-top: none;">


                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if (session('warning'))
                        <div class="alert alert-warning">
                            {{ session('warning') }}
                        </div>
                        @endif
                        <!-- /.box-header -->
                        <div class="box-body">

                            <table id="applied_tuition" class="display table-striped responsive nowrap" cellspacing="0" width="100%">

                                <thead>
                                    <tr style="background-color: #3c8dbc; color: #fefefe;">
                                        <th>Tuition Date</th>
                                        <th>Code</th>
                                        <th>Grade+Subject</th>
                                        <th>Location</th>
                                        <th>Notes</th>
                                        <th>Status</th>
                                        <th>Description</th>
                                        <th title="Delete Closed Tuitions">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($tuitions as $e): ?>
                                        <tr>
                                            <td><?php echo $e->tuition_date ?></td>
                                            <td><a class="btn tutor_application_shortview" title="View Details" id="<?php echo $e->id ?>">
                                                    <?php echo $e->tuition_code ?></a>
                                            </td>
                                            <td><?php echo $e->class_name.":".$e->s_names; ?></td>
                                            <td><?php echo $e->locations; ?></td>
                                            <td>
                                                <a type="button" class="btn btn-secondary" data-toggle="tooltip"
                                                   data-placement="bottom" title="<?php echo $e->notes; ?>">
                                                    <?php echo   str_limit($e->notes, $limit = 50, $end = '...') ?>
                                                </a>
                                            </td>
                                            <td>
                                                <a type="button" class="btn btn-secondary" data-toggle="tooltip"
                                                   data-placement="bottom" title="<?php echo $e->description; ?>">
                                                    <?php echo   str_limit($e->status, $limit = 50, $end = '...') ?>
                                                </a>
                                            </td>
                                            <td>
                                                <?php echo str_limit($e->description, $limit = 50, $end = '...'); ?>
                                            </td>
                                            <td style="text-align: center">
                                                <?php if(isset($e->description_id) && $e->description_id == 4): ?>
                                                <a class="btn  del-btn" href="applications/delete/<?php echo $e->application_id ?>"
                                                   title="Delete" style="padding: 0 0;">
                                            <span class="label label-danger">
                                                <i class="fa fa-fw fa-trash-o" style="font-size: 10px;"></i>
                                            </span>
                                                </a>
                                                <?php endif; ?>
                                            </td>

                                        </tr>
                                    <?php endforeach; ?>
                               </tbody>
                            </table>
                            <div class="box-footer">
                                <div style="display:inline-block;">
                                    <select class="form-control" id="page_size" name="page_size" style="width: 110px;">
                                        <option value="10"<?php if (isset($pagesize) && $pagesize == 10) echo "selected"; ?>>
                                            10
                                        </option>
                                        <option value="20"<?php if (isset($pagesize) && $pagesize == 20) echo "selected"; ?>>
                                            20
                                        </option>
                                        <option value="30"<?php if (isset($pagesize) && $pagesize == 30) echo "selected"; ?>>
                                            30
                                        </option>
                                        <option value="50"<?php if (isset($pagesize) && $pagesize == 50) echo "selected"; ?>>
                                            50
                                        </option>
                                    </select>
                                </div>
                                <?php echo $tuitions->render(); ?>
                                <div class="" style="display:inline-block;">Showing
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

<form class="" method="post" action="{{ url('applications') }}" id="filterform" style="display: none;">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="pagesize" id="pagesize" value="">

    <button type="submit" id="submit_pagesize" class="btn btn-success pull-right" style="margin-right: 5px;"><i
                class="fa fa-fw fa-search"></i> Search
    </button>
</form>
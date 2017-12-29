@include('../layouts.partials.modal')
<div class="box box-primary">
    <a href="#">
        <div class="box-header with-border" data-widget="">
            <!-- <i class="fa fa-minus pull-right" style="font-size:12px; margin-top: 5px;"></i>-->

            <h1 class="box-title">Tuition List</h1>
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
                        <table id="example1" class="table table-bordered table-striped responsive nowrap"
                               cellspacing="0" width="100%">
                            <thead>
                            <tr style="background-color: #3c8dbc; color: #fefefe;">
                                <th>Assign Date</th>
                                <th>Code</th>
                                <th>Grade+Subjects</th>
                                <th>Location</th>
                                <th>Feedback Rating</th>
                                <th>Reason</th>
                                <th>Trial/Regular</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($tuitions as $e): ?>
                            <tr>
                                <td><?php echo $e->assign_date; ?></td>
                                <td><a class="btn tutor_history_shortview" title="View Details" id="<?php  echo $e->tuition_id?>">
                                        <?php echo $e->tuition_code ?></a>
                                </td>
                                <td><?php echo $e->class_name. ': ' .$e->subject_name; ?></td>
                                <td><?php echo $e->location_name; ?></td>
                                <td><?php echo $e->feedback_rating; ?></td>
                                <td><?php echo $e->reason; ?></td>
                                <td><?php echo $e->is_trial == 1 ? 'Trial' : 'Regular' ?></td>

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
    <link rel="stylesheet" href="{{ asset('plugins/datepicker/datepicker3.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">

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
    <script>
        $('.tutor_history_shortview').click(function () {

            var id = this.id;
            $.ajax({

                url: 'historyshortview/'+id,
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
                    $('#tutor_application_history_tuition_text').val(tuitionText);
                    $('#tutor_application_history_tuition_short_view').modal();

                },
                cache: false,
                contentType: false,
                processData: false
            });
        });
    </script>

@endsection

@section('page_specific_inline_scripts')
    <script>
        jQuery(document).ready(function ($) {
            //set datatable attributes
            var table = $('#example1').DataTable();
        });
        function ConfirmDelete() {
            return confirm("Are you sure to delete this item!");
        }
    </script>
@endsection
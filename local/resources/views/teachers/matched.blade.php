<div class="">

    <?php if(!empty($tuitions)): ?>
        <?php if(isset($flag)): ?>
            <div class="alert alert-info">Tuition Search By Your Preferred Locations, Subjects, Fee, Age, Experience, Gender, Grade Categories and Institutions</div>
        <?php endif; ?>
        <div class="row">
            <?php $count=0;  foreach($tuitions as $t): $count++; ?>
                <div class="col-md-4">
                    <div class="box box-warning">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $t->tuition_code; ?></h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-footer no-padding">
                            <ul class="nav nav-stacked">

                                <li style="padding: 0px 0 30px 0;"><a href="#">&nbsp;<span
                                                class="pull-left  bg-gray"><?php echo $t->subjects; ?></span></a>
                                </li>
                                <li><a href="#">&nbsp;<span
                                                class="pull-left badge  bg-yellow"><?php echo $t->locations; ?></span></a>
                                </li>
                                <li><a href="#">&nbsp;<span
                                                class="pull-left text"><?php echo $t->special_notes; ?></span></a>
                                </li>

                                <li>
                                    <form class="form-horizontal viewdetail" method="post" action="{{ url('/viewdetail') }}"
                                          enctype="multipart/form-data">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="tuition_id" value="<?php echo $t->id; ?>">
                                        <input type="hidden" name="teacher_id" value="<?php echo $teacher_id; ?>">
                                        <input type="hidden" name="class_name" value="<?php echo $t->class_name; ?>">
                                        <input type="hidden" name="subject_name" value="<?php echo $t->subjects; ?>">
                                        <input type="hidden" name="location" value="<?php echo $t->locations; ?>">
                                        <input type="hidden" name="special_notes" value="<?php echo $t->special_notes; ?>">
                                        <span class="pull-left text">
                                            <button type="submit" class="btn bg-navy margin">View Detail</button>
                                        </span>
                                    </form>

                                </li>

                            </ul>

                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <?php if($count%3==0) echo '</div><div class="row">'; ?>
            <?php endforeach; ?>

        </div>
        <div class="box-footer">
            <?php echo $tuitions->render(); ?>
            <div class="" style="display:inline-block;">Showing
                <?php echo isset($offset) ? $offset : ''; ?> to
                <?php echo isset($perpage_record) ? $perpage_record : ''; ?> of
                <?php echo $totlalRecords; ?> entries
            </div>
        </div>
        <?php else: ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-warning">
                    <div class="box-header with-border" style="text-align: center;">
                        <h3 class="box-title">No Record Found.</h3>
                    </div>
                </div>
            </div>
        </div>

        <?php endif; ?>

</div>
    @include('layouts.partials.modal')

    @section('page_specific_styles')
        <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
    @endsection


    @section('page_specific_scripts')
        <script src="{{ asset('plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>
        <script src="{{ asset('plugins/fastclick/fastclick.js') }}"></script>
        <script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>
    @endsection
    @section('page_specific_inline_scripts')
        @if (session('message'))
            <script>
                jQuery(document).ready(function ($) {
                    toastr.success('Applied Successfully!');
                });
            </script>
        @endif
        <script>
            jQuery(document).ready(function ($) {
                //Initialize Select2 Elements
                $(".select2").select2();

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
                            $(".form-group").remove();
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

                            $('#applications').append('<div class="form-group">' +
                                    '<label  class="col-sm-3 control-label text-align-left">Class</label>' +
                                    '<div class="col-sm-9">' +
                                    '<label  class="control-label">' + class_name + '</label>' +
                                    '</div>' +
                                    '</div>' +
                                    '<div class="form-group">' +
                                    '<label  class="col-sm-3 control-label text-align-left">Subject</label>' +
                                    '<div class="col-sm-9">' +
                                    '<label  class="control-label text-align-left">' + subject_name + '</label>' +
                                    '</div>' +
                                    '</div>' +
                                    '<div class="form-group">' +
                                    '<label  class="col-sm-3 control-label text-align-left">Location</label>' +
                                    '<div class="col-sm-9">' +
                                    '<label class="control-label">' + location + '</label>' +
                                    '</div>' +
                                    '</div>' +
                                    '<div class="form-group">' +
                                    '<label  class="col-sm-3 control-label text-align-left">Special Notes</label>' +
                                    '<div class="col-sm-9">' +
                                    '<label class="control-label" style="text-align: left;">' + special_notes + '</label>' +
                                    '</div>' +
                                    '</div>' +
                                    '<div class="form-group">' +
                                    '<label  class="col-sm-3 control-label text-align-left">Application Notes</label>' +
                                    '<div class="col-sm-9">' +
                                    '<textarea class="form-control" name="application_note" rows="3" placeholder="Enter Application Note...">' + notes + '</textarea>' +
                                    '<input type="hidden" name="tuition_id" value="' + tuition_id + '" > <input type="hidden" name="teacher_id" value = "' + teacher_id + '"> ' +
                                    '<input type="hidden" name = "my_tuitions" value = "mytuitions" >' +
                                    '<input type="hidden" name="tuitionbycategory" value="{{isset($tuitionByCategory) ? $tuitionByCategory : ''}}">' +
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
        </script>
@endsection
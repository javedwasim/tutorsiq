@section('page_specific_styles')

    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
    @endsection
    @section('page_specific_scripts')
    <!-- Custom -->
    <script src="{{ asset('js/tutors.js') }}"></script>
            <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <!-- FastClick -->
    <script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('plugins/jQuerymask/jquery.mask.min.js') }}"></script>
    <script src="{{ asset('/plugins/iCheck/icheck.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}" type="text/javascript"></script>
    @endsection

<!-- SELECT2 EXAMPLE -->
<div class="box box-primary collapsed-box">
    <a href="#">
        <div class="box-header with-border" data-widget="collapse"><i class="fa fa-plus pull-right" style="font-size:12px;
        margin-top: 5px;"></i>

            <h1 class="box-title">Search Filters</h1>
        </div>
    </a>

    <!-- /.box-header -->
    <div class="box-body">
        <!-- form start -->
        <form class="" method="post" action="{{ url('admin/global/teachers/matched') }}" id="filterform">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="pagesize" id="pagesize" value="">

            <!-- /.row -->
            <div class="row" id="first-row">

                <!-- /.col -->
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="minimal" value="1"
                                       <?php if(isset($filters['gender_p']) && $filters['gender_p'] == 0 ) echo "unchecked"; else echo "checked"; ?> name="gender" id="gender" />
                                <input type="hidden" value="<?php if(isset($filters['gender_p']) && $filters['gender_p'] == 0 ) echo "0"; else echo "1"; ?>" name="gender_p" id="gender_p" />

                            </label>
                            <label style="margin-left: 10px;">Gender</label>
                        </div>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->

                <!-- /.col -->
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="minimal checkbox" value="1"
                                       <?php if(isset($filters['subjectPreference_p']) && $filters['subjectPreference_p']== 0 ) echo "unchecked"; else echo "checked"; ?> name="subjectPreference" id="subjectPreference">
                                <input type="hidden" value="<?php if(isset($filters['subjectPreference_p']) && $filters['subjectPreference_p'] == 0 ) echo "0"; else echo "1"; ?>" name="subjectPreference_p" id="subjectPreference_p" />
                            </label>
                            <label style="margin-left: 10px;">Subject Preference</label>
                        </div>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->

                <!-- /.col -->
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="minimal checkbox" value="1"
                                       <?php if(isset($filters['location_p']) && $filters['location_p']== 0 ) echo "unchecked"; else echo "checked"; ?> name="location" id="location">
                                <input type="hidden" value="<?php if(isset($filters['location_p']) && $filters['location_p']== 0 ) echo "0"; else echo "1"; ?> " name="location_p" id="location_p" />
                            </label>
                            <label style="margin-left: 10px;">Location</label>
                        </div>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->

                <!-- /.col -->
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="minimal checkbox" value="1"
                                       <?php if(isset($filters['teacher_band_id_p']) && $filters['teacher_band_id_p']== 0 ) echo "unchecked"; else echo "checked"; ?> name="teacher_band_id" id="teacher_band_id">
                                <input type="hidden" value="<?php if(isset($filters['teacher_band_id_p']) && $filters['teacher_band_id_p'] == 0 ) echo "0"; else echo "1"; ?>" name="teacher_band_id_p" id="teacher_band_id_p" />
                            </label>
                            <label style="margin-left: 10px;">Band</label>
                        </div>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->


            </div>
            <!-- /.row -->

            <!-- /.row -->
            <div class="row" id="second-row">

                <!-- /.col -->
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="minimal checkbox" value="1"
                                       <?php if(isset($filters['experience_p']) && $filters['experience_p'] == 0 ) echo "unchecked"; else echo "checked"; ?> name="experience" id="experience" />
                                <input type="hidden" value="<?php if(isset($filters['experience_p']) && $filters['experience_p'] == 0 ) echo "0"; else echo "1"; ?>" name="experience_p" id="experience_p" />
                            </label>
                            <label style="margin-left: 10px;">Experience</label>
                        </div>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->

                <!-- /.col -->
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="minimal checkbox" value="1"
                                       <?php if(isset($filters['fee_p']) && $filters['fee_p'] == 0 ) echo "unchecked"; else echo "checked"; ?> name="fee" id="fee" />
                                <input type="hidden" value="<?php if(isset($filters['fee_p']) && $filters['fee_p'] == 0 ) echo "0"; else echo "1"; ?>" name="fee_p" id="fee_p" />
                            </label>
                            <label style="margin-left: 10px;">Fee Range</label>
                        </div>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->

                <!-- /.col -->
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="minimal checkbox" value="1"
                                       <?php if(isset($filters['suitable_timings_p']) && $filters['suitable_timings_p'] == 0 ) echo "unchecked"; else echo "checked"; ?> name="suitable_timings" id="suitable_timings" />
                                <input type="hidden" value="<?php if(isset($filters['suitable_timings_p']) && $filters['suitable_timings_p'] == 0 ) echo "0"; else echo "1"; ?>" name="suitable_timings_p" id="suitable_timings_p" />
                            </label>
                            <label style="margin-left: 10px;">Suitable Timing</label>
                        </div>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->

                <!-- /.col -->
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="minimal checkbox" value="1"
                                       <?php if(isset($filters['age_p']) && $filters['age_p'] == 0 ) echo "unchecked"; else echo "checked"; ?> name="age" id="age" />
                                <input type="hidden" value="<?php if(isset($filters['age_p']) && $filters['age_p'] == 0 ) echo "0"; else echo "1"; ?>" name="age_p" id="age_p" />
                            </label>
                            <label style="margin-left: 10px;">Age</label>
                        </div>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->

                <!-- /.col -->
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="minimal checkbox" value="1"
                                       <?php if(isset($filters['tuition_category_p']) && $filters['tuition_category_p'] == 0 ) echo "unchecked"; else echo "checked"; ?> name="tuition_category" id="tuition_category" />
                                <input type="hidden" value="<?php if(isset($filters['tuition_category_p']) && $filters['tuition_category_p'] == 0 ) echo "0"; else echo $tuition_category_p; ?>" name="tuition_category_p" id="tuition_category_p" />
                            </label>
                            <label style="margin-left: 10px;">Category</label>
                        </div>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->
                <!-- /.col -->
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="minimal checkbox" value="1"
                                       <?php if(isset($filters['tuition_label_p']) && $filters['tuition_label_p'] == 0 ) echo "unchecked"; else echo "checked"; ?> name="tuition_label" id="tuition_label" />
                                <input type="hidden" value="<?php if(isset($filters['tuition_label_p']) && $filters['tuition_label_p'] == 0 ) echo "0"; else echo "1"; ?>" name="tuition_label_p" id="tuition_label_p" />
                            </label>
                            <label style="margin-left: 10px;">Labels</label>
                        </div>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->
                <!-- /.col -->
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="minimal checkbox" value="1"
                                       <?php if(isset($filters['institution_p']) && $filters['institution_p'] == 0 ) echo "unchecked"; else echo "checked"; ?> name="institution" id="institution" />
                                <input type="hidden" value="<?php if(isset($filters['institution_p']) && $filters['institution_p'] == 0 ) echo "0"; else echo "1"; ?>" name="institution_p" id="institution_p" />
                            </label>
                            <label style="margin-left: 10px;">Institutions</label>
                        </div>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->

            </div>
            <!-- /.row -->


            <div class="row" id="third-row">
            </div>
            <!-- /.row -->
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" name="reset" value="reset" id="reset" class="btn btn-warning pull-right"><i
                            class="fa fa-fw fa-undo"></i> Reset
                </button>
                <button type="submit" id="submit_pagesize" class="btn btn-success pull-right" style="margin-right: 5px;"><i
                            class="fa fa-fw fa-search"></i> Search
                </button>

            </div>
            <!-- /.box-footer -->
        </form>
        <!-- end form -->
    </div>
</div>



            <!-- /.box -->
@section('page_specific_inline_scripts')

    <script>
        //load mathced teachers
        jQuery(document).ready(function ($) {

            // For oncheck callback gender
            $('#gender').on('ifChecked', function () { $('#gender_p').val(1); })
                // For onUncheck callback gender
            $('#gender').on('ifUnchecked', function () {  $('#gender_p').val(0); })

            // For oncheck callback subjectPreference
            $('#subjectPreference').on('ifChecked', function () { $('#subjectPreference_p').val(1); })
            // For onUncheck callback subjectPreference
            $('#subjectPreference').on('ifUnchecked', function () {  $('#subjectPreference_p').val(0); })

            // For oncheck callback location
            $('#location').on('ifChecked', function () { $('#location_p').val(1); })
            // For onUncheck callback location
            $('#location').on('ifUnchecked', function () {  $('#location_p').val(0); })

            // For oncheck callback teacher_band_id
            $('#teacher_band_id').on('ifChecked', function () { $('#teacher_band_id_p').val(1); })
            // For onUncheck callback teacher_band_id
            $('#teacher_band_id').on('ifUnchecked', function () {  $('#teacher_band_id_p').val(0); })

            // For oncheck callback experience
            $('#experience').on('ifChecked', function () { $('#experience_p').val(1); })
            // For onUncheck callback experience
            $('#experience').on('ifUnchecked', function () {  $('#experience_p').val(0); })

            // For oncheck callback fee
            $('#fee').on('ifChecked', function () { $('#fee_p').val(1); })
            // For onUncheck callback fee
            $('#fee').on('ifUnchecked', function () {  $('#fee_p').val(0); })

            // For oncheck callback suitable_timings
            $('#suitable_timings').on('ifChecked', function () { $('#suitable_timings_p').val(1); })
            // For onUncheck callback suitable_timings
            $('#suitable_timings').on('ifUnchecked', function () {  $('#suitable_timings_p').val(0); })

            // For oncheck callback age
            $('#age').on('ifChecked', function () { $('#age_p').val(1); })
            // For onUncheck callback age
            $('#age').on('ifUnchecked', function () {  $('#age_p').val(0); })

            // For oncheck callback tuition_category
            $('#tuition_category').on('ifChecked', function () { $('#tuition_category_p').val(1); })
            // For onUncheck callback tuition_category
            $('#tuition_category').on('ifUnchecked', function () {  $('#tuition_category_p').val(0); })

            // For oncheck callback tuition_label
            $('#tuition_label').on('ifChecked', function () { $('#tuition_label_p').val(1); })
            // For onUncheck callback tuition_label
            $('#tuition_label').on('ifUnchecked', function () {  $('#tuition_label_p').val(0); })

            // For oncheck callback institution
            $('#institution').on('ifChecked', function () { $('#institution_p').val(1); })
            // For onUncheck callback institution
            $('#institution').on('ifUnchecked', function () {  $('#institution_p').val(0); })




            $("body").addClass('skin-blue sidebar-mini sidebar-collapse');
           //set datatable attributes
            var table = $('#example1').DataTable({
                "paging": false,
                "ordering": true,
                "info": false,
                'searching': false,
                "order": [[ 2, "asc" ]]

            });


            $("#page_size").change(function () {

                var page_size = $("#page_size").val();
                $('#pagesize').val(page_size);
                $('#submit_pagesize').trigger('click');

            });

            //Assigned Tuitions
            $('#assign_tuition').on('submit', function (e) {

                e.preventDefault();

                var formData = new FormData($(this)[0]);
                $.ajax({

                    url: '{{url("admin/tuitions/assigned")}}',
                    type: "POST",
                    data: formData,
                    async: false,
                    beforeSend: function () {
                        $("#wait").modal();
                    },
                    success: function (data) {
                        //alert(data);
                        $('#wait').modal('hide');
                        var success = data['success'];
                        if (success == 'save') {

                            $('#assigned').modal();
                        }


                    },
                    cache: false,
                    contentType: false,
                    processData: false

                });

            });


        });

        function AssignTuition(teacher_id, tuition_id) {
            $(".tuitions").remove();
            $(".checkbox").remove();
            //alert(tuition_id);
            $.ajax({

                url: '{{url("admin/tuitions/assign")}}',
                type: "post",
                data: {'id': tuition_id, 'teacher_id': teacher_id, '_token': $('input[name=_token]').val()},
                async: false,
                beforeSend: function () {
                    $("#wait").modal();
                },

                success: function (response) {
                    $('#wait').modal('hide');

                    var test = JSON.stringify(response);
                    var data = JSON.parse(test);
                    //console.log(data['tuitions']);
                    if (data['tuitions'].length > 0) {
                        for (var j = 0; j < data['tuitions'].length; j++) {

                            var firstname = data['tuitions'][j]['firstname'];
                            var lastname = data['tuitions'][j]['lastname'];
                            var city = data['tuitions'][j]['city'];
                            var mobile1 = data['tuitions'][j]['mobile1'];
                            var email = data['tuitions'][j]['email'];
                            var teacher_id = data['tuitions'][j]['teacher_id'];
                            var tuition_id = data['tuitions'][j]['tuition_id_p'];
                            var class_name = data['tuitions'][j]['class_name'];
                            var subject_name = data['tuitions'][j]['subject_name'];
                            var td_id = data['tuitions'][j]['td_id'];


                            $('#subjects').append('<div class="checkbox"><label><input type="hidden" name="teacher_id" id="teacher_id" value="' + teacher_id + '">' +
                                    '<input type="checkbox" name="subjects[]" id="' + subject_name + '" value="' + td_id + '" checked>' + subject_name + '</label>' +
                                    '<input type="hidden" name="tuitionid" id="tuitionid" value="' + tuition_id + '" />' +
                                    '<input type="hidden" name="td_id" id="td_id" value="' + td_id + '" /></div>');


                        }
                    } else {
                        $('#subjects').append('<div class="checkbox"><label><input type="checkbox" name="subjects[]" value="" disabled>Not Found</label></div>');
                    }
                    //$('#tuition').modal('hide');
                    $('#assign_teacher').modal('show');


                }
            });

        }


    </script>

    <script>
        $(document).on('show.bs.modal', '.modal', function () {
            var zIndex = 1040 + (10 * $('.modal:visible').length);
            $(this).css('z-index', zIndex);
            setTimeout(function () {
                $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
            }, 0);
        });
        $(function () {

            $(".select2").select2();

            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });


        $('#reset').click(function () {
            $(this).closest('form').find("input[type=text], select").val("");
        });


    </script>


@endsection
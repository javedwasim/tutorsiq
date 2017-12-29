<div class="teacher-search">
    <div class="row">

        <div class="col-lg-12">

            <div class="box box-default">

                <div class="box-header with-border">
                    <h3 class="box-title">Tuition Detail</h3>
                </div>

                <div class="box-body">

                    <form action="{{ url('call/save') }}" method="post" id="tutorRequestForm">

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="student_id" value="<?php echo isset($student_id) ? $student_id : ''; ?>">
                        <input type="hidden" name="tuition_status_id" value=8">
                        <input type="hidden" name="tuition_code" value="<?php echo isset($latest_code) ? $latest_code : $tuition->tuition_code; ?>">

                        <!-- /.row -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tuition Date<span style="color: red">*</span></label>
                                    <input type="text" class="form-control" id="datepicker" required
                                           name="tuition_date" placeholder="Tuition Date" maxlength="20"/>

                                </div>
                                <!-- /.form-group -->
                                <div class="form-group">
                                    <label>Contact No<span style="color: red">*</span></label>
                                    <input type="text" class="form-control" id="contact_no" required
                                           name="contact_no" maxlength="20" placeholder="Contact No"/>
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <!-- /.col -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Contact Person<span style="color: red">*</span></label>
                                    <input type="text" class="form-control" id="contact_person" required
                                           name="contact_person" placeholder="Contact Person" maxlength="100"/>
                                </div>
                                <!-- /.form-group -->
                                <div class="form-group">
                                    <label>Contact No 2</label>
                                    <input type="text" class="form-control" id="contact_no2"
                                           name="contact_no2" maxlength="20"
                                           placeholder="Contact No 2"/>
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->

                        <!-- /.row -->
                        <div class="row">
                            <!-- /.col -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>No of students<span style="color: red">*</span></label>
                                    <select class="form-control select2" id="no_of_students"
                                            name="no_of_students"
                                            data-placeholder="Select no of students" required>
                                        <option value=""></option>
                                        <option value="1"
                                        <?php if (isset($tuition->no_of_students) && ($tuition->no_of_students == 1)) echo "selected"; ?>>
                                            One
                                        </option>
                                        <option value="2"
                                        <?php if (isset($tuition->no_of_students) && ($tuition->no_of_students == 2)) echo "selected"; ?>>
                                            Two
                                        </option>
                                        <option value="3"
                                        <?php if (isset($tuition->no_of_students) && ($tuition->no_of_students == 3)) echo "selected"; ?>>
                                            Three
                                        </option>
                                        <option value="4"
                                        <?php if (isset($tuition->no_of_students) && ($tuition->no_of_students == 4)) echo "selected"; ?>>
                                            Four
                                        </option>
                                        <option value="5"
                                        <?php if (isset($tuition->no_of_students) && ($tuition->no_of_students == 5)) echo "selected"; ?>>
                                            Five
                                        </option>
                                        <option value="6"
                                        <?php if (isset($tuition->no_of_students) && ($tuition->no_of_students == 6)) echo "selected"; ?>>
                                            Six
                                        </option>
                                        <option value="7"
                                        <?php if (isset($tuition->no_of_students) && ($tuition->no_of_students == 7)) echo "selected"; ?>>
                                            Seven
                                        </option>
                                        <option value="8"
                                        <?php if (isset($tuition->no_of_students) && ($tuition->no_of_students == 8)) echo "selected"; ?>>
                                            Eight
                                        </option>
                                        <option value="9"
                                        <?php if (isset($tuition->no_of_students) && ($tuition->no_of_students == 9)) echo "selected"; ?>>
                                            Nine
                                        </option>
                                        <option value="10"
                                        <?php if (isset($tuition->no_of_students) && ($tuition->no_of_students == 10)) echo "selected"; ?>>
                                            Ten
                                        </option>
                                    </select>

                                </div>
                                <!-- /.form-group -->
                                <div class="form-group">
                                    <label>Institute Name<span style="color: red">*</span></label>
                                    <select class="form-control select2 institute" required
                                            id="institutes" name="institutes[]"
                                            data-placeholder="Select Institutes">
                                        <option value=""></option>
                                        <?php foreach($instututes as $instutute): ?>
                                        <option value="<?php echo $instutute->id; ?>">
                                            <?php echo $instutute->name; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <!-- /.col -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Grade+Subjects<span style="color: red">*</span></label>
                                    <select name="csm[]" id="csm" class="form-control select2 cms_change"
                                            multiple="multiple" required
                                            data-placeholder="Select Grade">
                                        <option value="">Select Grade</option>
                                        <?php foreach($classes as $class): ?>
                                        <option value="<?php echo $class->id; ?>"><?php echo $class->name; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="hidden" name="class_change" id="class_change" value=""/>
                                </div>
                                <!-- /.form-group -->
                                <div class="form-group">
                                    <label>Teacher Gender<span style="color: red">*</span></label>
                                    <select name="teacher_gender" id="teacher_gender" required
                                            data-placeholder="Select Gender" class="form-control select2">
                                        <option value=""></option>
                                        <?php foreach($gender as $gender): ?>
                                        <option value="<?php echo $gender->id; ?>"><?php echo $gender->name; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->

                        <!-- /.row -->
                        <div class="row">
                            <!-- /.col -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Stuitable Timings<span style="color: red">*</span></label>
                                    <select class="form-control select2" id="suitable_timings"
                                            name="suitable_timings" required
                                            data-placeholder="Select Suitable Timings">
                                        <option value=""></option>
                                        <option value="morning">Morning</option>
                                        <option value="evening">Evening</option>
                                        <option value="anytime">AnyTime</option>
                                    </select>
                                </div>
                                <!-- /.form-group -->
                                <div class="form-group">
                                    <label>Age Requirements</label>
                                    <select class="form-control select2" id="teacher_age" name="teacher_age"
                                            data-placeholder="Select Age">
                                        <option value=""></option>
                                        <option value="15">15 Years & Above</option>
                                        <option value="20">20 Years & Above</option>
                                        <option value="25">25 Years & Above</option>
                                        <option value="30">30 Years & Above</option>
                                        <option value="35">35 Years & Above</option>
                                        <option value="40">40 Years & Above</option>
                                        <option value="45">45 Years & Above</option>
                                        <option value="50">50 Years & Above</option>

                                    </select>
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <!-- /.col -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Teaching Duration</label>
                                    <select name="teaching_duration" id="teaching_duration"
                                            class="form-control  select2"
                                            data-placeholder="Select Teaching Duration">
                                        <option value=""></option>
                                        <option value="15">15 Mins</option>
                                        <option value="30">30 Mins</option>
                                        <option value="45">45 Mins</option>
                                        <option value="60">1 Hour</option>
                                        <option value="75">1 Hour 15 Mins</option>
                                        <option value="90">1 Hour 30 Mins</option>
                                        <option value="105">1 Hour 45 Mins</option>
                                        <option value="120">2 Hours</option>
                                        <option value="135">2 Hours 15 Mins</option>
                                        <option value="150">2 Hours 30 Mins</option>
                                        <option value="165">2 Hours 45 Mins</option>
                                        <option value="180">3 Hours</option>
                                        <option value="195">3 Hours 15 Mins</option>
                                        <option value="210">3 Hours 30 Mins</option>
                                        <option value="225">3 Hours 45 Mins</option>
                                        <option value="240">4 Hours</option>
                                        <option value="255">4 Hours 15 Mins</option>
                                        <option value="270">4 Hours 30 Mins</option>
                                        <option value="285">4 Hours 45 Mins</option>
                                        <option value="300">5 Hours</option>
                                        <option value="315">5 Hours 15 Mins</option>
                                        <option value="330">5 Hours 30 Mins</option>
                                        <option value="345">5 Hours 45 Mins</option>
                                        <option value="360">6 Hours</option>
                                    </select>
                                    <input type="hidden" name="duration_changed" id="duration_changed"
                                           value=""/>
                                </div>
                                <!-- /.form-group -->
                                <div class="form-group">
                                    <label>Teacher Experience</label>
                                    <select class="form-control select2" id="experience" name="experience"
                                            data-placeholder="Select Experience">
                                        <option value=""></option>
                                        <option value="lessthen1">less then1</option>
                                        <option value="1">1+</option>
                                        <option value="5">5+</option>
                                        <option value="10">10+</option>
                                        <option value="15">15+</option>
                                    </select>

                                </div>
                                <!-- /.form-group -->
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->

                        <!-- /.row -->
                        <div class="row">
                            <!-- /.col -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tuition MIN Fee(expected)</label>
                                    <select name="tuition_fee" id="tuition_fee" class="form-control select2"
                                            data-placeholder="Select Tuition Fee">
                                        <option value=""></option>
                                        <?php for($i = 1; $i <= 100; $i++){ ?>
                                        <option value="<?php echo $i; ?>"><?php echo $i . "K"; ?></option>
                                        <?php } ?>
                                    </select>
                                    <input type="hidden" name="fee_change" id="fee_change" value=""/>
                                </div>
                                <!-- /.form-group -->
                                <div class="form-group">
                                    <label>Location<span style="color: red">*</span></label>
                                    <select name="location_id" id="location_id" class="form-control select2"
                                            data-placeholder="Select Location" required>
                                        <option value="">Select Location</option>
                                        <?php foreach($locations as $location): ?>
                                        <option value="<?php echo $location->id; ?>">
                                            <?php echo $location->locations; ?>
                                        </option>
                                        <?php endforeach; ?>

                                    </select>
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <!-- /.col -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tuition Max Fee(expected)</label>
                                    <select name="tuition_max_fee" id="tuition_max_fee"
                                            class="form-control select2"
                                            data-placeholder="Select Tuition Fee">
                                        <option value=""></option>
                                        <?php for($i = 1; $i <= 100; $i++){ ?>
                                        <option value="<?php echo $i; ?>"
                                        <?php if (isset($tuition->tuition_max_fee) && $tuition->tuition_max_fee == $i) echo "selected"; ?>><?php echo $i . "K"; ?></option>
                                        <?php } ?>
                                    </select>
                                    <input type="hidden" name="fee_change" id="fee_change" value=""/>
                                </div>
                                <!-- /.form-group -->
                                <div class="form-group">
                                    <label>Tuition Category<span style="color: red">*</span></label>
                                    <select name="tuition_catefory_id" id="tuition_catefory_id"
                                            class="form-control select2"
                                            data-placeholder="Select Categories" required>
                                        <option value=""></option>
                                        <?php foreach($tuition_category as $category): ?>
                                        <option value="<?php echo $category->id ?>">
                                            <?php echo $category->name ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>


                                </div>
                                <!-- /.form-group -->
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->

                        <!-- /.row -->
                        <div class="row">
                            <!-- /.col -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Special Requirement About Teacher</label>
                                    <textarea class="form-control" rows="10" cols="50" id="note" name="note"></textarea>
                                </div>

                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->

                        <!-- /.row -->
                        <div class="row">
                            <!-- /.col -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Complete Address</label>
                                    <textarea class="form-control" rows="3" id="address" name="address" ></textarea>
                                </div>

                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->

                        <div class="modal-footer">
                            <button type="submit" class="btn btn11 outline" id="apply_tuition_btn">Apply For Tuition</button>
                        </div>

                    </form>

                </div>


            </div>

        </div>
    </div>
</div>



@section('page_specific_styles')
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datepicker/datepicker3.css') }}">
@endsection
@section('page_specific_scripts')
    <script src="{{ asset('plugins/jQuery/jQuery-2.1.4.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('plugins/jQuerymask/jquery.mask.min.js') }}"></script>
@endsection
@section('page_specific_inline_scripts')

    <script>

        $(function () {

            $(".select2").select2();

            $('.select2').change(function () {
                $('#label_change').val('change');
            });

            $('.institute').change(function () {
                $('#institute_change').val('change');
            });

            $('.cms_change').change(function () {
                $('#class_change').val('change');
            });

        });

        jQuery(document).ready(function () {

            $("#tuition_start_date").datepicker();

            $(document).on("click", ":submit", function (e) {
                $("#submitbtnValue").val($(this).val());
            });

            $('#contact_no').mask('00000000000');
            $('#contact_no2').mask('00000000000');

        });


        $(".target").change(function () {

            var classid = $(this).val();

            $.ajax({
                url: '{{url("call/save")}}',
                type: "POST",
                data: {'classid': classid, '_token': $('input[name=_token]').val()},

                beforeSend: function () {
                    $("#wait").modal();
                },
                success: function (response) {
                    $(".classsubjects").remove();
                    $("#class-subj").css("display", "block");

                    $('#wait').modal('hide');

                    var test = JSON.stringify(response);
                    var data = JSON.parse(test);
                    console.log(data['result']);

                    if (data['result'].length > 0) {

                        for (var j = 0; j < data['result'].length; j++) {

                            var mapping_id = data['result'][j]['mid'];
                            var class_id = data['result'][j]['cid'];
                            var subject_id = data['result'][j]['sid'];
                            var subject_name = data['result'][j]['sname'];
                            var class_name = data['result'][j]['cname'];

                            $(".class-subjects").append($('<div class="col-sm-3 classsubjects"><label>' +
                                '<input type="checkbox" name="subjects[]" id="subject" value="' + mapping_id + '"> &nbsp;' + subject_name + ' ' +
                                '</label></div>'));

                        }

                    }

                    $('input').iCheck({
                        checkboxClass: 'icheckbox_square-blue',
                        radioClass: 'iradio_square-blue',
                        increaseArea: '20%' // optional
                    });

                }
            });

        });

        //Date picker
        $('#datepicker').datepicker({
            autoclose: true
        });

        $('#datepicker1').datepicker({
            autoclose: true
        });


    </script>

    <script>
        $(".note").change(function () {
            var conceptName = $('#special_notes').find(":selected").val();
            //alert(conceptName);
            $("textarea#note").val(conceptName);
        });

        $('#tutorRequestForm').on('submit', function (e) {

            e.preventDefault();
            var formData = new FormData($(this)[0]);

            $.ajax({

                url: 'save',
                type: "post",
                data: formData,
                beforeSend: function () {
                    $("#wait").modal();
                },
                success: function (data) {
                    $('#wait').modal('hide');
                    window.location.href = "{{route('contactus')}}"
                },
                cache: false,
                contentType: false,
                processData: false

            });

        });
    </script>

    <?php if(!isset($tuition->tuition_start_date)): ?>
    <script>
        $("#tuition_start_date").datepicker().datepicker("setDate", new Date());
    </script>
    <?php endif; ?>
@endsection


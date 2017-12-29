@extends('layouts.app')

@section('htmlheader_title')
    @role('teacher')
    Teahcer Registeration
    @endrole

    <?php if($status=='update'): ?>
    {{ trans('Admin | Tuition | Update') }}
    <?php else: ?>
    {{ trans('Admin | Tuition | Add') }}
    <?php endif; ?>

@endsection

@section('contentheader_title')
    <?php if($status=='update'): ?>
    {{ trans('Update Tuition') }}
    <?php else: ?>
    {{ trans('Add New Tuition') }}
    <?php endif; ?>
@endsection

@section('main-content')

    <div class="spark-screen">
        <div class="row">
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-info">
                    <!-- form start -->
                    <form class="form-horizontal" id="tuition" method="post"  action="{{ url('admin/tuition/detail') }}"
                          enctype="multipart/form-data">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <input type="hidden" name="status" id="status" value="<?php echo isset($status)? $status : '' ?>">
                        <input type="hidden" name="id" id="id" value="<?php echo isset($id)? $id : '' ?>">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="submitbtnValue" id="submitbtnValue" value="">


                        <div id="accordion" role="tablist" aria-multiselectable="true">
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingOne">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#personalinfo"
                                           aria-expanded="true" aria-controls="personalinfo">
                                            Tuitions
                                        </a>
                                    </h4>
                                </div>
                                <div id="personalinfo" class="panel-collapse collapse in" role="tabpanel"
                                     aria-labelledby="headingOne">
                                    <div class="box-body">

                                        <div class="form-group">
                                            <label for="code" class="col-sm-2 control-label">Code</label>

                                            <div class="col-sm-10">
                                                <input type="text" class="form-control " id="tuition_code"
                                                       name="tuition_code"
                                                       placeholder="Tuition Code" maxlength="100"
                                                       value="<?php echo isset($latest_code) ? $latest_code : $tuition->tuition_code; ?>" readonly>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="tuition_catefory_id" class="col-sm-2 control-label">Tuition Category<span
                                                        style="color: red">*</span></label>

                                            <div class="col-sm-10">
                                                <select name="tuition_catefory_id" id="tuition_catefory_id" class="form-control" required>
                                                    <?php foreach($tuition_category as $category): ?>
                                                        <option value="<?php echo $category->id ?>"<?php if(isset($tuition->tuition_catefory_id) && $tuition->tuition_catefory_id==$category->id) echo  'selected'; ?>>
                                                            <?php echo $category->name ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="datepicker" class="col-sm-2 control-label">Tuition Date<span
                                                        style="color: red">*</span></label>

                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="datepicker"
                                                       name="tuition_date" value="<?php echo  isset($tuition->tuition_date) ? $tuition->tuition_date:''; ?>"
                                                       placeholder="startdate"  maxlength="100" required>
                                            </div>
                                        </div>



                                        <div class="form-group">
                                            <label for="no_of_students" class="col-sm-2 control-label">No of
                                                students<span
                                                        style="color: red">*</span></label>

                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="no_of_students"
                                                       value="<?php echo  isset($tuition->no_of_students) ? $tuition->no_of_students:'1'; ?>"
                                                       name="no_of_students" placeholder="No of students" maxlength="3"
                                                       required>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="is_approved"
                                                   class="col-sm-2 control-label">Is Approved ?</label>

                                            <div class="col-sm-10">
                                                <div class="checkbox">
                                                    <div class="row">
                                                        <div class="col-sm-4">
                                                            <label>
                                                                <input type="checkbox" name="is_approved"  id="is_approved"
                                                                       value="1" <?php echo isset($tuition)&&($tuition->is_approved==1) ? 'checked':'' ; ?>>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="is_active"
                                                   class="col-sm-2 control-label">Is Active ?</label>

                                            <div class="col-sm-10">
                                                <div class="checkbox">
                                                    <div class="row">
                                                        <div class="col-sm-4">
                                                            <label>
                                                                <input type="checkbox" name="is_active" id="is_active"
                                                                       value="1" <?php echo isset($tuition)&&($tuition->is_active==1) ? 'checked':'' ; ?>>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="location_id" class="col-sm-2 control-label">Location<span
                                                        style="color: red">*</span></label>

                                            <div class="col-sm-10">
                                                <select name="location_id" id="location_id"  class="form-control select2"
                                                        data-placeholder="Select Location" required>
                                                    <option value="">Select Location</option>
                                                    <?php foreach($locations as $location): ?>
                                                        <option value="<?php echo $location->id; ?>"<?php if(isset($tuition->location_id) && $tuition->location_id==$location->id) echo  'selected'; ?>>
                                                            <?php echo $location->locations; ?>
                                                        </option>
                                                    <?php endforeach; ?>

                                                </select>
                                                <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="gender_id" class="col-sm-2 control-label">Tuition Status<span
                                                        style="color: red">*</span></label>

                                            <div class="col-sm-10">
                                                <select name="tuition_status_id" id="tuition_status_id" class="form-control select2"
                                                        data-placeholder="Select Status" required>
                                                    <option value="">Select Tutiion Status</option>
                                                    <?php foreach($assign_status as $assign): ?>
                                                        <option value="<?php echo $assign->id ?>"<?php if(isset($tuition->tuition_status_id) && $tuition->tuition_status_id==$assign->id) echo  'selected'; ?>>
                                                            <?php echo $assign->name ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="special_notes" class="col-sm-2 control-label">Choose Note Category</label>

                                            <div class="col-sm-10">
                                                <select name="special_notes" id="special_notes" class="form-control note">
                                                    <option value="">Please Select</option>
                                                    <?php foreach($notes as $note): ?>
                                                    <option value="<?php echo $note->note ?>"<?php if(isset($tuition->special_notes) && $tuition->special_notes==$note->note) echo  'selected'; ?>>
                                                        <?php echo $note->name ?>
                                                    </option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="note" class="col-sm-2 control-label">Special Requirements<span
                                                        style="color: red">*</span></label>

                                            <div class="col-sm-10">
                                                <textarea class="form-control" rows="3" id="note" name="note" required>
                                                    <?php echo isset($tuition->special_notes)? $tuition->special_notes:''; ?>
                                                </textarea>

                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Tuition -->

                        <div id="accordion" role="tablist" aria-multiselectable="true">
                            <div class="panel panel-default">

                                <div class="panel-heading" role="tab" id="headingOne">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#contactinfo"
                                           aria-expanded="true" aria-controls="contactinfo">
                                            Tuition Details
                                        </a>
                                    </h4>
                                </div>

                                <div id="contactinfo" class="panel-collapse collapse in" role="tabpanel"
                                     aria-labelledby="headingOne">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label for="class" class="col-sm-2 control-label">Grade<span
                                                        style="color: red">*</span></label>

                                            <div class="col-sm-10">
                                                <select name="class" id="class" class="form-control target select2"
                                                        <?php if($status=='update') echo 'disabled'; else echo 'required'; ?>>
                                                    <option>Select Grade</option>
                                                    <?php foreach($classes as $class): ?>
                                                        <option value="<?php echo $class->id; ?>"<?php if(isset($tuition_details[0]->class_id) && $tuition_details[0]->class_id==$class->id) echo  'selected'; ?>><?php echo $class->name; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                                            </div>
                                        </div>
                                        <div class="form-group" <?php if($status=='add') echo "style='display: none;'" ?> id="class-subj">
                                            <label for="subject" class="col-sm-2 control-label">Subjects<span
                                                        style="color: red">*</span></label>

                                            <div class="col-sm-10">
                                                <div class="checkbox">
                                                    <div class="row class-subjects">

                                                        <?php if(!empty($tuition_details)): foreach($tuition_details as $detail): ?>
                                                            <div class="col-sm-3 classsubjects">
                                                                <label>
                                                                    <input type="checkbox" name="subjects[]" id="subject" value="<?php echo  $detail->csmid; ?>" checked disabled>
                                                                    &nbsp; <?php echo $detail->subject_name; ?>
                                                                </label>
                                                             </div>
                                                        <?php endforeach; endif; ?>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="form-group">
                                            <label for="labels" class="col-sm-2 control-label">Labels</label>
                                            <div class="col-sm-10">
                                                <select class="form-control select2" multiple="multiple" id="labels" name="labels[]"
                                                        data-placeholder="Select Labels">
                                                    <?php foreach($labels as $label): ?>
                                                    <option value="<?php echo $label->id; ?>"
                                                    <?php if(isset($tuition_labels) && in_array($label->id, $tuition_labels)) echo "selected";  ?>>
                                                        <?php echo $label->name; ?></option>
                                                    <?php endforeach; ?>

                                                </select>
                                                <input type="hidden" name="label_change" id="label_change" value="" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="address" class="col-sm-2 control-label">Address<span style="color: red">*</span></label>
                                            <div class="col-sm-10">
                                                <textarea class="form-control" rows="3" id="address" name="address" required><?php echo isset($tuition->address)? $tuition->address:''; ?></textarea>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="contact_no" class="col-sm-2 control-label">Contact No.<span style="color: red">*</span></label>
                                            <div class="col-sm-10">

                                                <input type="text" class="form-control" id="contact_no" name="contact_no"
                                                       value="<?php echo  isset($tuition->contact_no) ? $tuition->contact_no:''; ?>"
                                                        placeholder="Contact No"   required>

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="teacher_gender" class="col-sm-2 control-label">Teacher Gender<span
                                                        style="color: red">*</span></label>

                                            <div class="col-sm-10">
                                                <select name="teacher_gender" id="teacher_gender"
                                                        data-placeholder="Select Gender" class="form-control" required>
                                                    <option value="">Please Select</option>
                                                    <?php foreach($gender as $gender): ?>
                                                    <option value="<?php echo $gender->id; ?>"<?php if(isset($tuition->teacher_gender) && $gender->id==$tuition->teacher_gender) echo "selected" ?>><?php echo $gender->name; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="teacher_age" class="col-sm-2 control-label">Teacher Age<span
                                                        style="color: red">*</span></label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="teacher_age" name="teacher_age"
                                                       value="<?php echo  isset($tuition->teacher_age) ? $tuition->teacher_age:''; ?>"
                                                       placeholder="Teacher Age"  required>
                                                <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="contact_person" class="col-sm-2 control-label">Contact Person<span
                                                        style="color: red">*</span></label>

                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="contact_person" name="contact_person"
                                                       value="<?php echo  isset($tuition->contact_person) ? $tuition->contact_person:''; ?>"
                                                       placeholder="Contact Person"   required>
                                                <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="institution" class="col-sm-2 control-label">Prefered Institution</label>
                                            <div class="col-sm-10">

                                                <input type="text" class="form-control" id="institution" name="institution"
                                                       value="<?php echo  isset($tuition->institution) ? $tuition->institution:''; ?>"
                                                       placeholder="Prefered Institution" maxlength="100">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="details" class="col-sm-2 control-label">Other Details</label>
                                            <div class="col-sm-10">
                                                <textarea class="form-control" rows="3" id="details" name="details"><?php echo isset($tuition->details)? $tuition->details:''; ?></textarea>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- /tuition detail -->
                        <div class="box-footer">
                            <a href="{{url('admin/tuitions')}}" class="btn btn-warning pull-right">
                                <i class="fa fa-w fa-remove"></i> Cancel
                            </a>
                            <?php if($status == 'update'): ?>
                            <button type="submit" class="btn btn-primary pull-right" value="save" name="save"
                                    style="margin-right:5px;"><i class="fa fa-fw fa-save"></i> Update
                            </button>
                            <?php else: ?>
                            <button type="submit" class="btn btn-primary pull-right" value="saveadd" name="saveadd"
                                    style="margin-right:5px;"><i class="fa fa-fw fa-save"></i> Save & Add
                            </button>
                            <button type="submit" class="btn btn-primary pull-right" value="save" name="save"
                                    style="margin-right:5px;"><i class="fa fa-fw fa-save"></i> Save
                            </button>

                            <?php endif; ?>

                        </div>
                        <!-- /.box-footer -->
                    </form>
                </div>
                <!-- /.box -->
            </div>
        </div>
    </div>
    @include('layouts.partials.modal');
    </body>
@endsection
@section('page_specific_styles')
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
    @endsection
    @section('page_specific_scripts')
            <!-- FastClick -->
    <script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('plugins/jQuerymask/jquery.mask.min.js') }}"></script>
    <script src="{{ asset('/plugins/iCheck/icheck.min.js') }}" type="text/javascript"></script>
@endsection

@section('page_specific_inline_scripts')
    <script>

        $(function () {

            $(".select2").select2();

            $('.select2').change(function(){
                $('#label_change').val('change');
            });

            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });

        jQuery(document).ready(function () {

            $(document).on("click", ":submit", function(e){
                $("#submitbtnValue").val($(this).val());
            });
            $('#no_of_students').mask('000');
            $('#contact_no').mask('00000000000');

            $('#tuition').on('submit', function (e) {
                e.preventDefault();

                var formData = new FormData($(this)[0]);
                $.ajax({

                    url: '{{url("admin/tuition/detail")}}',
                    type: "POST",
                    data: formData,
                    async: false,
                    beforeSend: function () {
                        $("#wait").modal();
                    },
                    success: function (data) {
                        console.log(data);
                        $('#wait').modal('hide');

                        var success = data['success'];

                        if (success == 'saveandadd') {

                            var redirect_url = '{{url("admin/tuition/add/")}}';

                            $(".modal-footer").append($('<a class="btn btn-outline" ' +
                                    'href="'+redirect_url+'">OK</a>'));
                            $('#myModal').modal();

                        }else if(success == 'save') {

                            var redirect_url = '{{url("admin/tuitions")}}';

                            $(".modal-footer").append($('<a class="btn btn-outline" ' +
                                    'href="'+redirect_url+'">OK</a>'));
                            $('#myModal').modal();

                        }else{
                            $('#warning').modal();
                            $(".warning-message").remove();
                            $(".modal-body-warning").append($('<p>Please select subject</p>'));
                        }

                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });


            });
        });


        $(".target").change(function () {

            var classid = $(this).val();

            $.ajax({
                url: '{{url("admin/tuition/detail/class/subjects")}}',
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
                            '<input type="checkbox" name="subjects[]" id="subject" value="'+mapping_id+'"> &nbsp;'+subject_name+' ' +
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
        $( ".note" ).change(function() {
            var conceptName = $('#special_notes').find(":selected").val();
            //alert(conceptName);
            $("textarea#note").val(conceptName);
        });
    </script>
@endsection

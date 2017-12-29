@extends('layouts.app')

@section('htmlheader_title')
    @role('teacher')
    Teahcer Registeration
    @endrole

    <?php if($status != 'add'): ?>
    {{ trans('Admin | Teacher | Qualification | Update') }}
    <?php else: ?>
    {{ trans('Admin | Teacher | Qualification | Add') }}
    <?php endif; ?>
@endsection

@section('contentheader_title')
    <?php if($status != 'add'): ?>
    {{ trans('Update Qualification') }}
    <?php else: ?>
    {{ trans('Add New Qualification') }}
    <?php endif; ?>
@endsection

@section('main-content')

    <div class="spark-screen">
        <div class="row">
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-info">
                    <!-- form start -->
                    {!! Form::open(array('url'=>'/admin/teacher/qualification','method'=>'POST', 'id'=>'qualification',
                    'enctype'=>'multipart/form-data','class'=>'form-horizontal')) !!}
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="teacherid" id="teacherid"
                           value="<?php echo isset($teacherid) ? $teacherid : $qualifications->teacher_id; ?>">
                    <input type="hidden" name="qid" id="qid"
                           value="<?php echo isset($qualifications->id) ? $qualifications->id : '';?>">
                    <input type="hidden" name="status" id="status" value="<?php echo isset($status) ? $status : ''; ?>">
                    <input type="hidden" name="document"
                           value="<?php echo isset($qualifications->degree_document) ? $qualifications->degree_document : '';?>">
                    <input type="hidden" name="register" id="register"
                           value="<?php echo isset($register) ? $register : '';?>">
                    <input type="hidden" name="submitbtnValue" id="submitbtnValue" value="">
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
                    <div id="accordion" role="tablist" aria-multiselectable="true">
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingOne">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#qualification"
                                       aria-expanded="true" aria-controls="qualification">
                                        Qualification Detail
                                    </a>
                                </h4>
                            </div>
                            <div id="qualification" class="panel-collapse collapse in" role="tabpanel"
                                 aria-labelledby="headingOne">
                                <div class="box-body">

                                    <div class="form-group">
                                        <label for="passing_year1" class="col-sm-2 control-label">Qualification<span
                                                    style="color: red">*</span></label>

                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="qualification_name"
                                                   name="qualification_name"
                                                   value="<?php echo isset($qualifications->qualification_name) ? $qualifications->qualification_name : ''; ?>"
                                                   placeholder="Enter Qualification" maxlength="100" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="highest_degree" class="col-sm-2 control-label">Qualification
                                            Level<span
                                                    style="color: red">*</span></label>

                                        <div class="col-sm-10">
                                            <select class="form-control select2" id="highest_degree"
                                                    name="highest_degree"
                                                    required data-placeholder="Select Highest Degree">
                                                <option value=""></option>
                                                <option value="1st" <?php
                                                    if (isset($qualifications->highest_degree) && $qualifications->highest_degree == '1st') echo "selected"; ?> >
                                                    1st Highest
                                                </option>
                                                <option value="2nd" <?php
                                                    if (isset($qualifications->highest_degree) && $qualifications->highest_degree == '2nd') echo "selected"; ?> >
                                                    2nd Highest
                                                </option>
                                                <option value="other"<?php
                                                    if (isset($qualifications->highest_degree) && $qualifications->highest_degree == 'other') echo "selected"; ?>>
                                                    Other
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="elective_subjects" class="col-sm-2 control-label">Qualification
                                            Status</label>

                                        <div class="col-sm-10">
                                            <label>
                                                <input type="radio" name="continue" class="minimal" value="completed"
                                                <?php if (!isset($qualifications->status) ||
                                                    ($qualifications->status == 'completed') ||
                                                    ($qualifications->status == '') ) echo 'checked'; ?>> Completed
                                            </label>
                                            <label>
                                                <input type="radio" name="continue" class="minimal"
                                                       value="continue"<?php
                                                    if (isset($qualifications->status) &&
                                                        ($qualifications->status == 'continue')
                                                    ) echo 'checked';?> > Continue
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group" id="continue"
                                         style="<?php if (!isset($qualifications->status) ||
                                             ($qualifications->status == 'completed')||($qualifications->status == '')) {
                                             echo 'display: none;'; }?>" >
                                        <label for="higher_degree" class="col-sm-2 control-label">Degree Progress</label>

                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="higher_degree"
                                                   name="higher_degree"
                                                   value="<?php echo isset($qualifications->higher_degree) ? $qualifications->higher_degree : ''; ?>"
                                                   placeholder="Higher Degree" maxlength="250">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="elective_subjects" class="col-sm-2 control-label">Elective
                                            Subjects<span
                                                    style="color: red">*</span></label>

                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="elective_subjects"
                                                   name="elective_subjects"
                                                   value="<?php echo isset($qualifications->elective_subjects) ? $qualifications->elective_subjects : ''; ?>"
                                                   placeholder="Elective/Main Subjects" maxlength="250" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="institution" class="col-sm-2 control-label">College/University/Board<span
                                                    style="color: red">*</span></label>

                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="institution" name="institution"
                                                   value="<?php echo isset($qualifications->institution) ? $qualifications->institution : ''; ?>"
                                                   placeholder="Institution" maxlength="100" required>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label for="passing_year1" class="col-sm-2 control-label">Year Passed<span
                                                    style="color: red">*</span></label>

                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="passing_year"
                                                   name="passing_year"
                                                   value="<?php echo isset($qualifications->passing_year) ? $qualifications->passing_year : ''; ?>"
                                                   placeholder="Year Passed" maxlength="30" required>
                                        </div>
                                    </div>



                                    <div class="form-group">
                                        <label for="grade" class="col-sm-2 control-label">Grade/DIV<span
                                                    style="color: red">*</span></label>

                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="grade" name="grade"
                                                   value="<?php echo isset($qualifications->grade) ? $qualifications->grade : ''; ?>"
                                                   placeholder="Grade" maxlength="20" required>
                                        </div>
                                    </div>



                                    <div class="form-group">
                                        <label for="degree_document" class="col-sm-2 control-label">
                                            Original Degree Scanned Documents (both in 1st highest and 2nd highest)<span
                                                    style="color: red">*</span></label>

                                        <div class="col-sm-4">
                                            <input type="file" name="degree_document" id="degree_document"
                                            <?php if (empty($qualifications->degree_document)) {
                                                echo 'required';
                                            } ?>>

                                        </div>
                                        <?php if(!empty($qualifications->degree_document)) :?>
                                        <label for="degree_document" class="col-sm-6 control-label"
                                               style="text-align: left;">
                                            <label for="degree_document" class="col-sm-6 control-label"
                                                   style="text-align: left;">
                                                <a href="#" class="btn btn-primary btn-qualification-docs">
                                                    <i class="fa fa-fw fa-download"></i>Download Document
                                                </a>
                                            </label>
                                        </label>
                                        <?php endif; ?>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /personal information -->


                    <div class="box-footer">
                        <a href="{{url('admin/teachers')}}" class="btn btn-warning pull-right">
                            <i class="fa fa-w fa-remove"></i> Cancel
                        </a>
                        <?php if($status != 'add'): ?>
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

                    {!! Form::close() !!}
                </div>
                <!-- /.box -->
            </div>
        </div>
    </div>

    <?php if (isset($qualifications)): ?>
    <form class="pull-right form-group" method="post" action="{{ url('admin/doc/download') }}"
          id="qualification-docs" style="display: none;">

        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="path" id="path"
               value="<?php echo base_path()."/teachers/".$qualifications->teacher_id."/qualification/$qualifications->degree_document"; ?>" >
        <input type="hidden" name="filename" id="filename" value="<?php echo $qualifications->degree_document; ?>">

        <button type="submit" class="btn btn-link" id="experience-btn">
            <?php echo $qualifications->degree_document; ?></button>

    </form>
    <?php endif; ?>
    @include('layouts.partials.modal')
    <!-- /.example-modal -->
    @endsection

    @section('page_specific_styles')
        <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/iCheck/all.css') }}">
    @endsection

    @section('page_specific_scripts')
            <!-- FastClick -->
        <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>
        <script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>
        <!-- iCheck 1.0.1 -->
        <script src="{{ asset('plugins/iCheck/icheck.min.js') }}"></script>
    @endsection

@section('page_specific_inline_scripts')
    <script>

        jQuery(document).ready(function() {
            $(".alert").fadeOut(6000);

            $('.btn-qualification-docs').on('click', function () {
                $("#qualification-docs").submit();
            });
        });

        jQuery(document).ready(function(){
            //Initialize Select2 Elements
            $(".select2").select2();
            //iCheck for checkbox and radio inputs
            $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass: 'iradio_minimal-blue'
            });

            //continue button clicked
            $('input').on('ifClicked', function (event) {
                var value = $(this).val();
                if (value == 'continue') {
                    $('#higher_degree').attr('required', 'required');
                    $('#continue').show();
                } else {
                    $('#higher_degree').removeAttr('required');
                    $('#continue').hide();
                }

            });

            jQuery('.btn-qualification').on('click', function(event) {
                jQuery('.qualification-image').toggle('show');
            });

        });

        //Date picker
        $('#datepicker').datepicker({
            autoclose: true
        });

        jQuery( document ).ready( function( $ ) {



            $(document).on("click", ":submit", function(e){
                $("#submitbtnValue").val($(this).val());
            });

            $( '#qualification' ).on( 'submit', function(e) {
                e.preventDefault();

                var formData = new FormData($(this)[0]);
                $.ajax({
                    url:'{{url("admin/teacher/qualification")}}',
                    type: "POST",
                    data: formData,
                    async: true,
                    beforeSend: function(){
                        $("#wait").modal();
                    },
                    success: function (data) {
                        //alert(data);
                        $('#wait').modal('hide');
                        var teacherid = data['teacherid'];
                        var success = data['success'];
                        if(success=='saveandadd'){
                            var redirect_url = '{{url("admin/teacher/qualification/add/")}}/'+teacherid;
                            window.location.replace(redirect_url);
                            toastr.success('Qualification Save Successfully!');

                        }else{
                            toastr.success('Qualification Save Successfully!');
                        }

                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });


            });
        });

        $('.btn-outline').click(function(){
            window.location.href='/hometuition/admin/teacher/qualification/add/6';
        })
    </script>

@endsection

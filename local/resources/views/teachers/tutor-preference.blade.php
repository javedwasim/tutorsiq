@extends('layouts.app')

@section('htmlheader_title')
    @role('teacher')

    @endrole

    <?php if($status != 'add'): ?>
    {{ trans('Teacher | Preference | Update') }}
    <?php else: ?>
    {{ trans('Teacher | Preference | Add') }}
    <?php endif; ?>
@endsection

@section('contentheader_title')
    <?php if($status != 'add'): ?>
    {{ trans('Update Preference') }}
    <?php else: ?>
    {{ trans('Add New Preference') }}
    <?php endif; ?>
@endsection

@section('main-content')

    <div class="spark-screen">
        <div class="row">
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-info">
                    <!-- form start -->
                    <form class="form-horizontal" id="preference" method="post" action="{{ url('teacher/preferences') }}"
                          enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="teacherid" id="teacherid" value="<?php echo isset($preferences) ? $preferences->teacher_id:$teacherid; ?>">
                        <input type="hidden" name="id" id="id" value="<?php echo  isset($preferences->id) ?  $preferences->id:'';?>">
                        <input type="hidden" name="status" id="status" value="<?php echo isset($status) ? $status:''; ?>">
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
                                            Preference Detail
                                        </a>
                                    </h4>
                                </div>
                                <div id="qualification" class="panel-collapse collapse in" role="tabpanel"
                                     aria-labelledby="headingOne">
                                    <div class="box-body">

                                        <div class="form-group">
                                            <label for="gender_id" class="col-sm-2 control-label">Grade<span
                                                        style="color: red">*</span></label>

                                            <div class="col-sm-10">
                                                <select class="form-control target" id="class_id" name="class_id" required>
                                                    <option value="">Please Select</option>
                                                    <?php foreach($classes as $class): ?>
                                                    <option value="<?php echo $class->id; ?>"<?php if(isset($preferences->class_id) && $class->id==$preferences->class_id) echo "selected" ?>><?php echo $class->name; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <!-- Load mapping subjects-->
                                        <div id="subjects"> </div>
                                        <!-- Load mapping subjects-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- /.box -->
            </div>
        </div>
        @include('teachers.email-to-admin-subjects')
    </div>
    @include('layouts.partials.modal')
    @endsection

    @section('page_specific_scripts')

            <!-- FastClick -->
    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>
@endsection

@section('page_specific_inline_scripts')
    <script>

        $( ".target" ).change(function() {

            $(".checkbox").remove();
            var classid = $(this).val();
            var teacherid = $('#teacherid').val();
            var gradeSelected = $('#class_id :selected').text();
            //assign selected grade value to other than above form
            $('#gradeSelected').val(gradeSelected);

            $.ajax({
                url:'{{url("class/subject/load/mappings")}}',
                type: "POST",
                data: {'classid':classid,'teacherid':teacherid, '_token': $('input[name=_token]').val()},

                beforeSend: function(){
                    $("#wait").modal();
                },
                success: function (response) {
                    $(".mapping").css({ 'display': "block" });
                    $('#wait').modal('hide');
                    $('#subjects').empty();
                    $('#subjects').append(response);
                }
            });


        });

        jQuery( document ).ready( function( $ ) {

            $(document).on("click", ":submit", function(e){
                $("#submitbtnValue").val($(this).val());
            });

            $( '#preference' ).on( 'submit', function(e) {

                e.preventDefault();
                var formData = new FormData($(this)[0]);

                $.ajax({

                    url:'{{url("teacher/preferences")}}',
                    type: "POST",
                    data: formData,
                    async: false,
                    beforeSend: function(){
                        $("#wait").modal();
                    },
                    success: function (response) {

                        $(".mapping").css({ 'display': "block" });
                        $('#wait').modal('hide');
                        $('#subjects').empty();
                        $('#subjects').append(response);
                        toastr.success('Subject Preferences Added Successfully!');

                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });

            });

            //send email to admin for other than above
            $( '#adminEmailForm' ).on( 'submit', function(e) {

                e.preventDefault();
                var formData = new FormData($(this)[0]);

                $.ajax({

                    url:'{{url("admin/send/email")}}',
                    type: "POST",
                    data: formData,
                    async: false,
                    beforeSend: function(){
                        $("#wait").modal();
                    },
                    success: function (response) {

                        $('#wait').modal('hide');
                        toastr.success('Email Sent To Admin Successfully!');

                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });


            });


        });


    </script>

@endsection

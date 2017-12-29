@extends('layouts.app')

@section('htmlheader_title')
    {{ trans('Admin | Tuition | Categories') }}
@endsection

@section('contentheader_title')

    <div id="element1">
        {{ trans('Subjeccts/Grades Categories') }}
    </div>



@endsection

@section('main-content')


<!-- form start -->
<form class="form-horizontal" id="categories" method="post" action="{{ url('teacher/tuition/categories/save') }}"
      enctype="multipart/form-data">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="teacher_id" id="teacher_id" value="<?php echo isset($teacher_id) ? $teacher_id : '';?>">
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


    <!-- /Tuition Categories -->
    <div id="accordion" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingOne">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#qualification"
                       aria-expanded="true" aria-controls="qualification">
                        Categories
                    </a>
                </h4>
            </div>
            <div id="qualification" class="panel-collapse collapse in" role="tabpanel"
                 aria-labelledby="headingOne">

                <div class="box-body">
                    <div class="form-group">

                        <div class="col-sm-12">
                            <div class="checkbox">
                                <div class="row">
                                    <?php foreach($categories as $c): ?>
                                    <div class="col-sm-4">
                                        <label>
                                            <input type="checkbox" name="categories[]" class="minimal"
                                                   id="<?php echo $c->categoryid ?>"value="<?php echo $c->categoryid ?>"
                                                    <?php if( isset($c->id) ) echo "checked"; ?> >&nbsp;&nbsp;
                                            <?php echo $c->name ?>
                                        </label>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <div id="element2" style="float: right">

                        <button type="submit" class="btn btn-primary pull-right" value="save"
                                name="save" style="margin-right:5px;"><i
                                    class="fa fa-fw fa-save"></i> Save
                        </button>
                    </div>
                </div>

            </div>
        </div>

    </div>
    <!-- /Tuition Categories -->
</form>
<!-- form end -->


@include('layouts.partials.modal')
@endsection

@section('page_specific_styles')
    <link rel="stylesheet" href="{{ asset('plugins/iCheck/all.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
@endsection

@section('page_specific_scripts')
    <!-- iCheck 1.0.1 -->
    <script src="{{ asset('plugins/iCheck/icheck.min.js') }}"></script>
    <!-- FastClick -->
    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>
@endsection

@section('page_specific_inline_scripts')
    @if (session('status'))
        <script>
            jQuery(document).ready(function ($) {
                toastr.success('Subjects/Grades Categories Save Successfully!');
            });
        </script>
    @endif
    <script>

        jQuery(document).ready(function ($) {

            $("#zone").change(function () {
                $("#zoneForm").submit();
            });

            //Initialize Select2 Elements
            $(".select2").select2();
            //iCheck for checkbox and radio inputs
            $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass: 'iradio_minimal-blue'
            });

            $(document).on("click", ":submit", function (e) {
                $("#submitbtnValue").val($(this).val());
            });

        });

        $(document).ready(function () {
            $(".alert").fadeOut(6000);
        });


    </script>

@endsection

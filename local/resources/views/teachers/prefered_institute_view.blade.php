@extends('layouts.app')

@section('htmlheader_title')
    {{ trans('Admin | Preferred | Institutes') }}
@endsection

@section('contentheader_title')
    <div id="element1">
        {{ trans('Preferred Institutes') }}
        <p class="institute-heading">
            {{ trans('Among the following given institutes in which you have been studying/ currently
        teaching/ taught/ have any teaching  experience to the students of these institutions') }}
        </p>

    </div>

@endsection

@section('main-content')


    <!-- form start -->
    <form class="form-horizontal" id="institutes" method="post" action="{{ url('prefered/institutes/save') }}"
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


    <!-- /Prefered Institutes -->
        <div id="accordion" role="tablist" aria-multiselectable="true">
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingOne">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#qualification"
                           aria-expanded="true" aria-controls="qualification">
                            Institutes
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
                                        <?php foreach($institutes as $i): ?>
                                        <div class="col-sm-4">
                                            <label>
                                                <input type="checkbox" name="institutes[]" class="minimal"
                                                       id="<?php echo $i->instituteid ?>"value="<?php echo $i->instituteid ?>"
                                                <?php if( isset($i->id) ) echo "checked"; ?>  >&nbsp;&nbsp;
                                                <?php echo $i->name ?>
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
        <!-- /Prefered Institutes -->
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
                toastr.success('Institutes Save Successfully!');
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

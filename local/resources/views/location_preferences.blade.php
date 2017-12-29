@extends('layouts.app')

@section('htmlheader_title')

    <?php if($status != 'add'): ?>
    {{ trans('Admin | Location | Preference | Update') }}
    <?php else: ?>
    {{ trans('Admin | Location | Preference | Add') }}
    <?php endif; ?>
@endsection
<form class="form-horizontal" id="locations" method="post" action="{{ url('admin/location/save') }}"
      enctype="multipart/form-data">
@section('contentheader_title')
    <?php if($status != 'add'): ?>
    {{ trans('Update Location Preference') }}
    <?php else: ?>
        <div id="element1">
            {{ trans('Add Prefered Location') }}
        </div>

        <div id="element2" style="float: right">
            <a href="{{url('admin/teachers')}}" class="btn btn-warning pull-right">
                <i class="fa fa-w  fa-chevron-left"></i> Back
            </a>

            <button type="submit" class="btn btn-primary pull-right" value="save"
                    name="save" style="margin-right:5px;"><i
                        class="fa fa-fw fa-save"></i> Save
            </button>
        </div>

    <?php endif; ?>
@endsection

@section('main-content')


    <div class="spark-screen">
        <div class="row">
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-info">
                    <!-- form start -->

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="status" id="status"  value="<?php echo isset($status) ? $status : ''; ?>">
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
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <!-- /Zone1 information -->
                        <div id="accordion" role="tablist" aria-multiselectable="true">
                            <div class="panel panel-default">
                                <div class="panel-heading box-headers" role="tab" id="headingOne">
                                    <h4 class="panel-title">
                                        <div id="element1">
                                            <div id="element1"><input type="checkbox" id="checkAll" > Select  All</div>
                                            <div id="element2">Zone1</div>

                                        </div>
                                        <div id="element2">
                                            <p><?php echo isset($zones[0]->description) ? $zones[0]->description:"";?></p>
                                        </div>

                                    </h4>
                                </div>
                                <div id="zone1" class="panel-collapse collapse in" role="tabpanel"
                                     aria-labelledby="headingOne">
                                    <div class="box-body">

                                        <?php if(isset($zoneLocations['z1L'])): ?>
                                        <div class="form-group">

                                            <div class="col-sm-12">
                                                <div class="checkbox">
                                                    <div class="row">
                                                        <?php foreach($zoneLocations['z1L'] as $location): ?>
                                                        <div class="col-sm-4">
                                                            <label>
                                                                <input type="checkbox" name="zilocations[]" class="minimal zone1"
                                                                       id="<?php echo $location->id ?>"value="<?php echo $location->id ?>"
                                                                <?php if( isset($location->location_id) ) echo "checked"; ?> >&nbsp;&nbsp;
                                                                <?php echo $location->location_name ?>
                                                            </label>
                                                        </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endif ;?>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- /Zone1 information -->

                        <!-- /Zone2 information -->
                        <div id="accordion" role="tablist" aria-multiselectable="true">
                            <div class="panel panel-default">
                                <div class="panel-heading box-headers" role="tab" id="headingOne">
                                    <h4 class="panel-title">
                                        <div id="element1">
                                            <div id="element1"><input type="checkbox" id="checkAllZ2" > Select  All</div>
                                            <div id="element2">Zone 2</div>

                                        </div>
                                        <div id="element2">
                                            <p><?php echo isset($zones[1]->description) ? $zones[1]->description:"";?></p>
                                        </div>

                                    </h4>
                                </div>
                                <div id="zone2" class="panel-collapse collapse in" role="tabpanel"
                                     aria-labelledby="headingOne">
                                    <div class="box-body">

                                        <?php if(isset($zoneLocations['z2L'])): ?>
                                        <div class="form-group">

                                            <div class="col-sm-12">
                                                <div class="checkbox">
                                                    <div class="row">
                                                        <?php foreach($zoneLocations['z2L'] as $location): ?>
                                                        <div class="col-sm-4">
                                                            <label>
                                                                <input type="checkbox" name="z2locations[]" class="minimal zone2"
                                                                       id="<?php echo $location->id ?>"value="<?php echo $location->id ?>"
                                                                <?php if( isset($location->location_id) ) echo "checked"; ?> >&nbsp;&nbsp;
                                                                <?php echo $location->location_name ?>
                                                            </label>
                                                        </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endif ;?>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- /Zone2 information -->

                        <!-- /Zone3 information -->
                        <div id="accordion" role="tablist" aria-multiselectable="true">
                            <div class="panel panel-default">
                                <div class="panel-heading box-headers" role="tab" id="headingOne">
                                    <h4 class="panel-title">
                                        <div id="element1">
                                            <div id="element1"><input type="checkbox" id="checkAllZ3" > Select  All</div>
                                            <div id="element2">Zone3</div>

                                        </div>
                                        <div id="element2">
                                            <p><?php echo isset($zones[2]->description) ? $zones[2]->description:"";?></p>
                                        </div>

                                    </h4>
                                </div>
                                <div id="zone3" class="panel-collapse collapse in" role="tabpanel"
                                     aria-labelledby="headingOne">
                                    <div class="box-body">

                                        <?php if(isset($zoneLocations['z3L'])): ?>
                                        <div class="form-group">

                                            <div class="col-sm-12">
                                                <div class="checkbox">
                                                    <div class="row">
                                                        <?php foreach($zoneLocations['z3L'] as $location): ?>
                                                        <div class="col-sm-4">
                                                            <label>
                                                                <input type="checkbox" name="z3locations[]" class="minimal zone3"
                                                                       id="<?php echo $location->id ?>"value="<?php echo $location->id ?>"
                                                                <?php if( isset($location->location_id) ) echo "checked"; ?> >&nbsp;&nbsp;
                                                                <?php echo $location->location_name ?>
                                                            </label>
                                                        </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endif ;?>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- /Zone3 information -->

                        <!-- /Zone4 information -->
                        <div id="accordion" role="tablist" aria-multiselectable="true">
                            <div class="panel panel-default">
                                <div class="panel-heading box-headers" role="tab" id="headingOne">
                                    <h4 class="panel-title">
                                        <div id="element1">
                                            <div id="element1"><input type="checkbox" id="checkAllZ4" > Select  All</div>
                                            <div id="element2">Zone4</div>
                                        </div>
                                        <div id="element2">
                                            <p><?php echo isset($zones[3]->description) ? $zones[3]->description:"";?></p>
                                        </div>

                                    </h4>
                                </div>
                                <div id="zone4" class="panel-collapse collapse in" role="tabpanel"
                                     aria-labelledby="headingOne">
                                    <div class="box-body">
                                        <?php if(isset($zoneLocations['z4L'])): ?>
                                        <div class="form-group">

                                            <div class="col-sm-12">
                                                <div class="checkbox">
                                                    <div class="row">
                                                        <?php foreach($zoneLocations['z4L'] as $location): ?>
                                                        <div class="col-sm-4">
                                                            <label>
                                                                <input type="checkbox" name="z4locations[]" class="minimal zone4"
                                                                       id="<?php echo $location->id ?>"value="<?php echo $location->id ?>"
                                                                <?php if( isset($location->location_id) ) echo "checked"; ?> >&nbsp;&nbsp;
                                                                <?php echo $location->location_name ?>
                                                            </label>
                                                        </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endif ;?>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- /Zone4 information -->

                        <!-- /Zone5 information -->
                        <div id="accordion" role="tablist" aria-multiselectable="true">
                            <div class="panel panel-default">
                                <div class="panel-heading box-headers" role="tab" id="headingOne">
                                    <h4 class="panel-title">
                                        <div id="element1">
                                            <div id="element1"><input type="checkbox" id="checkAllZ5" > Select  All</div>
                                            <div id="element2">Zone 5 </div>
                                        </div>
                                        <div id="element2">
                                            <p><?php echo isset($zones[4]->description) ? $zones[4]->description:"";?></p>
                                        </div>

                                    </h4>
                                </div>
                                <div id="zone5" class="panel-collapse collapse in" role="tabpanel"
                                     aria-labelledby="headingOne">
                                    <div class="box-body">

                                        <?php if(isset($zoneLocations['z5L'])): ?>
                                        <div class="form-group">

                                            <div class="col-sm-12">
                                                <div class="checkbox">
                                                    <div class="row">
                                                        <?php foreach($zoneLocations['z5L'] as $location): ?>
                                                        <div class="col-sm-4">
                                                            <label>
                                                                <input type="checkbox" name="z5locations[]" class="minimal zone5"
                                                                       id="<?php echo $location->id ?>"value="<?php echo $location->id ?>"
                                                                <?php if( isset($location->location_id) ) echo "checked"; ?> >&nbsp;&nbsp;
                                                                <?php echo $location->location_name ?>
                                                            </label>
                                                        </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endif ;?>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- /Zone5 information -->

                    </form>
                </div>
                <!-- /.box -->
            </div>
        </div>
    </div>

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
    @endsection

@section('page_specific_inline_scripts')
    <script>

        jQuery(document).ready(function ($) {

            //iCheck for checkbox and radio inputs
            $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass: 'iradio_minimal-blue'
            });

            $(document).on("click", ":submit", function (e) {
                $("#submitbtnValue").val($(this).val());
            });

            $('#locations').on('submit', function (e) {
                e.preventDefault();

                var formData = new FormData($(this)[0]);
                $.ajax({

                    url: '{{url("admin/teacher/location/preference/save")}}',
                    type: "POST",
                    data: formData,
                    async: false,
                    beforeSend: function () {
                        $("#wait").modal();
                    },
                    success: function (data) {

                        $('#wait').modal('hide');
                        var teacherid = data['teacherid'];
                        var success = data['success'];

                        if (success == 'saveandadd') {


                        } else if (success == 'save') {

                            toastr.success('Locations Save Successfully!');

                        } else {
                            toastr.warning('Please select locations');
                        }

                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });


            });
        });

        jQuery(document).ready(function () {


            $(".alert").fadeOut(6000);


            $('#checkAll').click(function () {

                if(this.checked){
                    $('.zone1').iCheck('check');
                }else{
                    $('.zone1').iCheck('uncheck');
                }

            });

            $('#checkAllZ2').click(function () {

                if(this.checked){
                    $('.zone2').iCheck('check');
                }else{
                    $('.zone2').iCheck('uncheck');
                }

            });

            $('#checkAllZ3').click(function () {

                if(this.checked){
                    $('.zone3').iCheck('check');
                }else{
                    $('.zone3').iCheck('uncheck');
                }

            });

            $('#checkAllZ4').click(function () {

                if(this.checked){
                    $('.zone4').iCheck('check');
                }else{
                    $('.zone4').iCheck('uncheck');
                }

            });

            $('#checkAllZ5').click(function () {

                if(this.checked){
                    $('.zone5').iCheck('check');
                }else{
                    $('.zone5').iCheck('uncheck');
                }

            });

        });


    </script>

@endsection

@extends('layouts.app')

@section('htmlheader_title')

    <?php if($status != 'add'): ?>
    {{ trans('Admin | Location | Update') }}
    <?php else: ?>
    {{ trans('Admin | Location | Add') }}
    <?php endif; ?>
@endsection

@section('contentheader_title')
    <?php if($status != 'add'): ?>
    {{ trans('Update Location') }}
    <?php else: ?>
    {{ trans('Add New Location') }}
    <?php endif; ?>
@endsection

@section('main-content')

    <div class="spark-screen">
        <div class="row">
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-info">
                    <!-- form start -->
                    <form class="form-horizontal" id="locations" method="post" action="{{ url('admin/location/save') }}"
                          enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="status" id="status" value="<?php echo isset($status) ? $status:''; ?>">
                        <input type="hidden" name="id" id="id" value="<?php echo  isset($Location->id) ?  $Location->id:'';?>">
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
                                            Location Detail
                                        </a>
                                    </h4>
                                </div>
                                <div id="qualification" class="panel-collapse collapse in" role="tabpanel"
                                     aria-labelledby="headingOne">
                                    <div class="box-body">

                                        <div class="form-group">
                                            <label for="gender_id" class="col-sm-2 control-label">Zone<span
                                                        style="color: red">*</span></label>

                                            <div class="col-sm-10">
                                                <select class="form-control select2" id="zone" name="zone"
                                                        data-placeholder="Select Zone">
                                                    <option value=""></option>
                                                    <?php foreach($zones as $zone): ?>
                                                    <option value="<?php echo $zone->id; ?>"<?php if( isset($Location->zone_id) && ($zone->id == $Location->zone_id) ) echo "selected"; ?> ><?php echo $zone->name; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="gender_id" class="col-sm-2 control-label">Name<span
                                                        style="color: red">*</span></label>

                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="locations" value="<?php echo isset($Location->locations) ? $Location->locations:''; ?>"
                                                       name="locations"   placeholder="Enter Location"  maxlength="100" required>
                                            </div>
                                        </div>

                                        <div class="box-footer">
                                            <a href="{{url('admin/locations')}}" class="btn btn-warning pull-right">
                                                <i class="fa fa-w fa-remove"></i> Cancel
                                            </a>
                                            <?php if($status != 'add'): ?>
                                            <button type="submit" class="btn btn-primary pull-right" value="save" name="save" style="margin-right:5px;"><i class="fa fa-fw fa-save"></i> Update</button>
                                            <?php else: ?>
                                            <button type="submit" class="btn btn-primary pull-right" value="saveadd" name="saveadd" style="margin-right:5px;"><i class="fa fa-fw fa-save" ></i> Save & Add</button>
                                            <button type="submit" class="btn btn-primary pull-right" value="save" name="save" style="margin-right:5px;"><i class="fa fa-fw fa-save" ></i> Save</button>
                                            <?php endif; ?>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /personal information -->

                        <!-- /.box-footer -->
                    </form>
                </div>
                <!-- /.box -->
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-success" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Success</h4>
                </div>
                <div class="modal-body">
                    Information has been saved successfully.
                </div>
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>

    <!-- ajax modal -->
    <div class="modal fade" id="wait" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="box box-danger">
                <div class="box-header">
                    <h3 class="box-title">Processing state</h3>
                </div>
                <div class="box-body">
                    Please wait........
                </div>
                <!-- /.box-body -->
                <!-- Loading (remove the following to stop the loading)-->
                <div class="overlay">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>
                <!-- end loading -->
            </div>
        </div>
    </div>
    @endsection
@section('page_specific_styles')
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
@endsection
@section('page_specific_scripts')
    <!-- FastClick -->
    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>
@endsection

@section('page_specific_inline_scripts')
    <script>

        jQuery( document ).ready( function( $ ) {

            //Initialize Select2 Elements
            $(".select2").select2();

            $(document).on("click", ":submit", function(e){
                $("#submitbtnValue").val($(this).val());
            });

            $( '#locations' ).on( 'submit', function(e) {
                e.preventDefault();

                var formData = new FormData($(this)[0]);
                $.ajax({

                    url:'{{url("admin/location/save")}}',
                    type: "POST",
                    data: formData,
                    async: false,
                    beforeSend: function(){
                        $("#wait").modal();
                    },
                    success: function (data) {
                        //alert(data);
                        $('#wait').modal('hide');
                        var teacherid = data['teacherid'];
                        var success = data['success'];

                        if(success=='saveandadd'){

                            var redirect_url = '{{url("admin/zone/add")}}';
                            toastr.success('Location Save Successfully!');

                        }else{

                            var redirect_url = '{{url("admin/lmessage")}}';
                            window.location.replace(redirect_url);

                        }

                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });


            });
        });


    </script>

@endsection

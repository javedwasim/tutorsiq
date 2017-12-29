@extends('layouts.app')

@section('htmlheader_title')

    <?php if($status != 'add'): ?>
    {{ trans('Admin | Teacher | Institute | Update') }}
    <?php else: ?>
    {{ trans('Admin | Teacher | Institute | Add') }}
    <?php endif; ?>
@endsection

@section('contentheader_title')
    <?php if($status != 'add'): ?>
    {{ trans('Update  Institute') }}
    <?php else: ?>
    {{ trans('Add New  Institute') }}
    <?php endif; ?>
@endsection

@section('main-content')

    <div class="spark-screen">
        <div class="row">
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-info">
                    <!-- form start -->
                    <form class="form-horizontal" id="preference" method="post" action="{{ url('admin/institute/save') }}"
                          enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="status" id="status"
                               value="<?php echo isset($status) ? $status : ''; ?>">
                        <input type="hidden" name="id" id="id"
                               value="<?php echo isset($institute->id) ? $institute->id : '';?>">
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
                                            Institute Detail
                                        </a>
                                    </h4>
                                </div>
                                <div id="qualification" class="panel-collapse collapse in" role="tabpanel"
                                     aria-labelledby="headingOne">
                                    <div class="box-body">

                                        <div class="form-group">
                                            <label for="gender_id" class="col-sm-2 control-label">Name<span
                                                        style="color: red">*</span></label>

                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="name"
                                                       value="<?php echo isset($institute->name) ? $institute->name : ''; ?>"
                                                       name="name" placeholder="Enter Institue Name" maxlength="100"
                                                       required>
                                            </div>
                                        </div>

                                        <div class="box-footer">
                                            <a href="{{url('admin/institutes')}}" class="btn btn-warning pull-right">
                                                <i class="fa fa-w fa-remove"></i> Cancel
                                            </a>
                                            <?php if($status != 'add'): ?>
                                            <button type="submit" class="btn btn-primary pull-right" value="save"
                                                    name="save" style="margin-right:5px;"><i
                                                        class="fa fa-fw fa-save"></i> Update
                                            </button>
                                            <?php else: ?>
                                            <button type="submit" class="btn btn-primary pull-right" value="saveadd"
                                                    name="saveadd" style="margin-right:5px;"><i
                                                        class="fa fa-fw fa-save"></i> Save & Add
                                            </button>
                                            <button type="submit" class="btn btn-primary pull-right" value="save"
                                                    name="save" style="margin-right:5px;"><i
                                                        class="fa fa-fw fa-save"></i> Save
                                            </button>
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

    @include('layouts.partials.modal')
    @endsection

    @section('page_specific_scripts')

            <!-- FastClick -->
    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>
@endsection

@section('page_specific_inline_scripts')
    <script>

        jQuery(document).ready(function ($) {

            $(document).on("click", ":submit", function (e) {
                $("#submitbtnValue").val($(this).val());
            });

            $('#preference').on('submit', function (e) {
                e.preventDefault();

                var formData = new FormData($(this)[0]);
                $.ajax({

                    url:'{{url("admin/institute/save")}}',
                    type: "POST",
                    data: formData,
                    async: false,
                    beforeSend: function () {
                        $("#wait").modal();
                    },
                    success: function (data) {
                        // alert(data['success']);
                        $('#wait').modal('hide');
                        var teacherid = data['teacherid'];
                        var success = data['success'];

                        if (success == 'saveandadd') {

                            var redirect_url = '{{url("admin/institute/add")}}';
                            toastr.success('Intitute Save Successfully!');

                        } else {

                            var redirect_url = '{{url("admin/imessage")}}';
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

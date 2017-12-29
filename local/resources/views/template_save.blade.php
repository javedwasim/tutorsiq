@extends('layouts.app')

@section('page_specific_scripts')
    <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
    <script>tinymce.init({selector: 'textarea'});</script>
    <!-- FastClick -->
    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>
@endsection

@section('htmlheader_title')

    <?php if($status != 'add'): ?>
    {{ trans('Admin | Teacher | Template | Update') }}
    <?php else: ?>
    {{ trans('Admin | Teacher | Template | Add') }}
    <?php endif; ?>
@endsection

@section('contentheader_title')
    <?php if($status != 'add'): ?>
    {{ trans('Update Template') }}
    <?php else: ?>
    {{ trans('Add  Template') }}
    <?php endif; ?>
@endsection

@section('main-content')

    <div class="col-md-9">

        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">Email Template</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
                <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->

            <form class="form-horizontal" id="templater" method="post" action="{{ url('admin/template/save') }}"
                  enctype="multipart/form-data" novalidate>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="status" id="status"
                       value="<?php echo isset($status) ? $status : ''; ?>">
                <input type="hidden" name="id" id="id"
                       value="<?php echo isset($template->id) ? $template->id : '';?>">
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

                <div class="box-body" style="display: block;">

                    <div class="form-group">
                        <label for="title" class="col-sm-2 control-label">Title<span
                                    style="color: red">*</span></label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="title"
                                   value="<?php echo isset($template->title) ? $template->title : ''; ?>"
                                   name="title" placeholder="Enter  Title" maxlength="100"
                                   required>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="subject" class="col-sm-2 control-label">Subject<span
                                    style="color: red">*</span></label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="subject"
                                   value="<?php echo isset($template->subject) ? $template->subject : ''; ?>"
                                   name="subject" placeholder="Enter Subject" maxlength="100"
                                   required>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="body" class="col-sm-2 control-label">Body<span
                                    style="color: red">*</span></label>

                        <div class="col-sm-10">
                            <textarea class="form-control" rows="10" name="body"
                                      placeholder="Enter ..." required>
                                <?php echo isset($template->body) ? $template->body : ''; ?>
                            </textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="body" class="col-sm-2 control-label">Is System(?)<span
                                    style="color: red">*</span></label>

                        <div class="col-sm-10">
                            <input type="radio" name="is_active" id="is_active" value="1"
                            <?php echo isset($template->is_active) && ($template->is_active == 1) ? 'checked' : ''; ?> >&nbsp;Yes
                            &nbsp;

                            <input type="radio" name="is_active" id="is_active" value="0"
                            <?php echo isset($template->is_active) && ($template->is_active == 0) ? 'checked' : ''; ?> >&nbsp;No
                            &nbsp;
                        </div>
                    </div>

                    <div class="box-footer">
                        <a href="{{url('admin/templates')}}" class="btn btn-warning pull-right">
                            <i class="fa fa-w fa-remove"></i> Cancel
                        </a>
                        <?php if($status != 'add'): ?>
                        <button type="submit" class="btn btn-primary pull-right" value="save"
                                name="save" style="margin-right:5px;"><i
                                    class="fa fa-fw fa-save"></i> Update
                        </button>
                        <?php else: ?>

                        <button type="submit" class="btn btn-primary pull-right" value="save"
                                name="save" style="margin-right:5px;"><i
                                    class="fa fa-fw fa-save"></i> Save
                        </button>
                        <?php endif; ?>

                    </div>


                </div>
                <!-- /.box-body -->
            </form>
        </div>
        <!-- /.box -->

    </div>

    <div class="col-md-3">

        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">Place Holders</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
                <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body" style="display: block;">
                <ul class="nav nav-stacked">
                    <li><a href="#">Full Name <span class="pull-right badge bg-blue">#fullname#</span></a></li>
                    <li><a href="#">Email <span class="pull-right badge bg-aqua">#email#</span></a></li>
                </ul>
            </div>
            <!-- /.box-body -->
        </div>


    </div>

@endsection

@include('layouts.partials.modal')

@section('page_specific_inline_scripts')
    <script>

        jQuery(document).ready(function ($) {

            $(document).on("click", ":submit", function (e) {
                $("#submitbtnValue").val($(this).val());
            });

            $('#template').on('submit', function (e) {
                e.preventDefault();

                var formData = new FormData($(this)[0]);
                $.ajax({

                    url: '{{url("admin/template/save")}}',
                    type: "POST",
                    data: formData,
                    async: false,
                    beforeSend: function () {
                        $("#wait").modal();
                    },
                    success: function (data) {
                        // alert(data);
                        $('#wait').modal('hide');
                        var teacherid = data['teacherid'];
                        var success = data['success'];

                        if (success == 'saveandadd') {

                            var redirect_url = '{{url("admin/template/add")}}';
                            $(".modal-footer").append($('<a class="btn btn-outline" ' +
                                    'href="' + redirect_url + '">OK</a>'));
                            $('#myModal').modal();

                        } else {

                            var redirect_url = '{{url("admin/templates")}}';
                            $(".modal-footer").append($('<a class="btn btn-outline" ' +
                                    'href="' + redirect_url + '">OK</a>'));
                            $('#myModal').modal();

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

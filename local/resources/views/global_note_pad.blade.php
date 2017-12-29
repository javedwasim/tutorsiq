@extends('layouts.app')

@section('htmlheader_title')

    @role('teacher')
    Teacher Portal
    @endrole
    @role('admin')
    Admin | Global Notes
    @endrole

@endsection

@section('contentheader_title')
    {{ trans('Global Notes') }}
@endsection

@section('main-content')
    <div class="box box-warning">
        <div class="box-header with-border">
            <h3 class="box-title">Global Notes</h3>

            <div class="box-tools pull-right"></div>
            <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->

        <form class="form-horizontal" id="templater" method="post" action="{{ url('admin/global/notes') }}"
              enctype="multipart/form-data" novalidate>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="email_title" id="email_title" value="">

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
        @endif

        <!-- Custom Tabs -->
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">

                    <li class="active"><a href="#tab_1" data-toggle="tab">New Arrivals</a></li>
                    <li><a href="#tab_2" data-toggle="tab">Pending Retry</a></li>

                </ul>
                <div class="tab-content">

                    <div class="tab-pane active" id="tab_1">
                        <textarea class="form-control" rows="10" id="new_arrivals" name="new_arrivals"
                                  placeholder="New Arrivals ..."
                                  required><?php echo isset($notes->new_arrivals) ? $notes->new_arrivals : ''; ?></textarea>
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="tab_2">
                       <textarea class="form-control" rows="12" id="pending_retry" name="pending_retry"
                                 placeholder="Pending Retry ..."
                                 required><?php echo isset($notes->pending_retry) ? $notes->pending_retry : ''; ?></textarea>
                    </div>
                    <!-- /.tab-pane -->

                    <div class="box-footer">
                        <button type="submit" class="btn btn-warning pull-right" value="delete"
                                name="delete" style="margin-right:5px;"><i
                                    class="fa fa-fw fa-save"></i> Delete Notes
                        </button>
                        <button type="submit" class="btn btn-primary pull-right" value="save"
                                name="save" style="margin-right:5px;"><i
                                    class="fa fa-fw fa-save"></i> Save Notes
                        </button>
                    </div>

                </div>
                <!-- /.tab-content -->
            </div>
            <!-- nav-tabs-custom -->

        </form>
    </div>
    <!-- /.box -->
@endsection

@section('page_specific_inline_scripts')
    @if (session('notes'))
        <script>
            jQuery(document).ready(function ($) {
                toastr.options = {
                    "closeButton": true,
                    "debug": false,
                    "positionClass": "toast-top-center",
                    "preventDuplicates": true,
                    "toastClass": "animated fadeInDown",
                    "onclick": null,
                    "showDuration": "10000",
                    "hideDuration": "5000",
                    "timeOut": "2000",
                    "extendedTimeOut": "0",
                    "showEasing": "linear",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                };
                toastr.success('{{session('notes')}}');
                {{session::forget('notes')}}

            });
        </script>
    @endif
    <script>
        $(document).ready(function () {

            $(".alert").fadeOut(6000);


        });

        $('#new_line').change(function () {

            if ($(this).is(":checked")) {

                var str = $('#phone_list').val();
                var newStr = str.split(";").join("\n");
                $('#phone_list').val(newStr);


            } else {

                var str = $('#phone_list').val();
                $('#phone_list').val(str.replace(/\n/g, ";"));

            }
        });

    </script>

@endsection

@extends('layouts.app')

@section('htmlheader_title')
    @role('teacher')
        Teahcer Registeration
    @endrole
    <?php if($status == 'Qualification'): ?>
        {{ trans('Admin | Teacher | Qualification | Docs') }}
    <?php endif; ?>
@endsection

@section('contentheader_title')
    <?php if($status == 'Qualification'): ?>
        {{ trans('Teacher Qualification Documents') }}
    <?php endif; ?>
@endsection

@section('main-content')

    <div class="spark-screen">
        <div class="row">
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-info">

                    <div id="accordion" role="tablist" aria-multiselectable="true">
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingOne">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#qualification"
                                       aria-expanded="true" aria-controls="qualification">
                                        Document Image:
                                    </a>
                                </h4>
                            </div>
                            <div id="qualification" class="panel-collapse collapse in" role="tabpanel"
                                 aria-labelledby="headingOne">
                                <div class="box-body">
                                    <img src="<?php  echo url("/local/teachers/".$teacherid."/qualification/".$docname); ?>" alt="Teacher Documents" width="100%">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.box -->
            </div>
        </div>
    </div>
    @endsection

    @section('page_specific_scripts')

            <!-- FastClick -->
    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>
@endsection

@section('page_specific_inline_scripts')
    <script>
        //Date picker
        $('#datepicker').datepicker({
            autoclose: true
        });
    </script>
@endsection

@section('page_specific_styles')
        <!-- DataTables -->
<link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables/jquery.dataTables.min.css') }}">
{{--<link rel="stylesheet" href="{{ asset('plugins/datatables/bootstrap.min.css') }}">--}}
@endsection
        <!-- SELECT2 EXAMPLE -->
@section('page_specific_scripts')

        <!-- DataTables -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
<!-- SlimScroll -->
<script src="{{ asset('plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>
<!-- FastClick -->
<script src="{{ asset('plugins/fastclick/fastclick.js') }}"></script>

@endsection

<div class="box box-primary">
    <a href="#">
        <div class="box-header with-border" data-widget="collapse">
            <i class="fa fa-minus pull-right" style="font-size:12px;
        margin-top: 5px;"></i>
            <h1 class="box-title">Text</h1>
        </div>
    </a>
    <div class="box-body" style="padding: 0px;">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <a class="btn btn-primary pull-right" id="copyButton">
                            <i class="fa fa-fw fa-copy"></i> Copy
                        </a>
                    </div>
                    @if (session('status'))
                        <div class="alert alert-success" style="@">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if (session('warning'))
                        <div class="alert alert-warning">
                            {{ session('warning') }}
                        </div>
                        @endif
                                <!-- /.box-header -->

                        <div class="box-body">
                            <textarea class="form-control" id="smsText" rows="15" placeholder="Enter ..."><?php echo $smsText; ?></textarea>

                        </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.partials.modal')

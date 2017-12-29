@extends('layouts.app')

@section('htmlheader_title')

    @role('teacher')
    Teacher Portal
    @endrole
    @role('admin')
    Admin | Customer Phome #
    @endrole

@endsection

@section('contentheader_title')
    {{ trans('Customer Phone #') }}
@endsection

@section('main-content')

    <div class="box box-warning">
        <div class="box-header with-border">
            <h3 class="box-title">Customer Phone Numbers</h3>

            <div class="box-tools pull-right">&nbsp; </div>
            <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->


        <div class="box-body" style="display: block;">

            <div class="form-group">
                <label for="body" class="col-sm-2 control-label">Convert to new line:</label>

                <div class="col-sm-2">
                    <input type="checkbox" id="new_line">
                </div>

                <label for="body" class="col-sm-2 control-label">Remove First Digit:</label>

                <div class="col-sm-2">
                    <input type="checkbox" id="remove_first" name="new_line">
                </div>

                <label for="body" class="col-sm-2 control-label">
                    <button type="button" class="btn btn-primary pull-right copyButton" data-dismiss="modal">Copy Phone
                        #
                    </button>
                </label>
                <label for="body" class="col-sm-2 control-label">
                    <button type="button" class="btn btn-danger pull-right delButton" data-dismiss="modal">Delete Phone
                        #
                    </button>
                </label>

            </div>

        </div>


        <div class="box-body" style="display: block;">

            <div class="form-group">
                <label for="body" class="col-sm-2 control-label">Phone Numbers</label>

                <div class="col-sm-10">
                        <textarea class="form-control" rows="15" id="phone_list" name="body" placeholder="Enter ..."
                                  required><?php echo isset($phone_numers) ? $phone_numers : ''; ?> </textarea>
                </div>
            </div>

        </div>
        <!-- /.box-body -->

    </div>
    <!-- /.box -->

@endsection

@section('page_specific_scripts')
    <script src="{{ asset('/js/tutors.js') }}"></script>
    <script>
        $(".delButton").click(function () {
//            confirm("Are you sure to delete Phone Numbers!");
            $("#phone_list").val('');
            toastr.success('Phone Numbers Cleared Successfully!');
        });
    </script>
@endsection


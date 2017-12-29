@extends('layouts.app')

@section('htmlheader_title')
    @role('teacher')
    Teahcer Registeration
    @endrole

    <?php if($status != 'add'): ?>
    {{ trans('Admin | Teacher | Tuition | History | Update') }}
    <?php else: ?>
    {{ trans('Admin | Teacher | Tuition | History | Add') }}
    <?php endif; ?>
@endsection

@section('contentheader_title')
    <?php if($status != 'add'): ?>
    {{ trans('Update Tuition History') }}
    <?php else: ?>
    {{ trans('Add New Tuition History') }}
    <?php endif; ?>
@endsection

@section('main-content')

    <div class="spark-screen">
        <div class="row">
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-info">
                    <!-- form start -->
                    <form class="form-horizontal" method="post" action="{{ url('admin/teacher/reference') }}"
                          enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="teacherid" id="teacherid" value="<?php echo isset($history) ? $history->teacher_id:$teacherid; ?>">
                        <input type="hidden" name="id" id="id" value="<?php echo  isset($history->id) ?  $history->id:'';?>">
                        <input type="hidden" name="status" id="status" value="<?php echo isset($status) ? $status:''; ?>">

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
                                            Tuition History Detail
                                        </a>
                                    </h4>
                                </div>
                                <div id="qualification" class="panel-collapse collapse in" role="tabpanel"
                                     aria-labelledby="headingOne">
                                    <div class="box-body">

                                        <div class="form-group">
                                            <label for="tuition_assign_id" class="col-sm-2 control-label">Tuition Assign<span
                                                        style="color: red">*</span></label>

                                            <div class="col-sm-10">
                                                <select class="form-control" id="tuition_assign_id" name="tuition_assign_id" required>
                                                    <option value="">Please Select</option>
                                                    <?php foreach($tuition_assign as $assign): ?>
                                                        <option value="<?php echo $assign->id; ?>"><?php echo $assign->id; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>

                                        {{--<div class="form-group">--}}
                                            {{--<label for="contact_no" class="col-sm-2 control-label">Contact No<span--}}
                                                        {{--style="color: red">*</span></label>--}}

                                            {{--<div class="col-sm-10">--}}
                                                {{--<input type="text" class="form-control" id="contact_no"--}}
                                                       {{--name="contact_no" value="<?php echo isset($history->contact_no)?$history->contact_no:''; ?>"--}}
                                                       {{--placeholder="Contact No" maxlength="100" required>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}

                                        {{--<div class="form-group">--}}
                                            {{--<label for="cnic_no" class="col-sm-2 control-label">CNIC No--}}
                                                {{--From--}}
                                                {{--<span style="color: red">*</span>--}}
                                            {{--</label>--}}
                                            {{--<div class="col-sm-10">--}}
                                                {{--<input type="text" class="form-control pull-right"--}}
                                                       {{--id="cnic_no"  name="cnic_no" value="<?php echo isset($history->cnic_no)?$history->cnic_no:''; ?>"--}}
                                                       {{--placeholder="CNIC No"--}}
                                                       {{--required>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}

                                        {{--<div class="form-group">--}}
                                            {{--<label for="address" class="col-sm-2 control-label">Address--}}
                                                {{--<span style="color: red">*</span>--}}
                                            {{--</label>--}}
                                            {{--<div class="col-sm-10">--}}
                                                {{--<input type="text" class="form-control pull-right"--}}
                                                       {{--id="address"  name="address"  value="<?php echo isset($history->address)?$history->address:''; ?>"--}}
                                                       {{--placeholder="Reference Address" required>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}

                                        {{--<div class="form-group">--}}
                                            {{--<label for="relationship" class="col-sm-2 control-label">Relationship<span--}}
                                                        {{--style="color: red">*</span></label>--}}

                                            {{--<div class="col-sm-10">--}}
                                                {{--<input type="text" class="form-control" id="relationship" name="relationship" value="<?php echo isset($history->relationship)?$history->relationship:''; ?>"--}}
                                                       {{--placeholder="Relationship" maxlength="11" required>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /personal information -->


                        <div class="box-footer">
                            <a href="{{url('admin/teachers')}}" class="btn btn-warning pull-right">
                                <i class="fa fa-w fa-remove"></i> Cancel
                            </a>
                            <?php if($status != 'add'): ?>
                            <button type="submit" class="btn btn-primary pull-right" value="save" name="save" style="margin-right:5px;"><i class="fa fa-fw fa-save"></i> Update</button>
                            <?php else: ?>
                            <button type="submit" class="btn btn-primary pull-right" value="saveadd" name="saveadd" style="margin-right:5px;"><i class="fa fa-fw fa-save" ></i> Save & Add</button>
                            <button type="submit" class="btn btn-primary pull-right" value="save" name="save" style="margin-right:5px;"><i class="fa fa-fw fa-save" ></i> Save</button>
                            <?php endif; ?>

                        </div>
                        <!-- /.box-footer -->
                    </form>
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
        $('#datepicker1').datepicker({
            autoclose: true
        });
    </script>
@endsection

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
            <h1 class="box-title">Teachers List</h1>
        </div>
    </a>
    <div class="box-body" style="padding: 0px;">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">

                        <form class="pull-right form-group" method="post" action="{{ url('admin/tuition/global') }}" id="globalTuitions">

                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="ids" id="ids" value="">
                            <button type="button" class="btn btn-primary pull-right teacherbb-broadcast-selected" >
                                <i class="fa fa-fw fa-volume-up"></i>Add to BroadCast List</button>&nbsp;&nbsp;

                        </form>

                        <form class="pull-right form-group broadcast_phone_numbers" method="post" action="{{ url('admin/teacher/phone/broadcast') }}" id="broadcast_phone_numbers">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="teacher_id[]" id="teacher_id_pphone" value="">
                            <input type="hidden" name="broadcast_contact_no" id="broadcast_contact_no" value="<?php echo isset($contactNumbers)? $contactNumbers:''; ?>">
                            <button type="button" class="btn btn-primary broadcast_phone_numbers" ><i class="fa fa-fw fa-phone-square"></i>Get Phone Numbers</button>&nbsp;&nbsp;
                        </form>

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

                        <div class="box-body" style="margin-top: -30px;">
                            <input type="checkbox" class="minimal select_all" name="select_all" id="select_all" value="">
                            Select All
                            <table id="example1" class="table table-bordered table-striped" cellspacing="0" width="100%">

                                <thead>
                                    <tr style="background-color: #3c8dbc; color: #fefefe;">
                                        <th>Name</th>
                                        <th>Photo</th>
                                        <th>Band</th>
                                        <th>Age</th>
                                        <th>Experience</th>

                                    </tr>
                                </thead>

                                <tbody>
                                <?php foreach($teachers as $t): ?>
                                <tr>

                                    <td>
                                        <div id="element1">
                                            <input type="checkbox" class="minimal" name="globalTeachers[]" value="<?php echo $t->teacher_id; ?>">
                                        </div>
                                        <div id="element2">
                                            <div>
                                                {!! Form::open(array('url'=>'admin/teachers','method'=>'POST', 'id'=>'myform')) !!}
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="hidden" name="teacher_id" value="<?php echo $t->teacher_id;  ?>">
                                                    <input type="hidden" name="email" value="<?php echo $t->email;  ?>">
                                                    <button type="submit" class="btn btn-link"><?php echo $t->fullname ; ?></button>
                                                {!! Form::close() !!}
                                            </div>
                                            <div>
                                                <span class="mobile_number"><a href="tel:<?php echo $t->mobile1 ?>"><?php echo $t->mobile1 ?></a></span>
                                            </div>
                                        </div>

                                    </td>
                                    <td>
                                        <div id="element1">

                                            <?php if(isset($t->teacher_photo)): ?>
                                            <a href="#" class="teacher_photo" title="profile Pic"
                                               id="{{URL::asset("/local/teachers/$t->teacher_id/photo/$t->teacher_photo")}}">
                                                <img src="{{URL::asset("/local/teachers/$t->teacher_id/photo/$t->teacher_photo")}}"
                                                     alt="profile Pic" class="img-circle teacher-photo">
                                            </a>
                                            <?php endif; ?>

                                        </div>
                                        <div id="element2">
                                            <a href="#" class="volume global-list" title="Add to Global Teachers List" id="<?php echo $t->teacher_id; ?>">
                                                <i class="fa fa-fw fa-volume-up"></i>
                                            </a>
                                        </div>
                                    </td>
                                    <td><?php echo $t->band_name;  ?></td>
                                    <td><?php echo $t->agey; ?></td>
                                    <td><?php echo $t->experience; ?></td>
                                </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="box-footer" style="padding: 0px;">
                                <div style="display:inline-block;">
                                    <select class="form-control" id="page_size" name="page_size" style="width: 110px;">
                                        <option value="50"<?php if(isset($pagesize) && $pagesize==50) echo "selected"; ?>>50</option>
                                        <option value="100"<?php if(isset($pagesize) && $pagesize==100) echo "selected"; ?>>100</option>
                                        <option value="150"<?php if(isset($pagesize) && $pagesize==150) echo "selected"; ?>>150</option>
                                        <option value="200"<?php if(isset($pagesize) && $pagesize==200) echo "selected"; ?>>200</option>
                                    </select>
                                </div>
                                <?php echo $teachers->render(); ?>
                                <div class="" style="display:inline-block;">Showing
                                    <?php echo isset($offset) ? $offset:''; ?> to
                                    <?php echo isset($perpage_record) ? $perpage_record:''; ?> of
                                    <?php echo $count; ?> entries
                                </div>
                            </div>
                            <!-- /.box-body -->
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.partials.modal')

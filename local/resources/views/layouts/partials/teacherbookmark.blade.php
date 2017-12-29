<div class="row">
    <div class="col-xs-12">
        <div class="box" style="border-top: none;">
            <div class="box-header teacher_bookmark"> </div>
            <!-- /.box-header -->
            <div class="box-body">

                <div>
                    <small class="label pull-left bg-blue"><input type="checkbox" name="selectall" id="selectall"></small>
                    <strong style="margin-left: 10px;">Select All</strong>
                    <form class="pull-right form-group phone_numbers" method="post" action="{{ url('admin/teacher/phone/broadcast') }}" id="phone_numbers">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="teacher_id[]" id="teacher_ids" value="">
                        <button type="submit" class="btn btn-primary" ><i class="fa fa-fw fa-phone-square"></i>Get Phone Numbers</button>&nbsp;&nbsp;
                    </form>
                </div>


                <table id="teacher_bookmark_list" class="display table-striped responsive nowrap" cellspacing="0" width="100%">
                    <thead>
                    <tr style="background-color: #3c8dbc; color: #fefefe;">
                        <th>Name </th>
                        <th>Photo</th>
                        <th>Band</th>
                        <th>Age</th>
                        <th>Experience</th>
                        <th>Labels</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>


            </div>
            <!-- /.box-body -->
        </div>
    </div>
</div>
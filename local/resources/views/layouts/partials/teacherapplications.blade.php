<div class="row">
    <div class="col-xs-12">
        <div class="box" style="border-top: none;">
            <div class="box-header teacher_applications_list"> </div>
            <!-- /.box-header -->
            <div class="box-body">

                <div>
                    <small class="label pull-left bg-blue"><input type="checkbox" name="selectall" id="selectall2"></small>
                    <strong style="margin-left: 10px;">Select All</strong>
                    <form class="pull-right form-group phone_numbers" method="post" action="{{ url('admin/teacher/phone/broadcast') }}" id="phone_numbers2">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="teacher_id[]" id="teacher_id2" value = "">
                        <button type="submit" class="btn btn-primary" ><i class="fa fa-fw fa-phone-square"></i>Get Phone Numbers</button>
                    </form>

                    <form class="pull-right form-group phone_numbers" method="post" action="{{ url('admin/teacher/application/status') }}" id="application_status">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="application_id[]" id="application_id2" value = "">
                        <input type="hidden" name="currentTuitionId" id="currentTuitionId" value = "">
                        <button type="submit" class="btn btn-warning" style="margin-right: 10px;" >
                            <i class="fa fa-fw fa-bullhorn"></i>Change Application Status</button>
                    </form>

                </div>

                <table id="teacher_applications_listing" class="display table-striped responsive nowrap" cellspacing="0" width="100%">
                    <thead>
                    <tr style="background-color: #3c8dbc; color: #fefefe;">
                        <th>Name</th>
                        <th>Photo</th>
                        <th>Status</th>
                        <th>Applied Date</th>
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
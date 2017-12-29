<div class="box box-success direct-chat direct-chat-success">
    <!-- /.box-header -->
    <div class="box-header with-border">
        <h3 class="box-title">If you not found any required information related to grade or subject please inform us. </h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body"></div>
    <!-- /.box-body -->
    <div class="box-footer">
        <form action="{{ url('admin/send/email') }}" method="post" id="adminEmailForm">
            <input type="hidden" name="subject" class="form-control" value="New grade or subject required to be entered in system">
            <input type="hidden" name="gradeSelected" id="gradeSelected" class="form-control" value="">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
                <textarea class="form-control" name="body" required  rows="3" placeholder="Write Message ..."></textarea>
            </div>
            <button type="submit" class="btn btn-success btn-flat pull-right"><i class="fa fa-fw fa-envelope"></i>
                Send Email</button>
        </form>
    </div>
    <!-- /.box-footer-->
</div>
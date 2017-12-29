<div class="box box-primary">
    <a href="#">
        <div class="box-header with-border" data-widget="">
            <!--<i class="fa fa-minus pull-right" style="font-size:12px;  margin-top: 5px;"></i>-->
            <h1 class="box-title">Experience List</h1>
        </div>
    </a>

    <div class="box-body" style="padding: 0px;">

        <div class="row">
            <div class="col-xs-12">
                <div class="box" style="border-top: none;">

                    <div class="box-header">
                        <a href="{{'teacher/experiences'}}" class="btn btn-primary pull-right">
                            <i class="fa fa-plus-circle fa-lg"></i> Add New
                        </a>

                    </div>
                       <!-- /.box-header -->
                        <div class="box-body">

                            <table class="table table-hover" id="qualification">
                                <tbody>
                                <tr style="background-color: #3c8dbc; color: #fefefe;">
                                    <th>Experience Detail</th>
                                    <th>Documents</th>
                                    <th>Actions</th>
                                </tr>
                                <?php if(!empty($experiences)): ?>
                                <?php foreach($experiences as $e): ?>
                                <tr>
                                    <td>
                                        <a type="button" class="btn btn-secondary" data-toggle="tooltip"
                                                data-placement="bottom" title="<?php echo $e->experience; ?>">
                                            <?php echo   str_limit($e->experience, $limit = 80, $end = '...') ?>
                                        </a>
                                    </td>
                                    <td>

                                        <div class="pull-left">
                                            <form class="pull-right form-group" method="post" action="{{ url('download') }}"
                                                  id="qualification_docs">

                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="path" id="path"
                                                       value="<?php echo base_path()."/teachers/".$e->teacher_id."/experience/$e->experience_document"; ?>" >
                                                <input type="hidden" name="filename" id="filename" value="<?php echo $e->experience_document; ?>">

                                                <button type="submit" class="btn btn-link " >
                                                    <?php echo $e->experience_document; ?></button>

                                            </form>
                                        </div>
                                    </td>
                                    <td>
                                        <a class="btn  edit-btn" href="experience/update/<?php echo $e->id; ?>"
                                           title="Edit"
                                           style="padding: 0 0;">
                                        <span class="label label-success">
                                            <i class="fa fa-fw fa-edit" style="font-size: 10px;"></i>
                                        </span>
                                        </a>
                                        <a class="btn  del-btn"
                                           href="experience/delete/<?php echo $e->id . "-front"; ?>" title="Delete"
                                           style="padding: 0 0;" onclick="return ConfirmDelete();">
                                            <span class="label label-danger">
                                                <i class="fa fa-fw fa-trash-o" style="font-size: 10px;"></i>
                                            </span>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php else: ?>
                                <tr>
                                    <td class="child" colspan="7" style="text-align: center;"><strong>No Record</strong>
                                    </td>
                                </tr>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.box-body -->
                </div>
            </div>
        </div>
    </div>
</div>

@section('page_specific_inline_scripts')
    @if (session('status'))
        <script>
            jQuery(document).ready(function ($) {
                toastr.success('Expereince Save Successfully!');
            });
        </script>
    @endif
    @if (session('deleted'))
        <script>
            jQuery(document).ready(function ($) {
                toastr.success('Expereince Deleted Successfully!');
            });
        </script>
    @endif
    <script>
        $(document).ready(function () {
            $(".alert").fadeOut(3000);
        });

        function ConfirmDelete() {
            return confirm("Are you sure to delete this item!");
        }
    </script>
@endsection
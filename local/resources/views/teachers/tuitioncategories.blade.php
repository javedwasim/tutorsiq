<div class="box box-primary">
    <a href="#">
        <div class="box-header with-border" data-widget="collapse">
            <i class="fa fa-minus pull-right" style="font-size:12px;
        margin-top: 5px;"></i>

            <h1 class="box-title">Tuition Categories List</h1>
        </div>
    </a>

    <div class="box-body" style="padding: 0px;">

        <div class="row">
            <div class="col-xs-12">
                <div class="box" style="border-top: none;">

                    <div class="box-header">
                        <a href="{{'teacher/categories'}}" class="btn btn-primary pull-right">
                            <i class="fa fa-plus-circle fa-lg"></i> Add New
                        </a>

                    </div>
                    @if (session('status'))
                        <div class="alert alert-success">
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

                            <table class="table table-hover" id="qualification">
                                <tbody>
                                <tr style="background-color: #3c8dbc; color: #fefefe;">
                                    <th>Name</th>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                </tr>
                                <?php if(!empty($categories)): ?>
                                <?php foreach($categories as $e): ?>
                                <tr>
                                    <td><?php echo $e->name; ?></td>
                                    <td>&nbsp;</td>

                                    <td style="text-align: right">

                                        <a class="btn  del-btn"
                                           href="categories/delete/<?php echo $e->id; ?>" title="Delete"
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
                                    <td class="child" colspan="3" style="text-align: center;"><strong>No Record</strong>
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
    <script>

        $(document).ready(function () {
            $(".alert").fadeOut(6000);
        });
        function ConfirmDelete() {
            return confirm("Are you sure to delete this item!");
        }
    </script>
@endsection
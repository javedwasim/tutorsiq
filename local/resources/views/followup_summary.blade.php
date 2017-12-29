<div class="row">
    <div class="col-md-12">
        <div class="box box-default">
            <div class="box-header with-border">
                <i class="fa fa-book"></i>

                <h3 class="box-title">Share Summary</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <?php foreach ($shareSummary as $summary): ?>
                <div class="alert alert-info alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>

                    <h4>Contact Person: <?php echo $summary->contact_person; ?></h4>
                    <h4>Location: <?php echo $summary->locations; ?></h4>
                    <h4>Share Amount: <?php echo $summary->pShare; ?></h4>

                </div>
                <?php endforeach; ?>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
</div>





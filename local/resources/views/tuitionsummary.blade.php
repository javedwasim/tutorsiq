@section('page_specific_styles')
<link rel="stylesheet" href="{{ asset('css/AdminLTE.css') }}">
@endsection

<div class="row">
    <div class="col-xs-12">
        <div class="modal-header">
            <h4 class="box-title" id="shareSummary" style="font-weight: bold;">Share Summary(
                <?php echo $academyTuitions." Tuitions "; ?>)</h4>

        </div>
        <!-- /.col -->
        <div class="col-xs-4">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-fw fa-user"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text shareSummary">Agent One Share</span>
                    <span class="info-box-number shareAmount"><?php echo number_format($agentOneShare,0); ?></span>
                </div>
            </div>
        </div>

        <div class="col-xs-4">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-fw fa-user"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text shareSummary">Agent Two Share</span>
                    <span class="info-box-number shareAmount" ><?php echo number_format($agentTwoShare,0); ?></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>

        <div class="col-xs-4">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-fw fa-bank"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text shareSummary">Academy Share</span>
                    <span class="info-box-number shareAmount"><?php echo number_format($academyShare,0); ?></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>


    </div>
</div>

<div class="row">
    <div class="col-xs-12">

        <div class="box">
            <div class="box-header">
                <h3 class="box-title" style="font-weight: bold;">Partners Share</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
                <table class="table table-striped">
                    <tbody><tr>
                        <th style="width: 10px">#</th>
                        <th>Referred By</th>
                        <th># of Tuitions</th>
                        <th style="width: 40px">Amount</th>
                    </tr>
                    <?php $count=1; foreach ($partnersShare as $partner):
                            if($partner->partnerCount>0):
                    ?>
                        <tr>
                            <td><?php echo $count; ?></td>
                            <td><?php echo $partner->name; ?></td>
                            <td><?php echo $partner->partnerCount; ?></td>
                            <td><span class="badge bg-red"><?php echo number_format($partner->partnerShare,0); ?></span></td>
                        </tr>
                    <?php $count++; endif; endforeach; ?>

                    </tbody></table>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
</div>

@include('layouts.partials.htmlheader')
<div class="row">
    <div class="col-md-12">
        <div class="box box-default" style="border: none;">

            <div class="box-header with-border text-light-blue">
                <h3 class="box-title">About MySelf</h3>
                <!-- /.box-tools -->
            </div>

            <div class="box-body">

                <ul class="nav nav-stacked">
                    <?php if(isset($about_us)): ?>
                        <?php foreach($about_us as $aboutus): ?>
                        <li><a href="#"><?php echo $aboutus; ?></a></li>
                    <?php endforeach; endif; ?>
                </ul>

            </div>

            <div class="box-header with-border text-light-blue">
                <h3 class="box-title">Past Experience</h3>
                <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">

                <ul class="nav nav-stacked">
                    <?php if(isset($experiences)): ?>
                        <?php foreach($experiences as $experience): ?>
                        <li><a href="#"><?php echo $experience; ?></a></li>
                    <?php endforeach; endif; ?>

                </ul>

            </div>
            <div class="box-header with-border text-light-blue">
                <h3 class="box-title">Preferred Institutes</h3>
                <!-- /.box-tools -->
            </div>

            <div class="box-body">
                <ul class="nav nav-stacked">
                    <li><a href="#"><?php echo $institute_list;   ?></a></li>
                </ul>

            </div>

            <div class="box-header with-border text-light-blue">
                <h3 class="box-title">Zone+Locations</h3>
                <!-- /.box-tools -->
            </div>

            <div class="box-body">
                <ul class="nav nav-stacked">
                    <?php foreach ($locations as $location):?>
                        <li><a href="#"><?php echo $location->zone_locations; ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>

        </div>
        <!-- /.box -->
    </div>

</div>

<div class="box-body">


    <div class="box-header with-border">
        <h1 class="box-title">Tuitions List</h1>
    </div>

    <?php if(!empty($tuitions)): ?>
    <div class="row">
        <?php $rowcount=0; foreach($tuitions as $tuition): $rowcount++;  ?>
        <div class="col-md-4">
            <div class="box box-warning">

                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo $tuition->tuition_code; ?></h3>
                </div>

                <div class="box-footer no-padding">
                    <ul class="nav nav-stacked">

                        <li style="padding: 0px 0 30px 0;"><a href="#">&nbsp;<span
                                        class="pull-left  bg-gray"><?php echo $tuition->subjects; ?></span></a>
                        </li>
                        <li><a href="#">&nbsp;<span
                                        class="pull-left  bg-yellow"><?php echo $tuition->locations; ?></span></a>
                        </li>
                        <li><a href="#">&nbsp;<span
                                        class="pull-left text"><?php echo $tuition->special_notes; ?></span></a>
                        </li>

                        <li>
                            <form class="form-horizontal viewdetail" method="post" action="{{ url('/viewdetail') }}"
                                  enctype="multipart/form-data">

                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="tuition_id" value="<?php echo $tuition->id; ?>">
                                <input type="hidden" name="teacher_id" value="<?php echo $teacher_id; ?>">
                                <input type="hidden" name="class_name" value="<?php echo $tuition->class_name; ?>">
                                <input type="hidden" name="subject_name" value="<?php echo $tuition->subjects; ?>">
                                <input type="hidden" name="location" value="<?php echo $tuition->locations; ?>">
                                <input type="hidden" name="special_notes"
                                       value="<?php echo $tuition->special_notes; ?>">


                                <span class="pull-left text">
                                            <button type="submit" class="btn bg-navy margin">View Detail</button>
                                        </span>
                            </form>

                        </li>

                    </ul>

                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
         <?php if($rowcount%3==0) echo '</div><div class="row">'; ?>
        <?php endforeach; ?>
    </div>
                   <!-- /.box-body -->

    <?php else: ?>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-warning">
                <div class="box-header with-border" style="text-align: center;">
                    <h3 class="box-title">No Record Found.</h3>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <div class="box-footer">

        <?php echo $tuitions->render(); ?>
        <div class="" style="display:inline-block;">Showing
            <?php echo isset($offset) ? $offset : ''; ?> to
            <?php echo isset($perpage_record) ? $perpage_record : ''; ?> of
            <?php echo $count; ?> entries
        </div>
    </div>
</div>

@include('layouts.partials.modal')
<!-- /.box -->

<!-- Follow up filter -->
<div class="row">

    <?php $count = 0;  foreach ($tuitions as $tuition): $count++; ?>
    <div class="col-md-4 followup-box-size">
        <div class="panel box box-primary">
            <div class="panel-heading">
                <!-- Ttuition Code and edit and quick edit -->
                <div id="element1">

                    <div id="element1">
                        {!! Form::open(array('url'=>'admin/tuitions','method'=>'POST',
			                'id'=>'assignedTeacher','target'=>'_blank')) !!}

                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="tuition_date" value="custom">
                            <input type="hidden" name="tuition_code" value="<?php echo $tuition->tuition_code;?>">
                            <input type="hidden" id="start_date" name="start_date"value="<?php echo isset($tuition_start_date)?$tuition_start_date:''; ?>">
                            <input type="hidden" id="end_date" name="end_date"value="<?php echo isset($tuition_end_date)?$tuition_end_date:''; ?>">
                            <button type="submit" class="btn btn-link button-padding">
                                <?php echo $tuition->tuition_code;?></button>

                        {!! Form::close() !!}
                    </div>
                    <div id="element2">
                        <a class="btn short-view1" title="Short View" id="<?php echo $tuition->tid;  ?>"
                           style="padding: 0 0;">
                              <span>
                                  <i class="fa fa-fw fa-eye"
                                     style="font-size: 15px; color: black;"></i>
                              </span>
                        </a>

                        <a href="#" class="btn edit-btn quickEdit" id="<?php echo $tuition->tid;?>"
                           style="padding: 0 0;" title="Quick Edit">
                                <span>
                                    <i class="fa fa-fw fa-file-text-o"
                                       style="font-size: 14px; color: black;"></i>
                                </span>
                        </a>
                        <a class="btn edit-btn" href="update/<?php echo $tuition->tid; ?>" title="Edit Tuition"
                           style="padding: 0 0;" target="_blank">
                                <span>
                                    <i class="fa fa-fw fa-edit"
                                       style="font-size: 16px; color: black;"></i>
                                </span>
                        </a>
                        {{--Star Button--}}
                        <input type="hidden" name="is_started-<?php echo $tuition->tid ?>"
                               id="is_started-<?php echo $tuition->tid ?>"
                               value="<?php echo $tuition->is_started; ?>">
                        <a class="btn edit-btn tuition-started" id="<?php echo $tuition->tid; ?>"
                           title="<?php echo ($tuition->is_started == 1) ? 'Started' : 'Not Started'; ?>" style="padding: 0 0;">
                            <span>
                                <i id="<?php echo 'star-'.$tuition->tid  ?>"
                                   class="started-star <?php if ($tuition->is_started == 1)
                                   {echo "fa fa-star";} else{echo "fa fa-star-o";}?>"></i>
                            </span>
                        </a>
                        {{--Star Button End--}}
                    </div>

                </div>

                {{--Followup Select All--}}
                <div id="element1" style="float: right; margin-right: 0px; margin-bottom: 5px;">
                    <input type="checkbox" class="minimal1 followup-selected-count" name="tuitionFollowups[]" value="<?php echo $tuition->tid; ?>">
                </div>
                {{--Followup Select All End--}}

                <!-- Ttuition Code and edit and quick edit -->
                <div id="element2 followup-filter">

                    <select class="form-control select2 tuition_status_p" id="tuition_status_p" name="tuition_status_p"
                          data-placeholder="Select a Status">
                        <option value=""></option>
                        <?php foreach ($tuitionStatus_result as $status): ?>
                        <option value="<?php echo $status->id . "|" . $tuition->tid . "|" . $status->color; ?>"
                        <?php if (isset($tuition->tuition_status_id) && ($tuition->tuition_status_id == $status->id)) echo "selected";?> >
                            <?php echo $status->name; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

            </div>
            <div class="panel-body">
                <!-- List group -->
                <ul class="list-group">

                    <?php if (!empty($tuition->contact_person)): ?>
                        <li class="list-group-item">
                            <?php
                            echo '<b>'."Contact Person: ".'</b>' .$tuition->contact_person;
                            echo ", <a href='tel:$tuition->contact_no' >$tuition->contact_no</a>";
                            echo ", <a href='tel:$tuition->contact_no' >$tuition->contact_no2</a>";
                            ?>
                        </li>
                    <?php endif; ?>

                    <?php if (!empty($tuition->subjects)): ?>
                    <li class="list-group-item">
                        <div data-toggle="tooltip" title="<?php echo $tuition->subjects; ?>">
                            <?php echo '<b>'."Subject + Grade: ".'</b>'.str_limit($tuition->subjects, $limit = 40, $end = '...') ?>
                        </div>
                    </li>
                    <?php endif; ?>

                    <?php if (!empty($tuition->label_name)): ?>
                    <li class="list-group-item">
                        <div data-toggle="tooltip" title="<?php echo str_replace('-', ', ', $tuition->label_name); ?>">
                            <?php echo '<b>'."Labels: ".'</b>'.str_replace('-', ', ', str_limit($tuition->label_name, $limit = 40, $end = '...')) ?>
                        </div>
                    </li>
                    <?php endif; ?>

                    <?php if (!empty($tuition->locations)): ?>
                    <li class="list-group-item"><?php echo '<b>'."Location: " .'</b>'.$tuition->locations; ?></li>
                    <?php endif; ?>

                    <?php if (!empty($tuition->address)): ?>
                    <li class="list-group-item">
                        <div data-toggle="tooltip" title="<?php echo $tuition->address; ?>">
                            <?php echo '<b>'."Address: ".'</b>'.str_limit($tuition->address, $limit = 40, $end = '...') ?>
                        </div>
                    </li>
                    <?php endif; ?>

                    <?php if (!empty($tuition->tuition_final_fee)): ?>
                    <li class="list-group-item"><?php echo '<b>'."Final Fee: ".'</b>'.$tuition->tuition_final_fee ?></li>
                    <?php endif; ?>

                    <?php if (!empty($tuition->tuition_start_date)): ?>
                    <li class="list-group-item"><?php echo '<b>'."Start Date: ".'</b>'.$tuition->tuition_start_date; ?></li>
                    <?php endif; ?>

                    <?php if (!empty($tuition->tuition_date)): ?>
                    <li class="list-group-item"><?php echo '<b>'."Tuition Date: ".'</b>'.$tuition->tuition_date; ?></li>
                    <?php endif; ?>

                    <?php if (!empty($tuition->no_of_students)): ?>
                    <li class="list-group-item"><?php echo '<b>'."No of students: ".'</b>'. $tuition->no_of_students; ?></li>
                    <?php endif; ?>

                    <?php if (!empty($tuition->created_by)): ?>
                        <li class="list-group-item"><?php echo $tuition->created_by=='admin'? "<b>Made by:</b> Admin":''; ?></li>
                    <?php endif; ?>

                    <?php if (!empty($tuition->name)): ?>
                        <li class="list-group-item"><?php echo '<b>'."Referred By: ".'</b>'. $tuition->name; ?></li>
                    <?php endif; ?>

                </ul>
            </div>
        </div>

        <?php  $assignedTeachers = \App\Http\Controllers\TuitionDetails::AssignedTeachers($tuition->tid); ?>
        <!-- Teacher Info -->
        <div class="panel box box-warning" style="margin-top: -19px;">
            <div class="panel-heading">
                <a data-toggle="collapse" data-parent="#accordion" href="#<?php echo !empty($assignedTeachers)?
                    $tuition->tuition_code:'';?>">
                    <h3 class="panel-title"><?php echo !empty($assignedTeachers)?'Teacher Info':'No Teacher Assigned'; ?> </h3>
                </a>
            </div>

            <div class="panel-body collapse <?php echo !empty($assignedTeachers)?'in':''; ?>" id="<?php echo $tuition->tuition_code;?>">
                <!-- List group -->


                    <ul class="list-group">

                        <?php foreach ($assignedTeachers as $t):?>

                        <li class="list-group-item">
                            <div id="element1">
                                {!! Form::open(array('url'=>'admin/teachers','method'=>'POST',
                                    'id'=>'assignedTeacher','target'=>'_blank')) !!}
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="teacher_id" value="<?php echo $t->teacherid;  ?>">
                                <input type="hidden" name="email" value="<?php echo $t->email;  ?>">
                                <button type="submit" class="btn btn-link button-padding">
                                    <?php echo $t->fullname; ?></button>
                                {!! Form::close() !!}
                                <?php echo "<br>" . "<a href='tel:$t->mobile1' >$t->mobile1</a>"; ?>
                                <?php echo "<br>" . "<a href='tel:$t->mobile2' >$t->mobile2</a>"; ?>
                            </div>

                            <div id="element2" style="float: right;">
                                <img src="{{URL::asset("/local/teachers/$t->teacherid/photo/$t->teacher_photo")}}"
                                     alt="profile Pic" class="img-circle teacher-photo">
                            </div>
                        </li>

                        <?php endforeach; ?>
                    </ul>


            </div>
        </div>
        <!-- Teacher Info -->

    </div>
    <?php if ($count % 3 == 0) echo '</div><div class="row">'; ?>
    <?php endforeach; ?>
</div>
<div class="row">
    <div class="col-md-12">
        <?php echo $tuitions->render(); ?>
        <div class="" style="float: right; margin: 10px 10px;">Showing
            <?php echo isset($result['offset']) ? $result['offset'] : ''; ?> to
            <?php echo isset($result['perpage_record']) ? $result['perpage_record'] : ''; ?> of
            <?php echo $result['count']; ?> entries
        </div>
    </div>
</div>


<div class="modal fade" id="updateFollowupTuitions" role="dialog">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header popup-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Selected Tuition Status</h5>

            </div>
            <form action="{{ url('admin/tuitions/update/status') }}" method="post" id="tuitionStatusForm">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="modal-body">
                    <input type="hidden" name="tids" id="tidss" value="">
                    <div class="form-group">
                        <label for="recipient-name" class="form-control-label">Tuition Status:</label>
                        <select class="form-control select2"   name="tuitioStatus"
                              data-placeholder="Select Status">
                            <option value=""></option>
                            <?php foreach($assign_status as $status) { ?>
                            <option value="<?php echo $status->id; ?>" ><?php echo $status->name; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>

            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="updateFollowupTuitionsStatus" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header popup-header">
                <h5 class="modal-title" id="exampleModalLabel">Change Selected Tuition Labels</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- remove previous labels and add new lables -->
            <form action="{{ url('admin/bulk/add/followuplabels') }}" method="post" id="tuitionLabelsForm">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="modal-body">
                    <input type="hidden" name="lids" id="lidss" value="">
                    <div class="form-group">
                        <label for="recipient-name" class="form-control-label">Add New Labels(Delete previous  and add newely selected labels to  tuitions):</label>
                        <select class="form-control select2" multiple="multiple" id="bulkLabels" name="bulkLabels[]"
                                data-placeholder="Select Labels"  required>
                            <?php    foreach($labels as $label){ ?>
                            <option value="<?php echo $label->id; ?>" >
                                <?php echo $label->name; ?>
                            </option>
                            <?php } ?>

                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning"><i class="fa fa-fw fa-plus-circle"></i>Add Labels</button>
                </div>
            </form>
            <!-- remove previous labels and add new lables -->
            <hr/>

            <!-- append new labels to selected tuitions -->
            <form action="{{ url('admin/bulk/add/followuplabels') }}" method="post" id="tuitionLabelsFormhhhhh">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="appendLabels" id="appendLabels" value="AppendLabels">
                <div class="modal-body">
                    <input type="hidden" class="appendLabels" name="lids" id="updatelidss" value="">
                    <div class="form-group">
                        <label for="recipient-name" class="form-control-label">Update Labels(Append selected labels to tuitions):</label>
                        <select class="form-control select2" multiple="multiple" id="bulkLabels" name="bulkLabels[]"
                                data-placeholder="Select Labels" required>
                            <?php    foreach($labels as $label){ ?>
                            <option value="<?php echo $label->id; ?>" >
                                <?php echo $label->name; ?>
                            </option>
                            <?php } ?>

                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary pull-left" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-fw fa-save"></i>Save Labels</button>
                </div>
            </form>
            <!-- append new labels to selected tuitions -->
        </div>
    </div>
</div>


<div class="modal fade" id="starunstar" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header popup-header">
                <h5 class="modal-title" id="exampleModalLabel">Change Selected Tuition Labels</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- remove previous labels and add new lables -->
            <form action="{{ url('admin/tuition/starsave') }}" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="modal-body">
                    <input type="hidden" id="starid"  name = "starid" value="" >
                    <div class="form-group">
                        <label for="recipient-name" class="form-control-label">Select Star/UnStar Status</label>
                        <select class="form-control select2"  id="starunstar" name="starunstar"
                                data-placeholder="Select Star Status"  required>
                           <option value=""></option>
                           <option value="1">Star</option>
                           <option value="0">UnStar</option>

                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-fw fa-plus-circle"></i>Star/UnStar</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="followupSummary" role="dialog">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header popup-header">
                <h5 class="modal-title" id="exampleModalLabel">Partner Share</h5>

            </div>
            <form action="{{ url('admin/tuitions/followup/summary') }}" method="post" id="shareSummary">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="summaryids" id="fsummaryids" value="">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="recipient-name" class="form-control-label">Enter Partner Share</label>
                        <input type="text" class="form-control" name="partnerShare" id="partnerShare" value="" placeholder="Enter Partner Share i.e. 50">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Summary</button>
                </div>

            </form>
        </div>
    </div>
</div>

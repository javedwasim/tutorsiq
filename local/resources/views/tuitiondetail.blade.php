@extends('layouts.app')

@section('htmlheader_title')
    @role('teacher')
    Teahcer Registeration
    @endrole

    <?php if($status == 'update'): ?>
    {{ trans('Admin | Tuition | Update') }}
    <?php else: ?>
    {{ trans('Admin | Tuition | Add') }}
    <?php endif; ?>

@endsection

@section('contentheader_title')
    <?php if($status == 'update'): ?>
    {{ trans('Update Tuition') }}
    <?php else: ?>
    {{ trans('Add New Tuition') }}
    <?php endif; ?>
@endsection

@section('main-content')
    <div class="spark-screen">
        <div class="row">
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-info">
                    <!-- form start -->
                    <form class="form-horizontal" id="tuition" method="post" action="{{ url('admin/tuition/detail') }}"
                          enctype="multipart/form-data">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif


                        <input type="hidden" name="status" id="status"
                               value="<?php echo isset($status) ? $status : '' ?>">
                        <input type="hidden" name="id" id="id" value="<?php echo isset($id) ? $id : '' ?>">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="submitbtnValue" id="submitbtnValue" value="">


                        <div id="accordion" role="tablist" aria-multiselectable="true">
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingOne">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#personalinfo"
                                           aria-expanded="true" aria-controls="personalinfo">
                                            Tuitions
                                        </a>
                                    </h4>
                                </div>
                                <div id="personalinfo" class="panel-collapse collapse in" role="tabpanel"
                                     aria-labelledby="headingOne">
                                    <div class="box-body">

                                        <!-- Tuition Status-Code -->
                                        <div class="form-group">
                                            <!-- Tuition Status -->
                                            <label for="tuition_status_id" class="col-sm-2 control-label">Tuition
                                                Status</label>
                                            <div class="col-sm-4">

                                                <select name="tuition_status_id" id="tuition_status_id"
                                                        class="form-control select2"
                                                        data-placeholder="Select Status">
                                                    <option value="">Select Status</option>
                                                    <?php foreach($assign_status as $assign): ?>
                                                    <option value="<?php echo $assign->id ?>"<?php if (isset($tuition->tuition_status_id) && $tuition->tuition_status_id == $assign->id) echo 'selected'; ?>>
                                                        <?php echo $assign->name ?>
                                                    </option>
                                                    <?php endforeach; ?>
                                                </select>

                                            </div>
                                            <!-- Tuition Status -->

                                            <!-- Tuition Code -->
                                            <label for="tuition_code" class="col-sm-2 control-label">Tuition
                                                Code</label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control " id="tuition_code"
                                                       name="tuition_code"
                                                       placeholder="Tuition Code" maxlength="100"
                                                       value="<?php echo isset($latest_code) ? $latest_code : $tuition->tuition_code; ?>"
                                                       readonly/>
                                            </div>
                                            <!-- form-group -->

                                        </div>
                                        <!-- Tuition Status-Code -->

                                        <!-- Take Note -->
                                        <div class="form-group">
                                            <label for="take_note" class="col-sm-2 control-label">Take Note</label>
                                            <div class="col-sm-10">
                                                <textarea class="form-control" rows="3" id="take_note"
                                                          name="take_note"><?php echo isset($tuition->take_note) ? $tuition->take_note : ''; ?></textarea>
                                            </div>
                                        </div>
                                        <!-- Take Note -->

                                        <!-- Tuition-Date Start Date -->
                                        <div class="form-group">

                                            <!-- Tuition Date -->
                                            <label for="datepicker" class="col-sm-2 control-label">Tuition Date</label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" id="datepicker"
                                                       name="tuition_date"
                                                       value="<?php echo isset($tuition->tuition_date) ? date('d/m/Y', strtotime($tuition->tuition_date)) : ''; ?>"
                                                       placeholder="Tuition Date" maxlength="20"/>
                                            </div>
                                            <!-- Tuition Date -->

                                            <!-- Start Date -->
                                            <label for="tuition_start_date" class="col-sm-2 control-label">Start
                                                Date</label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" id="tuition_start_date"
                                                       name="tuition_start_date"
                                                       value="<?php echo isset($tuition->tuition_start_date) &&
                                                       $tuition->tuition_start_date!='0000-00-00' ? date('d/m/Y', strtotime($tuition->tuition_start_date)):''; ?>"
                                                       placeholder="Start Date" maxlength="20"/>
                                            </div>
                                            <!-- Start Date -->

                                        </div>
                                        <!-- Tuition-Date Start Date -->

                                        <!-- Contact Person Contact No -->
                                        <div class="form-group">

                                            <!-- Contact Person -->
                                            <label for="contact_person" class="col-sm-2 control-label">Contact
                                                Person</label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" id="contact_person"
                                                       name="contact_person"
                                                       value="<?php echo isset($tuition->contact_person) ? $tuition->contact_person : ''; ?>"
                                                       placeholder="Contact Person" maxlength="100"/>
                                            </div>
                                            <!-- Contact Person -->

                                            <!-- Contact No -->
                                            <label for="contact_no" class="col-sm-2 control-label">Contact No</label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" id="contact_no"
                                                       name="contact_no" maxlength="20"
                                                       value="<?php echo isset($tuition->contact_no) ? $tuition->contact_no : ''; ?>"
                                                       placeholder="Contact No"/>
                                            </div>
                                            <!-- Contact No -->

                                        </div>
                                        <!-- Contact Person Contact No -->

                                        <!-- Contact No.2 No of students -->
                                        <div class="form-group">

                                            <!-- Contact No.2 -->
                                            <label for="contact_no2" class="col-sm-2 control-label">Contact No.
                                                2</label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" id="contact_no2"
                                                       name="contact_no2" maxlength="20"
                                                       value="<?php echo isset($tuition->contact_no2) ? $tuition->contact_no2 : ''; ?>"
                                                       placeholder="Contact No 2"/>
                                            </div>
                                            <!-- Contact No.2 -->

                                            <!-- No of students -->
                                            <label for="no_of_students" class="col-sm-2 control-label">No of
                                                students</label>
                                            <div class="col-sm-4">

                                                <select class="form-control select2" id="no_of_students"
                                                        name="no_of_students"
                                                        data-placeholder="Select no of students">
                                                    <option value=""></option>
                                                    <option value="1"
                                                    <?php if (isset($tuition->no_of_students) && ($tuition->no_of_students == 1)) echo "selected"; ?>>
                                                        One
                                                    </option>
                                                    <option value="2"
                                                    <?php if (isset($tuition->no_of_students) && ($tuition->no_of_students == 2)) echo "selected"; ?>>
                                                        Two
                                                    </option>
                                                    <option value="3"
                                                    <?php if (isset($tuition->no_of_students) && ($tuition->no_of_students == 3)) echo "selected"; ?>>
                                                        Three
                                                    </option>
                                                    <option value="4"
                                                    <?php if (isset($tuition->no_of_students) && ($tuition->no_of_students == 4)) echo "selected"; ?>>
                                                        Four
                                                    </option>
                                                    <option value="5"
                                                    <?php if (isset($tuition->no_of_students) && ($tuition->no_of_students == 5)) echo "selected"; ?>>
                                                        Five
                                                    </option>
                                                    <option value="6"
                                                    <?php if (isset($tuition->no_of_students) && ($tuition->no_of_students == 6)) echo "selected"; ?>>
                                                        Six
                                                    </option>
                                                    <option value="7"
                                                    <?php if (isset($tuition->no_of_students) && ($tuition->no_of_students == 7)) echo "selected"; ?>>
                                                        Seven
                                                    </option>
                                                    <option value="8"
                                                    <?php if (isset($tuition->no_of_students) && ($tuition->no_of_students == 8)) echo "selected"; ?>>
                                                        Eight
                                                    </option>
                                                    <option value="9"
                                                    <?php if (isset($tuition->no_of_students) && ($tuition->no_of_students == 9)) echo "selected"; ?>>
                                                        Nine
                                                    </option>
                                                    <option value="10"
                                                    <?php if (isset($tuition->no_of_students) && ($tuition->no_of_students == 10)) echo "selected"; ?>>
                                                        Ten
                                                    </option>
                                                </select>

                                            </div>
                                            <!-- No of students -->

                                        </div>
                                        <!-- Contact No.2 No of students -->

                                        <!-- Grade and subjects -->
                                        <div class="form-group">
                                            <label for="csm" class="col-sm-2 control-label">Grade+Subjects</label>
                                            <div class="col-sm-10">
                                                <select name="csm[]" id="csm" class="form-control select2 cms_change"
                                                        multiple="multiple"
                                                        data-placeholder="Select Grade">
                                                    <option value="">Select Grade</option>
                                                    <?php foreach($classes as $class): ?>
                                                    <option value="<?php echo $class->id; ?>"<?php if (in_array($class->id, $selected_classes)) echo "disabled";  ?> ><?php echo $class->name; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <input type="hidden" name="class_change" id="class_change" value=""/>
                                            </div>
                                        </div>

                                        <?php if($status == 'update' && !empty($selected_classes) ): ?>

                                        <div class="form-group">

                                            <label for="csm" class="col-sm-2 control-label">Existing
                                                Grade+Subjects</label>

                                            <div class="col-sm-10">
                                                <select name="csm[]" id="csm" class="form-control select2 cms_change"
                                                        multiple="multiple"
                                                        data-placeholder="Select Grade" disabled>
                                                    <option value="">Select Grade</option>
                                                    <?php foreach($classes as $class): ?>
                                                    <option value="<?php echo $class->id; ?>"<?php if (in_array($class->id, $selected_classes)) echo "selected";  ?>><?php echo $class->name; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>

                                    <?php endif; ?>

                                    <!-- Grade and subjects -->

                                        <!-- Institutes Location -->
                                        <div class="form-group">

                                            <!-- Institutes -->
                                            <label for="institutes" class="col-sm-2 control-label">
                                                Institutes</label>
                                            <div class="col-sm-4">
                                                <select class="form-control select2 institute" multiple="multiple"
                                                        id="institutes" name="institutes[]"
                                                        data-placeholder="Select Institutes">
                                                    <?php foreach($instututes as $instutute): ?>
                                                    <option value="<?php echo $instutute->id; ?>"
                                                    <?php if (isset($instututes) && in_array($instutute->id, $preferredinstitute)) echo "selected";  ?>>
                                                        <?php echo $instutute->name; ?></option>
                                                    <?php endforeach; ?>

                                                </select>
                                                <input type="hidden" name="institute_change" id="institute_change"
                                                       value=""/>

                                            </div>
                                            <!-- Institutes -->

                                            <!-- Location -->
                                            <label for="location_id" class="col-sm-2 control-label">Location</label>
                                            <div class="col-sm-4">
                                                <select name="location_id" id="location_id" class="form-control select2"
                                                        data-placeholder="Select Location">
                                                    <option value="">Select Location</option>
                                                    <?php foreach($locations as $location): ?>
                                                    <option value="<?php echo $location->id; ?>"<?php if (isset($tuition->location_id) && $tuition->location_id == $location->id) echo 'selected'; ?>>
                                                        <?php echo $location->locations; ?>
                                                    </option>
                                                    <?php endforeach; ?>

                                                </select>
                                            </div>
                                            <!-- Location -->

                                        </div>
                                        <!-- Institutes Location -->

                                        <!-- Contact Person Address -->
                                        <div class="form-group">
                                            <label for="address" class="col-sm-2 control-label">Address</label>
                                            <div class="col-sm-10">
                                                <textarea class="form-control" rows="3" id="address"
                                                          name="address"><?php echo isset($tuition->address) ? $tuition->address : ''; ?></textarea>
                                            </div>
                                        </div>
                                        <!-- Contact Person Address -->

                                        <!-- Tuition Category and Gender -->
                                        <div class="form-group">

                                            <!-- Tuition Category -->
                                            <label for="tuition_catefory_id" class="col-sm-2 control-label">Subjects/Grades
                                                Categories</label>
                                            <div class="col-sm-4">

                                                <select name="tuition_catefory_id" id="tuition_catefory_id"
                                                        class="form-control select2"
                                                        data-placeholder="Select Categories">
                                                    <option value=""></option>
                                                    <?php foreach($tuition_category as $category): ?>
                                                    <option value="<?php echo $category->id ?>"<?php if (isset($tuition->tuition_catefory_id) && $tuition->tuition_catefory_id == $category->id) echo 'selected'; ?>>
                                                        <?php echo $category->name ?>
                                                    </option>
                                                    <?php endforeach; ?>
                                                </select>

                                            </div>
                                            <!-- Tuition Category -->

                                            <!-- Location -->
                                            <label for="teacher_gender" class="col-sm-2 control-label">Gender</label>
                                            <div class="col-sm-4">
                                                <select name="teacher_gender" id="teacher_gender"
                                                        data-placeholder="Select Gender" class="form-control select2">
                                                    <option value=""></option>
                                                    <?php foreach($gender as $gender): ?>
                                                    <option value="<?php echo $gender->id; ?>"<?php if (isset($tuition->teacher_gender) && $gender->id == $tuition->teacher_gender) echo "selected" ?>><?php echo $gender->name; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <!-- Location -->
                                        </div>
                                        <!-- Tuition Category and Gender -->

                                        <!-- Labels and Suitable Timing -->
                                        <div class="form-group">

                                            <!-- Tuition Labels -->
                                            <label for="labels" class="col-sm-2 control-label">Labels</label>
                                            <div class="col-sm-4">
                                                <select class="form-control select2" multiple="multiple" id="labels"
                                                        name="labels[]"
                                                        data-placeholder="Select Labels">
                                                    <?php foreach($labels as $label): ?>
                                                    <option value="<?php echo $label->id; ?>"
                                                    <?php if (isset($tuition_labels) && in_array($label->id, $tuition_labels)) echo "selected";  ?>>
                                                        <?php echo $label->name; ?></option>
                                                    <?php endforeach; ?>

                                                </select>
                                                <input type="hidden" name="label_change" id="label_change" value=""/>
                                            </div>
                                            <!-- Tuition Labels -->

                                            <!-- Suitable Timings -->
                                            <label for="suitable_timings" class="col-sm-2 control-label">Suitable
                                                Timings</label>
                                            <div class="col-sm-4">

                                                <select class="form-control select2" id="suitable_timings"
                                                        name="suitable_timings"
                                                        data-placeholder="Select Suitable Timings">
                                                    <option value=""></option>
                                                    <option value="morning"
                                                    <?php if (isset($tuition->suitable_timings) && $tuition->suitable_timings == 'morning') echo "selected"; ?>>
                                                        Morning
                                                    </option>
                                                    <option value="evening"
                                                    <?php if (isset($tuition->suitable_timings) && $tuition->suitable_timings == 'evening') echo "selected"; ?>>
                                                        Evening
                                                    </option>
                                                    <option value="anytime"
                                                    <?php if (isset($tuition->suitable_timings) && $tuition->suitable_timings == 'anytime') echo "selected"; ?>>
                                                        AnyTime
                                                    </option>
                                                </select>

                                            </div>
                                            <!-- Suitable Timings -->
                                        </div>
                                        <!-- Labels and Suitable Timing -->

                                        <!-- Special Notes -->
                                        <div class="form-group">
                                            <label for="special_notes" class="col-sm-2 control-label">Choose Special
                                                Requirements</label>

                                            <div class="col-sm-10">
                                                <select name="special_notes" id="special_notes"
                                                        class="form-control note select2"
                                                        data-placeholder="Select Note Category">
                                                    <option value=""></option>
                                                    <?php foreach($notes as $note): ?>
                                                    <option value="<?php echo $note->note ?>"<?php if (isset($tuition->special_notes) && $tuition->special_notes == $note->note) echo 'selected'; ?>>
                                                        <?php echo $note->name ?>
                                                    </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="note" class="col-sm-2 control-label">Special
                                                Requirements</label>
                                            <div class="col-sm-10">

                                                <textarea class="form-control" rows="3" id="note"
                                                          name="note"><?php echo isset($tuition->special_notes) ? $tuition->special_notes : ''; ?></textarea>

                                            </div>
                                        </div>
                                        <!-- Special Notes -->

                                        <!-- Teaching Duration  -->
                                        <div class="form-group">

                                            <!-- Time Duration -->
                                            <label for="teaching_duration" class="col-sm-2 control-label">Teaching
                                                Duration</label>
                                            <div class="col-sm-4">
                                                <select name="teaching_duration" id="teaching_duration"
                                                        class="form-control  select2"
                                                        data-placeholder="Select Teaching Duration">
                                                    <option value=""></option>
                                                    <option value="15"
                                                    <?php if (isset($tuition->teaching_duration) && ($tuition->teaching_duration == '15')) echo "selected"; ?>>
                                                        15 Mins
                                                    </option>
                                                    <option value="30"
                                                    <?php if (isset($tuition->teaching_duration) && ($tuition->teaching_duration == '30')) echo "selected"; ?>>
                                                        30 Mins
                                                    </option>
                                                    <option value="45"
                                                    <?php if (isset($tuition->teaching_duration) && ($tuition->teaching_duration == '45')) echo "selected"; ?>>
                                                        45 Mins
                                                    </option>
                                                    <option value="60"
                                                    <?php if (isset($tuition->teaching_duration) && ($tuition->teaching_duration == '60')) echo "selected"; ?>>
                                                        1 Hour
                                                    </option>
                                                    <option value="75"
                                                    <?php if (isset($tuition->teaching_duration) && ($tuition->teaching_duration == '75')) echo "selected"; ?>>
                                                        1 Hour 15 Mins
                                                    </option>
                                                    <option value="90"
                                                    <?php if (isset($tuition->teaching_duration) && ($tuition->teaching_duration == '90')) echo "selected"; ?>>
                                                        1 Hour 30 Mins
                                                    </option>
                                                    <option value="105"
                                                    <?php if (isset($tuition->teaching_duration) && ($tuition->teaching_duration == '105')) echo "selected"; ?>>
                                                        1 Hour 45 Mins
                                                    </option>
                                                    <option value="120"
                                                    <?php if (isset($tuition->teaching_duration) && ($tuition->teaching_duration == '120')) echo "selected"; ?>>
                                                        2 Hours
                                                    </option>
                                                    <option value="135"
                                                    <?php if (isset($tuition->teaching_duration) && ($tuition->teaching_duration == '135')) echo "selected"; ?>>
                                                        2 Hours 15 Mins
                                                    </option>
                                                    <option value="150"
                                                    <?php if (isset($tuition->teaching_duration) && ($tuition->teaching_duration == '150')) echo "selected"; ?>>
                                                        2 Hours 30 Mins
                                                    </option>
                                                    <option value="165"
                                                    <?php if (isset($tuition->teaching_duration) && ($tuition->teaching_duration == '165')) echo "selected"; ?>>
                                                        2 Hours 45 Mins
                                                    </option>
                                                    <option value="180"
                                                    <?php if (isset($tuition->teaching_duration) && ($tuition->teaching_duration == '180')) echo "selected"; ?>>
                                                        3 Hours
                                                    </option>
                                                    <option value="195"
                                                    <?php if (isset($tuition->teaching_duration) && ($tuition->teaching_duration == '195')) echo "selected"; ?>>
                                                        3 Hours 15 Mins
                                                    </option>
                                                    <option value="210"
                                                    <?php if (isset($tuition->teaching_duration) && ($tuition->teaching_duration == '210')) echo "selected"; ?>>
                                                        3 Hours 30 Mins
                                                    </option>
                                                    <option value="225"
                                                    <?php if (isset($tuition->teaching_duration) && ($tuition->teaching_duration == '225')) echo "selected"; ?>>
                                                        3 Hours 45 Mins
                                                    </option>
                                                    <option value="240"
                                                    <?php if (isset($tuition->teaching_duration) && ($tuition->teaching_duration == '240')) echo "selected"; ?>>
                                                        4 Hours
                                                    </option>
                                                    <option value="255"
                                                    <?php if (isset($tuition->teaching_duration) && ($tuition->teaching_duration == '255')) echo "selected"; ?>>
                                                        4 Hours 15 Mins
                                                    </option>
                                                    <option value="270"
                                                    <?php if (isset($tuition->teaching_duration) && ($tuition->teaching_duration == '270')) echo "selected"; ?>>
                                                        4 Hours 30 Mins
                                                    </option>
                                                    <option value="285"
                                                    <?php if (isset($tuition->teaching_duration) && ($tuition->teaching_duration == '285')) echo "selected"; ?>>
                                                        4 Hours 45 Mins
                                                    </option>
                                                    <option value="300"
                                                    <?php if (isset($tuition->teaching_duration) && ($tuition->teaching_duration == '300')) echo "selected"; ?>>
                                                        5 Hours
                                                    </option>
                                                    <option value="315"
                                                    <?php if (isset($tuition->teaching_duration) && ($tuition->teaching_duration == '315')) echo "selected"; ?>>
                                                        5 Hours 15 Mins
                                                    </option>
                                                    <option value="330"
                                                    <?php if (isset($tuition->teaching_duration) && ($tuition->teaching_duration == '330')) echo "selected"; ?>>
                                                        5 Hours 30 Mins
                                                    </option>
                                                    <option value="345"
                                                    <?php if (isset($tuition->teaching_duration) && ($tuition->teaching_duration == '345')) echo "selected"; ?>>
                                                        5 Hours 45 Mins
                                                    </option>
                                                    <option value="360"
                                                    <?php if (isset($tuition->teaching_duration) && ($tuition->teaching_duration == '360')) echo "selected"; ?>>
                                                        6 Hours
                                                    </option>
                                                </select>
                                                <input type="hidden" name="duration_changed" id="duration_changed"
                                                       value=""/>
                                            </div>
                                            <!-- Time Duration -->

                                            <!-- Referred By -->
                                            <label for="referrer_id" class="col-sm-2 control-label">Referred By</label>
                                            <div class="col-sm-4">

                                                <select class="form-control select2 referred" id="referrer_id"
                                                        name="referrer_id"
                                                        data-placeholder="Select Referrers">
                                                    <option value=""></option>
                                                    <?php foreach($referrers as $referrer): ?>
                                                    <option value="<?php echo $referrer->id; ?>"<?php if (isset($tuition->referrer_id) && $tuition->referrer_id == $referrer->id) echo "selected"; ?>>
                                                        <?php echo $referrer->name; ?></option>
                                                    <?php endforeach; ?>

                                                </select>

                                            </div>

                                            <!-- Referred By -->

                                        </div>


                                        <!-- Age Experience -->
                                        <div class="form-group">

                                            <!-- Time Duration -->
                                            <label for="teacher_age" class="col-sm-2 control-label">Age</label>
                                            <div class="col-sm-4">

                                                <select class="form-control select2" id="teacher_age" name="teacher_age"
                                                        data-placeholder="Select Age">

                                                    <option value=""></option>

                                                    <option value="15"<?php if (isset($tuition->teacher_age) && ($tuition->teacher_age == 15)) echo "selected"; ?>>
                                                        15 Years & Above
                                                    </option>
                                                    <option value="25"<?php if (isset($tuition->teacher_age) && ($tuition->teacher_age == 25)) echo "selected"; ?>>
                                                        25 Years & Above
                                                    </option>
                                                    <option value="30"<?php if (isset($tuition->teacher_age) && ($tuition->teacher_age == 30)) echo "selected"; ?>>
                                                        30 Years & Above
                                                    </option>
                                                    <option value="35"<?php if (isset($tuition->teacher_age) && ($tuition->teacher_age == 35)) echo "selected"; ?>>
                                                        35 Years & Above
                                                    </option>
                                                    <option value="40"<?php if (isset($tuition->teacher_age) && ($tuition->teacher_age == 40)) echo "selected"; ?>>
                                                        40 Years & Above
                                                    </option>
                                                    <option value="50"<?php if (isset($tuition->teacher_age) && ($tuition->teacher_age == 50)) echo "selected"; ?>>
                                                        50 Years & Above
                                                    </option>
                                                    <option value="60"<?php if (isset($tuition->teacher_age) && ($tuition->teacher_age == 60)) echo "selected"; ?>>
                                                        60 Plus
                                                    </option>


                                                </select>

                                                <input type="hidden" name="age_change" id="age_change" value=""/>
                                            </div>
                                            <!-- Time Duration -->

                                            <!-- Tuition Experience -->
                                            <label for="experience" class="col-sm-2 control-label">Experience</label>
                                            <div class="col-sm-4">

                                                <select class="form-control select2" id="experience"
                                                        name="experience"
                                                        data-placeholder="Select Experience">
                                                    <option value=""></option>
                                                    <option value="15"
                                                    <?php if (isset($tuition->experience) && ($tuition->experience == '15')) echo "selected"; ?>>
                                                        Fifteen Years Plus
                                                    </option>
                                                    <option value="10"
                                                    <?php if (isset($tuition->experience) && ($tuition->experience == '10')) echo "selected"; ?>>
                                                        Ten Years Plus
                                                    </option>
                                                    <option value="5"
                                                    <?php if (isset($tuition->experience) && ($tuition->experience == '5')) echo "selected"; ?>>
                                                        Five Years Plus
                                                    </option>
                                                    <option value="1"
                                                    <?php if (isset($tuition->experience) && ($tuition->experience == '1')) echo "selected"; ?>>
                                                        One Years Plus
                                                    </option>
                                                    <option value="0.5"
                                                    <?php if (isset($tuition->experience) && ($tuition->experience == '0.5')) echo "selected"; ?>>
                                                        less then one year
                                                    </option>
                                                    <option value="0"
                                                    <?php if (isset($tuition->experience) && ($tuition->experience == '0')) echo "selected"; ?>>
                                                        Fresh
                                                    </option>
                                                </select>
                                                <input type="hidden" name="experience_change" id="experience_change"
                                                       value=""/>
                                            </div>
                                            <!-- Tuition Experience -->

                                        </div>
                                        <!-- Age Experience -->

                                        <!-- Band Other details -->
                                        <div class="form-group">

                                            <!-- Band -->
                                            <label for="band_id" class="col-sm-2 control-label">Band</label>
                                            <div class="col-sm-4">
                                                <select name="band_id" id="band_id" class="form-control select2"
                                                        data-placeholder="Select Band">
                                                    <option value=""></option>
                                                    <?php foreach($bands as $band): ?>
                                                    <option value="<?php echo $band->id ?>" <?php if (isset($tuition->band_id) && $tuition->band_id == $band->id) echo "selected" ?>>
                                                        <?php echo $band->name ?>
                                                    </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <!-- Band -->

                                            <!-- Is Approved -->
                                            <label for="is_approved" class="col-sm-2 control-label">Is Approved
                                                ?</label>
                                            <div class="col-sm-1">
                                                <input type="checkbox" name="is_approved" id="is_approved"
                                                       value="1" <?php echo isset($tuition) && ($tuition->is_approved == 1) ? 'checked' : ''; ?>>
                                            </div>
                                            <!-- Is Approved -->

                                            <!-- Other Detail -->
                                            <label for="is_active" class="col-sm-2 control-label">Is Active</label>
                                            <div class="col-sm-1">
                                                <input type="checkbox" name="is_active" id="is_active"
                                                       value="1" <?php echo isset($tuition) && ($tuition->is_active == 1) ? 'checked' : ''; ?>>
                                            </div>
                                            <!-- Other Detail -->

                                        </div>
                                        <!-- Band Other details -->

                                        <!-- Tutiion MIN and MAX Fee -->
                                        <div class="form-group">
                                            <!-- Tuition Fee -->
                                            <label for="tuition_fee" class="col-sm-2 control-label">Tuition MIN
                                                Fee(expected)</label>
                                            <div class="col-sm-4">

                                                <select name="tuition_fee" id="tuition_fee" class="form-control select2"
                                                        data-placeholder="Select Tuition Fee">
                                                    <option value=""></option>
                                                    <?php for($i = 1; $i <= 100; $i++){ ?>
                                                    <option value="<?php echo $i; ?>"
                                                    <?php if (isset($tuition->tuition_fee) && $tuition->tuition_fee == $i) echo "selected"; ?>><?php echo $i . "K"; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <input type="hidden" name="fee_change" id="fee_change" value=""/>

                                            </div>
                                            <!-- Tutiion MIN Fee -->

                                            <!-- Tuition MAX Fee -->
                                            <label for="tuition_max_fee" class="col-sm-2 control-label">Tuition MAX
                                                Fee(expected)</label>
                                            <div class="col-sm-4">

                                                <select name="tuition_max_fee" id="tuition_max_fee"
                                                        class="form-control select2"
                                                        data-placeholder="Select Tuition Fee">
                                                    <option value=""></option>
                                                    <?php for($i = 1; $i <= 100; $i++){ ?>
                                                    <option value="<?php echo $i; ?>"
                                                    <?php if (isset($tuition->tuition_max_fee) && $tuition->tuition_max_fee == $i) echo "selected"; ?>><?php echo $i . "K"; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <input type="hidden" name="fee_change" id="fee_change" value=""/>

                                            </div>
                                            <!-- Tuition MAX Fee -->
                                        </div>
                                        <!-- Tutiion MIN and MAX Fee -->

                                        <!-- Tutiion Final Fee and Partner Share -->
                                        <div class="form-group">
                                            <!-- Tuition Final Fee -->
                                            <label for="tuition_fee" class="col-sm-2 control-label">Tuition Fee</label>
                                            <div class="col-sm-4">

                                                <input type="text" class="form-control" id="tuition_final_fee"
                                                       name="tuition_final_fee"
                                                       maxlength="5"
                                                       value="<?php echo isset($tuition->tuition_final_fee) ? $tuition->tuition_final_fee : ''; ?>"
                                                       placeholder="Enter Fee">

                                            </div>
                                            <!-- Tutiion Final Fee -->

                                            <!-- Tuition Partner Share-->
                                            <label for="tuition_max_fee" class="col-sm-2 control-label">Partner
                                                Share(%)</label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" id="partner_share"
                                                       name="partner_share"
                                                       maxlength="5"
                                                       value="<?php echo isset($tuition->partner_share) ? $tuition->partner_share : ''; ?>"
                                                       placeholder="Enter Partner Share">
                                            </div>
                                            <!-- Tuition Partner Share-->
                                        </div>
                                        <!-- Tutiion Final Fee and Partner Share -->

                                        <!-- Tutiion Agent One and Two Share -->
                                        <div class="form-group">
                                            <!-- Tuition Final Fee -->
                                            <label for="tuition_fee" class="col-sm-2 control-label">Agent One
                                                Share(%)</label>
                                            <div class="col-sm-4">

                                                <input type="text" class="form-control" id="agent_one_share"
                                                       name="agent_one_share"
                                                       maxlength="5"
                                                       value="<?php echo isset($tuition->agent_one_share) ? $tuition->agent_one_share : ''; ?>"
                                                       placeholder="Enter Agent Share">

                                            </div>
                                            <!-- Tutiion Final Fee -->

                                            <!-- Tuition Partner Share-->
                                            <label for="tuition_max_fee" class="col-sm-2 control-label">Agent Two
                                                Share(%)</label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" id="agent_two_share"
                                                       name="agent_two_share"
                                                       maxlength="5"
                                                       value="<?php echo isset($tuition->agent_two_share) ? $tuition->agent_two_share : ''; ?>"
                                                       placeholder="Enter Agent Share">
                                            </div>
                                            <!-- Tuition Partner Share-->
                                        </div>
                                        <!-- Tutiion Agent One and Two Share -->

                                        <!-- Other Details -->
                                        <div class="form-group">
                                            <!-- Other Detail -->
                                            <label for="special_notes" class="col-sm-2 control-label">Other
                                                Details</label>
                                            <div class="col-sm-10">
                                                <textarea class="form-control" rows="3" id="details"
                                                          name="details"><?php echo isset($tuition->details) ? $tuition->details : ''; ?></textarea>
                                            </div>
                                            <!-- Other Detail -->

                                        </div>
                                        <!-- Other Details -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Tuition -->


                        <div class="box-footer">
                            <a href="{{url('admin/tuitions')}}" class="btn btn-warning pull-right">
                                <i class="fa fa-w fa-remove"></i> Cancel
                            </a>
                            <?php if($status == 'update'): ?>
                            <button type="submit" class="btn btn-primary pull-right" value="save" name="save"
                                    style="margin-right:5px;"><i class="fa fa-fw fa-save"></i> Update
                            </button>
                            <?php else: ?>
                            <button type="submit" class="btn btn-primary pull-right" value="saveadd" name="saveadd"
                                    style="margin-right:5px;"><i class="fa fa-fw fa-save"></i> Save & Add
                            </button>
                            <button type="submit" class="btn btn-primary pull-right" value="save" name="save"
                                    style="margin-right:5px;"><i class="fa fa-fw fa-save"></i> Save
                            </button>

                            <?php endif; ?>

                        </div>
                        <!-- /.box-footer -->
                    </form>
                </div>
                <!-- /.box -->
            </div>
        </div>
    </div>
    @include('layouts.partials.modal')
    </body>
@endsection

@section('page_specific_styles')
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datepicker/datepicker3.css') }}">
@endsection
@section('page_specific_scripts')
    <!-- FastClick -->
    <script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('plugins/jQuerymask/jquery.mask.min.js') }}"></script>
    <script src="{{ asset('/plugins/iCheck/icheck.min.js') }}" type="text/javascript"></script>
@endsection

@section('page_specific_inline_scripts')

    <script>

        @if (session('status'))

        jQuery(document).ready(function ($) {
            toastr.success('{{session('status')}}');
        });
    @endif

        $(function () {

            $(".select2").select2();

            $('.select2').change(function () {
                $('#label_change').val('change');
            });

            $('.institute').change(function () {
                $('#institute_change').val('change');
            });

            $('.cms_change').change(function () {
                $('#class_change').val('change');
            });


            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });

        jQuery(document).ready(function () {

            $("#tuition_start_date").datepicker();

            $(document).on("click", ":submit", function (e) {
                $("#submitbtnValue").val($(this).val());
            });

            $('#contact_no').mask('00000000000');
            $('#contact_no2').mask('00000000000');

            $('#tuition').on('submit', function (e) {
                e.preventDefault();

                var formData = new FormData($(this)[0]);
                $.ajax({

                    url: '{{url("admin/tuition/detail")}}',
                    type: "POST",
                    data: formData,
                    async: false,
                    beforeSend: function () {
                        $("#wait").modal();
                    },
                    success: function (data) {
                        console.log(data);
                        $('#wait').modal('hide');

                        var success = data['success'];

                        if (success == 'saveandadd') {

                            var redirect_url = '{{url("admin/tuition/add/")}}';
                            toastr.success('Tuition Save Successfully!');

                        } else if (success == 'save') {

                            var redirect_url = '{{url("admin/atmessage")}}';
                            window.location.replace(redirect_url);

                        } else {
                            $('#warning').modal();
                            $(".warning-message").remove();
                            $(".modal-body-warning").append($('<p>Please select subject</p>'));
                        }

                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });


            });
        });

        //Assigned Pending Tuitions
        $("#tuition_status_id").change(function () {

            var tuition_status_id  = $(this).val();
            var tutiionid = $('#id').val();

            //if status is assigned pending tuition
            if(tuition_status_id==10){

                $.ajax({
                    url: '{{url("admin/fee/tuition")}}',
                    type: "POST",
                    data: {'tuitionid': tutiionid, '_token': $('input[name=_token]').val()},

                    beforeSend: function () {
                        $("#wait").modal();
                    },
                    success: function (response) {

                        $('#wait').modal('hide');
                        var test = JSON.stringify(response);
                        var data = JSON.parse(test);

                        var status = data['success'];
                        if(status == false){
                            $('#TuitionFinalFee').modal();
                        }

                    }
                });

            }else if(tuition_status_id==6){

                $.ajax({
                    url: '{{url("admin/assigned/tuition")}}',
                    type: "POST",
                    data: {'tuitionid': tutiionid, '_token': $('input[name=_token]').val()},

                    beforeSend: function () {
                        $("#wait").modal();
                    },
                    success: function (response) {

                        $('#wait').modal('hide');
                        var test = JSON.stringify(response);
                        var data = JSON.parse(test);

                        var status = data['success'];
                        if(status == false){
                            $('#TuitionStartDate').modal();
                        }

                    }
                });

            }

        });

        $(".target").change(function () {

            var classid = $(this).val();

            $.ajax({
                url: '{{url("admin/tuition/detail/class/subjects")}}',
                type: "POST",
                data: {'classid': classid, '_token': $('input[name=_token]').val()},

                beforeSend: function () {
                    $("#wait").modal();
                },
                success: function (response) {
                    $(".classsubjects").remove();
                    $("#class-subj").css("display", "block");

                    $('#wait').modal('hide');

                    var test = JSON.stringify(response);
                    var data = JSON.parse(test);
                    //console.log(data['result']);

                    if (data['result'].length > 0) {

                        for (var j = 0; j < data['result'].length; j++) {

                            var mapping_id = data['result'][j]['mid'];
                            var class_id = data['result'][j]['cid'];
                            var subject_id = data['result'][j]['sid'];
                            var subject_name = data['result'][j]['sname'];
                            var class_name = data['result'][j]['cname'];

                            $(".class-subjects").append($('<div class="col-sm-3 classsubjects"><label>' +
                                '<input type="checkbox" name="subjects[]" id="subject" value="' + mapping_id + '"> &nbsp;' + subject_name + ' ' +
                                '</label></div>'));

                        }

                    }

                    $('input').iCheck({
                        checkboxClass: 'icheckbox_square-blue',
                        radioClass: 'iradio_square-blue',
                        increaseArea: '20%' // optional
                    });

                }
            });

        });

        //Date picker
        $('#datepicker').datepicker({
            autoclose: true
        });

        $('#datepicker1').datepicker({
            autoclose: true
        });



    </script>

    <script>
        $(".note").change(function () {
            var conceptName = $('#special_notes').find(":selected").val();
            //alert(conceptName);
            $("textarea#note").val(conceptName);
        });
    </script>

    <?php if(!isset($tuition->tuition_date)): ?>
    <script>
        $("#datepicker").datepicker().datepicker("setDate", new Date());
    </script>
    <?php endif; ?>
@endsection

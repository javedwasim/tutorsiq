<!-- SELECT2 EXAMPLE -->
<div class="box box-primary" id="filter-heading">
    <a href="#">
        <div class="box-header with-border" data-widget="collapse"><i class="fa fa-plus pull-right" style="font-size:12px;
        margin-top: 5px;"></i>
            <h1 class="box-title">Search Filters</h1>
        </div>
    </a>
    <!-- /.box-header -->
    <div class="box-body" style="display: none;">
        <!-- form start -->
        <form class="" method="post" action="{{ url('admin/teachers') }}" id="filterform">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="row" id="first-row">
                <!-- /.col -->
                <div class="col-md-3">

                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control " id="firstname" name="firstname"
                               placeholder="Name"
                               value="<?php echo isset($filters['firstname']) ? $filters['firstname'] : '' ?>">
                    </div>
                    <!-- /.form-group -->
                </div>

                <!-- /.col -->

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="text" class="form-control " id="email" name="email"
                               placeholder="Email"
                               value="<?php echo isset($filters['email']) ? $filters['email'] : '' ?>">
                    </div>
                    <!-- /.form-group -->

                </div>

                <!-- /.col -->
                <div class="col-md-3">

                    <div class="form-group">
                        <label>Mobile</label>
                        <input type="text" class="form-control " id="mobile1" name="mobile1"
                               placeholder="Mobile Number"
                               value="<?php echo isset($filters['mobile1']) ? $filters['mobile1'] : '' ?>">
                    </div>
                    <!-- /.form-group -->
                </div>

                <!-- /.col -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label>CNIC</label>
                        <input type="text" class="form-control " id="cnic_number" name="cnic_number"
                               placeholder="CNIC Number"
                               value="<?php echo isset($filters['cnic_number']) ? $filters['cnic_number'] : '' ?>">
                    </div>
                    <!-- /.form-group -->
                </div>
            </div>
            <!-- /.row -->
            <div class="row" id="second-row">

                <!-- /.col -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Grade</label>
                        <select class="form-control select2" id="class" name="class"
                                data-placeholder="Select a Grade">
                            <option value=""></option>
                            <?php foreach($classes as $class): ?>
                            <option value="<?php echo $class->id; ?>"
                            <?php if (isset($filters['class']) && $class->id == $filters['class']) echo "selected" ?>>
                                <?php echo $class->name; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Subjects</label>
                        <select class="form-control select2" multiple="multiple" id="subjects" name="subjects[]"
                                data-placeholder="Select a Subject">
                            <?php    foreach($subjects as $subject){ ?>
                            <option value="<?php echo $subject->sid; ?>"
                            <?php if (isset($filters['subjects']) && in_array($subject->sid, $filters['subjects'])) echo "selected";  ?>>
                                <?php echo $subject->name; ?>
                            </option>
                            <?php } ?>
                        </select>
                    </div>
                    <!-- /.form-group -->
                </div>

                <!-- /.col -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Zone</label>
                        <select class="form-control select2" id="zone" name="zone"
                                data-placeholder="Select a Zone">
                            <option value=""></option>
                            <?php foreach($zones as $zone): ?>
                            <option value="<?php echo $zone->id; ?>"
                            <?php if (isset($filters['zone']) && $zone->id == $filters['zone']) echo "selected" ?>>
                                <?php echo $zone->name; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->

                 <!-- /.col -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Locations</label>
                        <select class="form-control select2" multiple="multiple" id="locations" name="locations[]"
                                data-placeholder="Select a Location" style="width: 100%;">
                            <?php    foreach($locations as $location){ ?>
                            <option value="<?php echo $location->id; ?>"
                            <?php if (isset($filters['locations']) && in_array($location->id, $filters['locations'])) echo "selected";  ?>>
                                <?php echo $location->locations; ?>
                            </option>
                            <?php } ?>

                        </select>
                    </div>
                    <!-- /.form-group -->
                </div>


            </div>
            <!-- /.row -->
            <div class="row" id="third-row">
                <!-- /.col -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Teacher Band</label>
                        <select class="form-control" id="teacher_band_id" name="teacher_band_id" >
                            <option value="">All</option>
                            <?php foreach($teacher_band as $band): ?>
                            <option value="<?php echo $band->id; ?>"<?php if (isset($filters['teacher_band_id']) && $band->id == $filters['teacher_band_id']) echo "selected" ?>><?php echo $band->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <!-- /.form-group -->
                </div>

                <!-- /.col -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Gender</label>
                        <select class="form-control" id="gender_id" name="gender_id"  >
                            <option value="">All</option>
                            <?php foreach($genders as $gender): ?>
                            <option value="<?php echo $gender->id; ?>"<?php if (isset($filters['gender_id']) && $gender->id == $filters['gender_id']) echo "selected" ?>><?php echo $gender->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Experience</label>
                        <select class="form-control select2"  id="experience" name="experience"
                                data-placeholder="Select Experience" style="width: 100%;">
                            <option value=""></option>
                            <option value="15"<?php if (isset($filters['experience']) && ($filters['experience'] == 15)) echo "selected"; ?>>
                                Fifteen Years Plus</option>
                            <option value="10"<?php if (isset($filters['experience']) && ($filters['experience'] == 10)) echo "selected"; ?>>
                                Ten Years Plus</option>
                            <option value="5"<?php if (isset($filters['experience']) && ($filters['experience'] == 5)) echo "selected"; ?>>
                                Five Years Plus</option>
                            <option value="1"<?php if (isset($filters['experience']) && ($filters['experience'] == 1)) echo "selected"; ?>>
                                One Years Plus</option>
                            <option value="0.5"<?php if (isset($filters['experience']) && ($filters['experience'] == '0.5')) echo "selected"; ?>>
                                less then one year</option>
                            <option value="0"<?php if (isset($filters['experience']) && ($filters['experience'] == '0')) echo "selected"; ?>>
                                Fresh</option>
                        </select>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Age</label>
                        <select class="form-control select2"  id="age" name="age"
                                data-placeholder="Select Age" style="width: 100%;">
                            <option value=""></option>
                            <option value="15"<?php if (isset($filters['age']) && ($filters['age'] == 15)) echo "selected"; ?>>
                                Fifteen Years Plus</option>
                            <option value="25"<?php if (isset($filters['age']) && ($filters['age'] == 25)) echo "selected"; ?>>
                                Twenty Five Years Plus</option>
                            <option value="30"<?php if (isset($filters['age']) && ($filters['age'] == 30)) echo "selected"; ?>>
                                Thirty Years Plus</option>
                            <option value="35"<?php if (isset($filters['age']) && ($filters['age'] == 35)) echo "selected"; ?>>
                                Thirty Five Years Plus</option>
                            <option value="40"<?php if (isset($filters['age']) && ($filters['age'] == 40)) echo "selected"; ?>>
                                Forty Years Plus</option>
                            <option value="50"<?php if (isset($filters['age']) && ($filters['age'] == 45)) echo "selected"; ?>>
                                Fifty Years Plus</option>
                            <option value="60"<?php if (isset($filters['age']) && ($filters['age'] == 50)) echo "selected"; ?>>
                                Sixty Years Plus</option>

                        </select>
                    </div>
                    <!-- /.form-group -->
                </div>

            </div>
            <!-- /.row -->
            <div class="row" id="fourth-row">
                <!-- /.col -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Is Active ?</label>
                        <select class="form-control" id="is_active" name="is_active">
                            <option value="">All</option>
                            <option value="1"
                            <?php if (isset($filters['is_active']) && ($filters['is_active'] == 1)) echo "selected"; ?>>
                                Yes
                            </option>
                            <option value="2"
                            <?php if (isset($filters['is_active']) && ($filters['is_active'] == 2)) echo "selected"; ?>>
                                No
                            </option>

                        </select>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Is Approved ?</label>
                        <select class="form-control" id="is_approved" name="is_approved">
                            <option value="">All</option>
                            <option value="1"
                            <?php if (isset($filters['is_approved']) && ($filters['is_approved'] == 1)) echo "selected"; ?>>
                                Yes
                            </option>
                            <option value="2"
                            <?php if (isset($filters['is_approved']) && ($filters['is_approved'] == 2)) echo "selected"; ?>>
                                No
                            </option>
                        </select>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Marital Status</label>
                        <select class="form-control" id="marital_status_id" name="marital_status_id">
                            <option value="0">All</option>
                            <?php foreach($marital_status as $marital): ?>
                            <option value="<?php echo $marital->id; ?>"<?php if (isset($filters['marital_status_id']) && $marital->id == $filters['marital_status_id']) echo "selected" ?>><?php echo $marital->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <!-- /.form-group -->
                </div>

                <!-- /.col -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Registeration Number</label>
                        <input type="text" class="form-control " id="reg_number" name="reg_number"  placeholder="Registeration Number"
                               value="<?php echo isset($filters['reg_number']) ? $filters['reg_number'] : '' ?>">
                    </div>
                    <!-- /.form-group -->
                </div>

            </div>
            <!-- /.row -->
            <div class="row" id="fifth-row">
                <!-- /.col -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Qualification Name</label>
                        <input type="text" class="form-control " id="qual_name" name="qual_name"  placeholder="Qualification Name"
                               value="<?php echo isset($filters['qual_name']) ? $filters['qual_name'] : '' ?>">
                    </div>
                    <!-- /.form-group -->
                </div>

                <!-- /.col -->

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Preferred Institutes</label>
                        <select class="form-control select2" multiple="multiple" id="insts" name="insts[]"
                                data-placeholder="Select Institutes" style="width: 100%;">
                            <?php    foreach($institutes as $institute){ ?>
                            <option value="<?php echo $institute->id; ?>"
                            <?php if (isset($filters['insts']) && in_array($institute->id, $filters['insts'])) echo "selected";  ?>>
                                <?php echo $institute->name; ?>
                            </option>
                            <?php } ?>

                        </select>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Minimum Fee Package</label>
                        <select name="expected_minimum_fee" id="expected_minimum_fee"
                                class="form-control select2"
                                data-placeholder="Select Minimum Fee">
                            <option value=""></option>
                            <option value="4"
                            <?php if (isset($filters['expected_minimum_fee']) && $filters['expected_minimum_fee'] == 4) echo "selected"; ?> >4000 to 8000</option>
                            <option value="8"
                            <?php if (isset($filters['expected_minimum_fee']) && $filters['expected_minimum_fee'] == 8) echo "selected"; ?> >8000 to 12000</option>
                            <option value="12"
                            <?php if (isset($filters['expected_minimum_fee']) && $filters['expected_minimum_fee'] == 12) echo "selected"; ?> >12000 to 15000</option>
                            <option value="15"
                            <?php if (isset($filters['expected_minimum_fee']) && $filters['expected_minimum_fee'] == 15) echo "selected"; ?> >15000 to 20000</option>
                            <option value="20"
                            <?php if (isset($filters['expected_minimum_fee']) && $filters['expected_minimum_fee'] == 20) echo "selected"; ?> >20000 to 30000</option>
                            <option value="30"
                            <?php if (isset($filters['expected_minimum_fee']) && $filters['expected_minimum_fee'] == 30) echo "selected"; ?> >30000 to 40000</option>

                        </select>
                    </div>

                </div>
                <!-- /.col -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Suitable Timings</label>
                        <select class="form-control select2" id="suitable_timings"
                                name="suitable_timings"
                                data-placeholder="Select Suitable Timings">
                            <option value=""></option>
                            <option value="morning"
                                <?php if (isset($filters['suitable_timings']) && $filters['suitable_timings'] == 'morning') echo "selected"; ?>>
                                Morning
                            </option>
                            <option value="evening"
                                <?php  if (isset($filters['suitable_timings']) && $filters['suitable_timings'] == 'evening') echo "selected"; ?>>
                                Evening
                            </option>
                            <option value="anytime"
                                <?php if (isset($filters['suitable_timings']) && $filters['suitable_timings'] == 'anytime') echo "selected"; ?>>
                                Both(Morning & Evening)
                            </option>
                        </select>
                    </div>
                </div>
                <!-- /.col -->
                <!-- /.form-group -->
            </div>
            <!-- /.row -->
            <div class="row" id="sixth-row">

                <!-- /.col -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Subjects/Grades Categories</label>
                        <select class="form-control select2" multiple="multiple" id="categories" name="categories[]"
                                data-placeholder="Select Categories" style="width: 100%;">
                            <?php    foreach($tuition_categories as $category){ ?>
                            <option value="<?php echo $category->id; ?>"
                            <?php if (isset($filters['categories']) && in_array($category->id, $filters['categories'])) echo "selected";  ?>>
                                <?php echo $category->name; ?>
                            </option>
                            <?php } ?>

                        </select>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Labels</label>
                        <select class="form-control select2" multiple="multiple" id="labels" name="labels[]"
                                data-placeholder="Select Labels" style="width: 100%;">
                            <?php    foreach($labels as $label){ ?>
                            <option value="<?php echo $label->id; ?>"
                            <?php if (isset($filters['labels']) && in_array($label->id, $filters['labels'])) echo "selected";  ?>>
                                <?php echo $label->name; ?>
                            </option>
                            <?php } ?>

                        </select>
                        <input type="hidden" name="pagesize" id="pagesize" value="" />
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <div class="box-footer">
                <button type="submit" name="reset" value="reset" id="reset" class="btn btn-warning pull-right"><i
                            class="fa fa-fw fa-undo"></i> Reset
                </button>
                <button type="submit" id="submit_pagesize" class="btn btn-success pull-right" style="margin-right: 5px;"><i
                            class="fa fa-fw fa-search"></i> Search
                </button>

            </div>
            <!-- /.box-footer -->
        </form>
        <!-- end form -->
    </div>
</div>
<!-- /.box -->

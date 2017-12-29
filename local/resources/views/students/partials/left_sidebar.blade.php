<div class="col-xl-3 col-lg-12">
    <form class="" method="post" action="{{ url('search-teacher') }}" id="filterform">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <!-- filters -->
        <div class="box filter-box">
            <div id="accordion" class="panel panel-primary behclick-panel">
                <div class="panel-body">
                    <div class="filter">
                        <strong>Filter Search</strong>
                        <button type="submit" name="reset" value="reset" id="reset">
                            Clear filters
                        </button>
                    </div>
                    <div class="hold">
                        <h4 class="panel-title">Search By Qualification</h4>
                        <div class="form-group">
                            <input type="search" class="form-control" name="qual_name" id="qual_name"
                                   value="<?php echo isset($filters['qual_name']) ? $filters['qual_name'] : ''; ?>"
                                   placeholder="Enter Teacher Qualification">
                            <button type="submit" class="btn-search"></button>
                        </div>
                    </div>
                    <div class="hold">
                        <h4 class="panel-title">Filter by Suitable Timings:</h4>
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
                    <div class="hold">
                        <h4 class="panel-title">Filter by Age:</h4>
                        <select class="form-control select2" id="age"
                                name="age"
                                data-placeholder="Select Age">
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
<!--                    --><?php //echo '<pre>'; print_r($filters['age']); die(); ?>
                    <div class="hold">
                        <h4 class="panel-title">Filter by Subjects/Grades Categories:</h4>
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
                    <div class="hold">
                        <h4 class="panel-title">Filter by Grade:</h4>
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
                    <div class="hold">
                        <h4 class="panel-title">Filter by Subjects:</h4>

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
                    <div class="hold">
                        <h4 class="panel-title">Filter by Gender:</h4>
                        <ul class="list-unstyled">
                            <li>
                                <div class="radio">
                                    <input id="radio1" type="radio" name="gender_id" value="1"
                                    <?php echo isset($filters['gender_id']) && $filters['gender_id'] == 1 ? "checked" : ''; ?> >
                                    <label for="radio1">
                                        Male
                                    </label>
                                </div>
                            </li>
                            <li>
                                <div class="radio">
                                    <input id="radio2" type="radio" name="gender_id" value="2"
                                    <?php echo isset($filters['gender_id']) && $filters['gender_id'] == 2 ? "checked" : ''; ?>>
                                    <label for="radio2">
                                        Female
                                    </label>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="hold">
                        <h4 class="panel-title">Filter by Experience:</h4>

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
                    <div class="hold">

                        <h4 class="panel-title">Filter by Fee Package:</h4>
                        <select name="expected_minimum_fee" id="expected_minimum_fee"
                                class="form-control select2"
                                data-placeholder="Select Fee Package">
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
                    <div class="hold">
                        <h4 class="panel-title">Filter by Preferred Institutes:</h4>
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
                    <div class="hold">
                        <h4 class="panel-title">Filter by Zone:</h4>
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
                    <div class="hold">
                        <h4 class="panel-title">Filter by Locations:</h4>
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
                    <div class="hold button-hold">
                        <button type="submit" class="btn btn11 btn-block main-search ">
                            Search
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /filters -->
    </form>
</div>

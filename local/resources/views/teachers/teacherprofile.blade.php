@section('main-content')

    <div class="spark-screen">
        <div class="row">
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-info">
                    <!-- form start -->
                    <form class="form-horizontal" method="post" action="{{ url('/saveprofile') }}" id="teacher-post"
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
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="user_id"
                               value="<?php echo isset($teacher_detail->user_id) ? $teacher_detail->user_id : '';?>">
                        <input type="hidden" name="tid_update"
                               value="<?php echo isset($teacher_detail->id) ? $teacher_detail->id : '';?>">
                        <input type="hidden" name="front_image"
                               value="<?php echo isset($teacher_detail->cnic_front_image) ? $teacher_detail->cnic_front_image : '';?>">
                        <input type="hidden" name="back_image"
                               value="<?php echo isset($teacher_detail->cnic_back_image) ? $teacher_detail->cnic_back_image : '';?>">
                        <input type="hidden" name="photo"
                               value="<?php echo isset($teacher_detail->teacher_photo) ? $teacher_detail->teacher_photo : '';?>">
                        <input type="hidden" name="bill"
                               value="<?php echo isset($teacher_detail->electricity_bill) ? $teacher_detail->electricity_bill : '';?>">
                        <input type="hidden" name="gender_id"
                               value="<?php echo isset($teacher_detail->gender_id) ? $teacher_detail->gender_id : '';?>">
                        <input type="hidden" name="submitbtnValue" id="submitbtnValue" value="">

                        <div id="accordion" role="tablist" aria-multiselectable="true">
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingOne">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#personalinfo"
                                           aria-expanded="true" aria-controls="personalinfo">
                                            Personal Information
                                        </a>
                                    </h4>
                                </div>

                                <div id="personalinfo" class="panel-collapse collapse in" role="tabpanel"
                                     aria-labelledby="headingOne">
                                    <div class="box-body">

                                        <!-- Full Name and Email -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="firstname" class="col-sm-4 control-label">
                                                        Full Name<span style="color: red">*</span></label>

                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control " id="fullname"
                                                               name="fullname"
                                                               placeholder="Teacher Name" maxlength="100"
                                                               value="<?php echo isset($teacher_detail->fullname) ? $teacher_detail->fullname : ''; ?>"
                                                               required readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="email" class="col-sm-4 control-label">Email<span
                                                                style="color: red">*</span></label>

                                                    <div class="col-sm-8">
                                                        <input type="email" class="form-control" id="email" name="email"
                                                               value="<?php echo isset($teacher_detail->email) ? $teacher_detail->email : ''; ?>"
                                                               placeholder="Email"
                                                               maxlength="100" <?php echo isset($teacher_detail->email) ? 'disabled' : 'required'; ?> >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Full Name and Email -->

                                        <!-- Password and Confirm Password -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="password" class="col-sm-4 control-label">Password<span
                                                                style="color: red">*</span></label>

                                                    <div class="col-sm-8">

                                                        <input type="password" class="form-control" id="password"
                                                               value="<?php echo isset($teacher_detail->password) ? $teacher_detail->password : ''; ?>"
                                                               name="password" placeholder="password"
                                                               minlength="6" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="confirm_password" class="col-sm-4 control-label">Confirm
                                                        Password<span
                                                                style="color: red">*</span></label>

                                                    <div class="col-sm-8">
                                                        <input type="password" class="form-control"
                                                               placeholder="Confirm Password"
                                                               value="<?php echo isset($teacher_detail->password) ? $teacher_detail->password : ''; ?>"
                                                               id="confirm_password" required>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <!-- Password and Confirm Password -->

                                        <!-- Gender and Father Name -->
                                        <div class="row">

                                            <div class="col-md-6">

                                                <div class="form-group">
                                                    <label for="father_name" class="col-sm-4 control-label">Father's/Husband
                                                        Name<span style="color:red;">*</span></label>

                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control" id="father_name"
                                                               value="<?php echo isset($teacher_detail->father_name) ? $teacher_detail->father_name : ''; ?>"
                                                               name="father_name" placeholder="Father Name"
                                                               maxlength="100" required readonly>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="col-md-6">

                                                <div class="form-group">
                                                    <label for="gender_id" class="col-sm-4 control-label">
                                                        Gender<span style="color: red;">*</span></label>

                                                    <div class="col-sm-8">
                                                        <select class="form-control select2" id="gender_id"
                                                                data-placeholder="Select Gender" required disabled
                                                                name="gender_id">
                                                            <option value=""></option>
                                                            <?php foreach($genders as $gender): ?>
                                                            <option value="<?php echo $gender->id; ?>"<?php if (isset($teacher_detail->gender_id) && $gender->id == $teacher_detail->gender_id) {
                                                                echo "selected";
                                                            } elseif ($gender->id == 1) {
                                                                echo "selected";
                                                            } ?>
                                                            ><?php echo $gender->name; ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                        <!-- Gender and Father Name -->

                                        <!-- Marital Status and Added By -->
                                        <div class="row">
                                            <div class="col-md-6">

                                                <div class="form-group">
                                                    <label for="marital_status_id" class="col-sm-4 control-label">
                                                        Marital Status<span style="color: red;">*</span></label>

                                                    <div class="col-sm-8">
                                                        <select class="form-control select2" id="marital_status_id"
                                                                name="marital_status_id" required
                                                                data-placeholder="Select Marital Status">
                                                            <option value=""></option>
                                                            <?php foreach($maritals as $marital): ?>
                                                            <option value="<?php echo $marital->id; ?>"
                                                            <?php if (isset($teacher_detail->marital_status_id) && $marital->id == $teacher_detail->marital_status_id) {
                                                                echo "selected";
                                                            } elseif ($marital->id == 2) echo "selected"; ?> >
                                                                <?php echo $marital->name; ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="col-md-6">

                                                <div class="form-group">
                                                    <label for="added_by" class="col-sm-4 control-label">
                                                        Added By</label>

                                                    <div class="col-sm-8">
                                                        <select class="form-control select2" id="added_by"
                                                                name="added_by"  data-placeholder="Select Added By">
                                                            <option value=""></option>
                                                            <option value="employee1"<?php if (isset($teacher_detail->added_by) && $teacher_detail->added_by=='employee1')
                                                                echo "selected"; ?>>Employee One</option>
                                                            <option value="employee2"<?php if (isset($teacher_detail->added_by) && $teacher_detail->added_by=='employee2')
                                                                echo "selected"; ?>>Employee Two</option>
                                                            <option value="employee3"<?php if (isset($teacher_detail->added_by) && $teacher_detail->added_by=='employee3')
                                                                echo "selected"; ?>>Employee Three</option>
                                                            <option value="employee4"<?php if (isset($teacher_detail->added_by) && $teacher_detail->added_by=='employee4')
                                                                echo "selected"; ?>>Employee Four</option>
                                                            <option value="employee5"<?php if (isset($teacher_detail->added_by) && $teacher_detail->added_by=='employee5')
                                                                echo "selected"; ?>>Employee Five</option>
                                                            <option value="employee6"<?php if (isset($teacher_detail->added_by) && $teacher_detail->added_by=='employee6')
                                                                echo "selected"; ?>>Employee Six</option>
                                                            <option value="employee7"<?php if (isset($teacher_detail->added_by) && $teacher_detail->added_by=='employee7')
                                                                echo "selected"; ?>>Employee Seven</option>
                                                            <option value="employee8"<?php if (isset($teacher_detail->added_by) && $teacher_detail->added_by=='employee8')
                                                                echo "selected"; ?>>Employee Eight</option>
                                                            <option value="employee9"<?php if (isset($teacher_detail->added_by) && $teacher_detail->added_by=='employee9')
                                                                echo "selected"; ?>>Employee Nine</option>
                                                            <option value="employee10"<?php if (isset($teacher_detail->added_by) && $teacher_detail->added_by=='employee10')
                                                                echo "selected"; ?>>Employee Ten</option>
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>


                                        </div>
                                        <!-- Marital Status and Added By -->

                                        <!-- Religion and Minimum Fee -->
                                        <div class="row">
                                            <div class="col-md-6">

                                                <div class="form-group">
                                                    <label for="religion" class="col-sm-4 control-label">
                                                        Religion<span style="color:red;">*</span></label>

                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control" id="religion"
                                                               name="religion" required
                                                               value="<?php echo isset($teacher_detail->religion) ? $teacher_detail->religion : ''; ?>"
                                                               placeholder="Religion" maxlength="20">
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="col-md-6">

                                                <div class="form-group">
                                                    <label for="expected_minimum_fee" class="col-sm-4 control-label">
                                                        Minimum Fee Package<span style="color:red;">*</span></label>

                                                    <div class="col-sm-8">
                                                        <select name="expected_minimum_fee" id="expected_minimum_fee"
                                                                class="form-control select2" required
                                                                data-placeholder="Select Tuition Fee">
                                                            <option value=""></option>
                                                            <option value="4"
                                                            <?php if (isset($teacher_detail->expected_minimum_fee) && $teacher_detail->expected_minimum_fee == 4) echo "selected"; ?> >4000 to 8000</option>
                                                            <option value="8"
                                                            <?php if (isset($teacher_detail->expected_minimum_fee) && $teacher_detail->expected_minimum_fee == 8) echo "selected"; ?> >8000 to 12000</option>
                                                            <option value="12"
                                                            <?php if (isset($teacher_detail->expected_minimum_fee) && $teacher_detail->expected_minimum_fee == 12) echo "selected"; ?> >12000 to 15000</option>
                                                            <option value="15"
                                                            <?php if (isset($teacher_detail->expected_minimum_fee) && $teacher_detail->expected_minimum_fee == 15) echo "selected"; ?> >15000 to 20000</option>
                                                            <option value="20"
                                                            <?php if (isset($teacher_detail->expected_minimum_fee) && $teacher_detail->expected_minimum_fee == 20) echo "selected"; ?> >20000 to 30000</option>
                                                            <option value="30"
                                                            <?php if (isset($teacher_detail->expected_minimum_fee) && $teacher_detail->expected_minimum_fee == 30) echo "selected"; ?> >30000 to 40000</option>

                                                        </select>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                        <!-- Religion and Minimum Fee -->

                                        <!-- Suitable Timings and Living In-->
                                        <div class="row">
                                            <div class="col-md-6">

                                                <div class="form-group">
                                                    <label for="suitable_timings" class="col-sm-4 control-label">
                                                        Suitable Timings<span style="color:red;">*</span></label>

                                                    <div class="col-sm-8">
                                                        <select class="form-control select2" id="suitable_timings"
                                                                name="suitable_timings" required
                                                                data-placeholder="Select Suitable Timings">
                                                            <option value=""></option>
                                                            <option value="morning"
                                                            <?php if (isset($teacher_detail->suitable_timings) && $teacher_detail->suitable_timings == 'morning') echo "selected"; ?>>
                                                                Morning
                                                            </option>
                                                            <option value="evening"
                                                            <?php if (isset($teacher_detail->suitable_timings) && $teacher_detail->suitable_timings == 'evening') echo "selected"; ?>>
                                                                Evening
                                                            </option>
                                                            <option value="anytime"
                                                            <?php if (isset($teacher_detail->suitable_timings) && $teacher_detail->suitable_timings == 'anytime') echo "selected"; ?>>
                                                                Both(Morning & Evening)
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="col-md-6">

                                                <div class="form-group">
                                                    <label for="tuition_category_id" class="col-sm-4 control-label">
                                                        Subjects/Grades Categories<span style="color: red">*</span></label>

                                                    <div class="col-sm-8">
                                                        <select class="form-control select2 category"
                                                                multiple="multiple" id="tuition_category_id"
                                                                name="tuition_category_id[]" required
                                                                data-placeholder="Select Tuition Categories">
                                                            <?php foreach($tuition_categories as $category): ?>
                                                            <option value="<?php echo $category->id; ?>"
                                                            <?php if (isset($tuition_categories) && in_array($category->id, $tuitioncategories)) echo "selected";  ?>>
                                                                <?php echo $category->name; ?></option>
                                                            <?php endforeach; ?>

                                                        </select>
                                                        <input type="hidden" name="category_change" id="category_change"
                                                               value=""/>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-md-6">

                                                <div class="form-group">
                                                    <label for="livingin" class="col-sm-4 control-label">
                                                        Living In <span style="color: red">*</span></label>

                                                    <div class="col-sm-8">
                                                        <select class="form-control select2" id="livingin"
                                                                name="livingin" required
                                                                data-placeholder="Select Living In">
                                                            <option value=""></option>
                                                            <option value="own"
                                                            <?php if (isset($teacher_detail->livingin) && ($teacher_detail->livingin == 'own')) echo "selected"; ?>>
                                                                Own House
                                                            </option>
                                                            <option value="rent"
                                                            <?php if (isset($teacher_detail->livingin) && ($teacher_detail->livingin == 'rent')) echo "selected"; ?>>
                                                                Rented House
                                                            </option>
                                                            <option value="hostel"
                                                            <?php if (isset($teacher_detail->livingin) && ($teacher_detail->livingin == 'hostel')) echo "selected"; ?>>
                                                                Hostel
                                                            </option>

                                                        </select>

                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                        <!-- Suitable Timings and Living In-->

                                        <!-- Age and Expereince -->
                                        <div class="row">
                                            <div class="col-md-6">

                                                <div class="form-group">
                                                    <label for="age" class="col-sm-4 control-label">
                                                        Age <span style="color: red;">*</span></label>

                                                    <div class="col-sm-8">
                                                        <select class="form-control select2" id="age" name="age"
                                                                data-placeholder="Select Age" required>

                                                            <option value=""></option>

                                                            <option value="15"<?php if (isset($teacher_detail->age) && ($teacher_detail->age == 15)) echo "selected"; ?>>
                                                                Fifteen Years Plus
                                                            </option>

                                                            <option value="25"<?php if (isset($teacher_detail->age) && ($teacher_detail->age == 25)) echo "selected"; ?>>
                                                                Twenty Five Years Plus
                                                            </option>
                                                            <option value="30"<?php if (isset($teacher_detail->age) && ($teacher_detail->age == 30)) echo "selected"; ?>>
                                                                Thirty Years Plus
                                                            </option>
                                                            <option value="35"<?php if (isset($teacher_detail->age) && ($teacher_detail->age == 35)) echo "selected"; ?>>
                                                                Thirty Five Years Plus
                                                            </option>
                                                            <option value="40"<?php if (isset($teacher_detail->age) && ($teacher_detail->age == 40)) echo "selected"; ?>>
                                                                Forty Years Plus
                                                            </option>
                                                            <option value="50"<?php if (isset($teacher_detail->age) && ($teacher_detail->age == 50)) echo "selected"; ?>>
                                                                Fifty Years Plus
                                                            </option>
                                                            <option value="50"<?php if (isset($teacher_detail->age) && ($teacher_detail->age == 60)) echo "selected"; ?>>
                                                                Sixty Years Plus
                                                            </option>

                                                        </select>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-md-6">

                                                <div class="form-group">
                                                    <label for="experience" class="col-sm-4 control-label">
                                                        Your Teaching Experience Period<span style="color:red;">*</span></label>

                                                    <div class="col-sm-8">
                                                        <select class="form-control select2" id="experience"
                                                                name="experience" required
                                                                data-placeholder="Select Experience">
                                                            <option value=""></option>
                                                            <option value="15"
                                                            <?php if (isset($teacher_detail->experience) && ($teacher_detail->experience == '15')) echo "selected"; ?>>
                                                                Fifteen Years Plus
                                                            </option>
                                                            <option value="10"
                                                            <?php if (isset($teacher_detail->experience) && ($teacher_detail->experience == '10')) echo "selected"; ?>>
                                                                Ten Years Plus
                                                            </option>
                                                            <option value="5"
                                                            <?php if (isset($teacher_detail->experience) && ($teacher_detail->experience == '5')) echo "selected"; ?>>
                                                                Five Years Plus
                                                            </option>
                                                            <option value="1"
                                                            <?php if (isset($teacher_detail->experience) && ($teacher_detail->experience == '1')) echo "selected"; ?>>
                                                                One Years Plus
                                                            </option>
                                                            <option value="0.5"
                                                            <?php if (isset($teacher_detail->experience) && ($teacher_detail->experience == '0.5')) echo "selected"; ?>>
                                                                less then one year
                                                            </option>
                                                            <option value="0"
                                                            <?php if (isset($teacher_detail->experience) && ($teacher_detail->experience == '0')) echo "selected"; ?>>
                                                                Fresh
                                                            </option>
                                                        </select>

                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                        <!-- Age and Expereince -->

                                        <!-- Past Teaching Experience -->
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="past_experience" class="col-sm-12 control-label" style="text-align: left">
                                                        Write About Your Past Teaching Experiecne (Home Tuition or/and Institution)<span style="color: red;">*</span></label>

                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <textarea class="form-control" rows="10" required
                                                                  name="past_experience"><?php echo isset($teacher_detail->past_experience) ? $teacher_detail->past_experience : ''; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <!-- Past Teaching Experience -->

                                        <!-- About My Self -->
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="strength" class="col-sm-12 control-label" style="text-align: left">About My Self(MAX 1000 Words)</label>

                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <textarea class="form-control" rows="3" name="strength"
                                                                  maxlength="1000"><?php echo isset($teacher_detail->strength) ? $teacher_detail->strength : ''; ?></textarea>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                        <!-- About My Self -->




                                        <!-- Teacher reference for rented house -->
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="reference_for_rent" class="col-sm-12 control-label" style="text-align: left">If living in rented
                                                        house/hostel, give the reference of anyone (relative or
                                                        teacher/friend)
                                                        resident in Lahore in his/her own house(Name, Address, Mobile
                                                        No, Relationship)</label>

                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <textarea class="form-control" rows="3" name="reference_for_rent"
                                                                  maxlength="300" readonly><?php echo isset($teacher_detail->reference_for_rent) ? $teacher_detail->reference_for_rent : ''; ?></textarea>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                        <!-- Teacher reference for rented house -->

                                        <!-- Gurantor Reference -->
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="reference_gurantor" class="col-sm-12 control-label" style="text-align: left">
                                                        Reference of guarantor(Relative/Teacher/Friend) resident in Lahore (Name, Address, Mobile
                                                        No, Relationship)<span style="color:red;">*</span></label>

                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <textarea class="form-control" rows="3" name="reference_gurantor" required readonly
                                                                  maxlength="300"><?php echo isset($teacher_detail->reference_gurantor) ? $teacher_detail->reference_gurantor : ''; ?></textarea>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                        <!-- Gurantor Reference -->



                                        <!-- Your About Us -->
                                        <div class="row">

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="about_us" class="col-sm-12 control-label" style="text-align: left">Comments About UsYour Strengths (MAX 1000 Words)</label>
                                                </div>

                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <textarea class="form-control" rows="3" name="about_us"
                                                                  maxlength="1000"><?php echo isset($teacher_detail->about_us) ? $teacher_detail->about_us : ''; ?></textarea>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                        <!-- Your About Us -->

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /personal information -->

                        <!-- /contact information -->

                        <div id="accordion" role="tablist" aria-multiselectable="true">
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingOne">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#contactinfo"
                                           aria-expanded="true" aria-controls="contactinfo">
                                            Contact Information
                                        </a>
                                    </h4>
                                </div>
                                <div id="contactinfo" class="panel-collapse collapse in" role="tabpanel"
                                     aria-labelledby="headingOne">
                                    <div class="box-body">

                                        <!-- CNIC Number and  Personal Photo -->
                                        <div class="row">

                                            <div class="col-md-6">

                                                <div class="form-group">
                                                    <label for="teacher_photo" class="col-sm-4 control-label">
                                                        Upload Personal Photo<span style="color: red">*</span></label>

                                                    <div class="col-sm-8">

                                                        <input type="file" id="teacher_photo" name="teacher_photo"
                                                               <?php if(empty($teacher_detail->teacher_photo)) echo "required" ?> class="upload-demo">
                                                        <?php if(!empty($teacher_detail->teacher_photo)) :?>
                                                        <label for="degree_document" class="control-label"
                                                               style="text-align: left;">

                                                            <button type='button' class="btn btn-primary btn-photo">
                                                                <i class="fa  fa-eye"></i>View Image
                                                            </button>

                                                            <div class="box-body teacher-image" style="display: none;">
                                                                <img src="<?php  echo url("/local/teachers/" . $teacher_detail->id . "/photo/" . $teacher_detail->teacher_photo); ?>"
                                                                     alt="Teacher Documents" width="100" height="100">
                                                            </div>
                                                        </label>
                                                        <?php endif; ?>


                                                    </div>
                                                </div>

                                            </div>

                                            <div class="col-md-6">

                                                <div class="form-group">
                                                    <label for="electricity_bill" class="col-sm-4 control-label">
                                                        Scanned Copy Of Electricity/Telephone Bill OR Copy Of Your Permanent Residence<span style="color: red">*</span></label>

                                                    <div class="col-sm-8">

                                                        <input type="file" id="electricity_bill" name="electricity_bill"
                                                               <?php if(empty($teacher_detail->electricity_bill)) echo "required" ?>  class="upload-demo">
                                                        <?php if(!empty($teacher_detail->electricity_bill)) :?>
                                                        <label for="degree_document" class="control-label"
                                                               style="text-align: left;">

                                                            <button type='button' class="btn btn-primary btn-bill">
                                                                <i class="fa  fa-eye"></i>View Image
                                                            </button>

                                                            <div class="box-body teacher-bill" style="display: none;">
                                                                <img src="<?php  echo url("/local/teachers/" . $teacher_detail->id . "/bill/" . $teacher_detail->electricity_bill); ?>"
                                                                     alt="Teacher Documents" width="100" height="100">
                                                            </div>
                                                        </label>
                                                        <?php endif; ?>


                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                        <!-- CNIC Number and  Personal Photo -->

                                        <!-- CNIC Front and Back Image -->
                                        <div class="row">

                                            <div class="col-md-6">

                                                <div class="form-group">
                                                    <label for="cnic_front_image" class="col-sm-4 control-label">
                                                        Original CNIC Scanned Front Image <span style="color: red;">*</span></label>

                                                    <div class="col-sm-8">

                                                        <input type="file" id="cnic_front_image" name="cnic_front_image"
                                                               class="upload-demo" <?php if(empty($teacher_detail->cnic_front_image)) echo 'required'; ?> >
                                                        <?php if(!empty($teacher_detail->cnic_front_image)) :?>
                                                        <label for="" class="control-label" style="text-align: left;">

                                                            <button type='button'
                                                                    class="btn btn-primary btn-cnic-front">
                                                                <i class="fa  fa-eye"></i> View Image
                                                            </button>

                                                            <div class="box-body teacher-cnic-front"
                                                                 style="display: none;">
                                                                <img src="<?php  echo url("/local/teachers/" . $teacher_detail->id . "/cnic/" . $teacher_detail->cnic_front_image); ?>"
                                                                     alt="Teacher Documents" width="100" height="100">
                                                            </div>

                                                        </label>
                                                        <?php endif; ?>


                                                    </div>
                                                </div>

                                            </div>

                                            <div class="col-md-6">

                                                <div class="form-group">
                                                    <label for="cnic_back_image" class="col-sm-4 control-label">
                                                        Original CNIC Scanned  Back Image<span style="color: red;">*</span></label>

                                                    <div class="col-sm-8">
                                                        <input type="file" id="cnic_back_image" name="cnic_back_image"
                                                               class="upload-demo" <?php if(empty($teacher_detail->cnic_back_image)) echo 'required'; ?> >
                                                        <?php if(!empty($teacher_detail->cnic_back_image)) :?>
                                                        <label for="" class="control-label"
                                                               style="text-align: left;">

                                                            <button type='button' class="btn btn-primary btn-cnic-back">
                                                                <i class="fa  fa-eye"></i> View Image
                                                            </button>

                                                            <div class="box-body teacher-cnic-back"
                                                                 style="display: none;">
                                                                <img src="<?php  echo url("/local/teachers/" . $teacher_detail->id . "/cnic/" . $teacher_detail->cnic_back_image); ?>"
                                                                     alt="Teacher Documents" width="100" height="100">
                                                            </div>
                                                        </label>
                                                        <?php endif; ?>

                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                        <!-- CNIC Front and Back Image -->

                                        <!-- DOB and LandLine -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="firstname" class="col-sm-4 control-label">
                                                        D.O.B<span style="color: red;">*</span></label>

                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control pull-right dob-pointer"
                                                               id="datepicker"
                                                               value="<?php echo $dob; ?>"
                                                               name="dob" placeholder="D.O.B" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="email" class="col-sm-4 control-label">Contact No (Home PTCL)</label>

                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control pull-right"
                                                               id="landline"
                                                               value="<?php echo isset($teacher_detail->landline) ? $teacher_detail->landline : ''; ?>"
                                                               name="landline" placeholder="Landline">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- DOB and LandLine -->

                                        <!-- Mobile Primary and Secondry -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="mobile1" class="col-sm-4 control-label">Personal Contact No
                                                        <span style="color: red">*</span></label>

                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control pull-right" id="mobile1"
                                                               value="<?php echo isset($teacher_detail->mobile1) ? $teacher_detail->mobile1 : ''; ?>"
                                                               name="mobile1" readonly
                                                               placeholder="Enter contact no"
                                                               maxlength="11" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="personal_contactno2"
                                                           class="col-sm-4 control-label">Personal Contact No2.</label>

                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control pull-right" id="personal_contactno2"
                                                               value="<?php echo isset($teacher_detail->personal_contactno2) ? $teacher_detail->personal_contactno2 : ''; ?>"
                                                               name="personal_contactno2" maxlength="20"
                                                               placeholder="Enter contact no">
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <!-- Mobile Primary and Secondry -->

                                        <!-- Father/Husband and emergency number -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="guardian_contact_no" class="col-sm-4 control-label">
                                                        Father/Husband's Contact No<span style="color: red;">*</span>
                                                    </label>

                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control pull-right" id="mobile1" readonly
                                                               value="<?php echo isset($teacher_detail->guardian_contact_no) ? $teacher_detail->guardian_contact_no : ''; ?>"
                                                               name="guardian_contact_no" required
                                                               placeholder="Enter contact no"
                                                               maxlength="11">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="email"
                                                           class="col-sm-4 control-label">Any Other Emergency Contact No</label>

                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control pull-right" id="mobile2"
                                                               value="<?php echo isset($teacher_detail->mobile2) ? $teacher_detail->mobile2 : ''; ?>"
                                                               name="mobile2" maxlength="20"
                                                               placeholder="Enter contact no">
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                        <!-- Father/Husband and emergency number -->

                                        <!-- CNIC number -->
                                        <div class="row">
                                            <div class="col-md-6">

                                                <div class="form-group">
                                                    <label for="cnic_number" class="col-sm-4 control-label">
                                                        CNIC No <span style="color: red;">*</span></label>

                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control" id="cnic_number" readonly
                                                               value="<?php echo isset($teacher_detail->cnic_number) ? $teacher_detail->cnic_number : ''; ?>"
                                                               name="cnic_number" placeholder="_____-________-_"
                                                               maxlength="15" required>
                                                    </div>
                                                </div>

                                            </div>


                                        </div>

                                        <!-- CNIC number -->

                                        <!-- Permanent Address -->
                                        <div class="h-holder">
                                            <div class="heading">Permanent Address(As on CNIC)
                                                <span style="color: red;">*</span></div>

                                            <!-- address-line one and two input-->
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="address_line1" class="col-sm-2 control-label">
                                                            Address <span style="color:red;">*</span></label>

                                                        <div class="col-sm-10">
                                                            <textarea class="form-control" name="address_line1" id="address_line1" cols="138"  maxlength="300" style="background: #eeeeee; width: 100%" required readonly
                                                                      rows="2"><?php echo isset($teacher_detail->address_line1) ? $teacher_detail->address_line1 : ''; ?></textarea>

                                                        </div>
                                                    </div>
                                                </div>


                                            </div>
                                            <!-- address-line one and two input-->

                                            <!-- City and Province Input-->
                                            <div class="row">

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="province" class="col-sm-4 control-label">
                                                            Province<span style="color:red;">*</span></label>

                                                        <div class="col-sm-8">
                                                            <select class="form-control select2" id="province"
                                                                    name="province" required disabled
                                                                    data-placeholder="Select Province">
                                                                <option value=""></option>
                                                                <?php foreach($provinces as $province): ?>
                                                                <option value="<?php echo $province->id; ?>"
                                                                <?php if (isset($teacher_detail->province) && ($province->id == $teacher_detail->province)) echo "selected"; ?> >
                                                                    <?php echo $province->name;  ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="city" class="col-sm-4 control-label">
                                                            City<span style="color:red;">*</span></label>

                                                        <div class="col-sm-8" id="provinceCities">

                                                            <select class="form-control select2" id="city" name="city"
                                                                    required disabled data-placeholder="Select City">
                                                                <option value=""></option>
                                                                <?php foreach($cities as $city): ?>
                                                                <option value="<?php echo $city->id; ?>"
                                                                <?php if (isset($teacher_detail->city) && ($city->id == $teacher_detail->city)) echo "selected"; ?> >
                                                                    <?php echo $city->name;  ?></option>
                                                                <?php endforeach; ?>
                                                            </select>

                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <!-- City and Province Input-->

                                            <!-- Zip Code and Country-->
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="zip_code" class="col-sm-4 control-label">
                                                            Zip Code</label>

                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control pull-right"
                                                                   id="zip_code" readonly
                                                                   value="<?php echo isset($teacher_detail->zip_code) ? $teacher_detail->zip_code : ''; ?>"
                                                                   name="zip_code" placeholder="zip Code"
                                                                   maxlength="10">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="country" class="col-sm-4 control-label">
                                                            Country<span style="color:red;">*</span></label>

                                                        <div class="col-sm-8">

                                                            <select class="form-control select2" id="country"
                                                                    name="country" required disabled
                                                                    data-placeholder="Select Country">
                                                                <option value=""></option>
                                                                <option value="Pakistan"
                                                                <?php if (isset($teacher_detail->country) && ($teacher_detail->country == 'Pakistan')) echo "selected"; ?> >
                                                                    Pakistan
                                                                </option>
                                                            </select>

                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <!-- Zip Code and Country-->

                                        </div>
                                        <!-- Permanent Address -->

                                        <!-- Present Address -->
                                        <div class="h-holder">
                                            <div class="heading">Present Address<span style="color:red;">*</span></div>

                                            <!-- address-line one and two input-->
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="address_line1_p" class="col-sm-2 control-label">
                                                            Address<span style="color:red;">*</span></label>

                                                        <div class="col-md-10">
                                                            <textarea class="form-control" name="address_line1_p" id="address_line1_p" cols="138"  maxlength="300" style="width: 100%" required
                                                                      rows="2"><?php echo isset($teacher_detail->address_line1_p) ? $teacher_detail->address_line1_p : ''; ?></textarea>

                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <!-- address-line one and two input-->

                                            <!-- City and Province Input-->
                                            <div class="row">

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="province_p" class="col-sm-4 control-label">
                                                            Province<span style="color:red;">*</span></label>

                                                        <div class="col-sm-8">
                                                            <select class="form-control select2" id="province_p"
                                                                    name="province_p" required
                                                                    data-placeholder="Select Province">
                                                                <option value=""></option>
                                                                <?php foreach($provinces as $province): ?>
                                                                <option value="<?php echo $province->id; ?>"
                                                                <?php if (isset($teacher_detail->province_p) && ($province->id == $teacher_detail->province_p)) echo "selected"; ?> >
                                                                    <?php echo $province->name;  ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="city_p" class="col-sm-4 control-label">
                                                            City<span style="color:red;">*</span></label>

                                                        <div class="col-sm-8" id="provinceCities_p">

                                                            <select class="form-control select2" id="city_p"
                                                                    name="city_p" required
                                                                    data-placeholder="Select City">
                                                                <option value=""></option>
                                                                <?php foreach($cities as $city): ?>
                                                                <option value="<?php echo $city->id; ?>"
                                                                <?php if (isset($teacher_detail->city_p) && ($city->id == $teacher_detail->city_p)) echo "selected"; ?> >
                                                                    <?php echo $city->name;  ?></option>
                                                                <?php endforeach; ?>
                                                            </select>

                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <!-- City and Province Input-->

                                            <!-- Zip Code and Country-->
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="zip_code_p" class="col-sm-4 control-label">
                                                            Zip Code</label>

                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control pull-right"
                                                                   id="zip_code_p"
                                                                   value="<?php echo isset($teacher_detail->zip_code_p) ? $teacher_detail->zip_code_p : ''; ?>"
                                                                   name="zip_code_p" placeholder="zip Code"
                                                                   maxlength="10">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="country_p" class="col-sm-4 control-label">
                                                            Country<span style="color:red;">*</span></label>

                                                        <div class="col-sm-8">

                                                            <select class="form-control select2" id="country_p"
                                                                    name="country_p" required
                                                                    data-placeholder="Select Country">
                                                                <option value=""></option>
                                                                <option value="Pakistan"
                                                                <?php if (isset($teacher_detail->country_p) && ($teacher_detail->country_p == 'Pakistan')) echo "selected"; ?> >
                                                                    Pakistan
                                                                </option>
                                                            </select>

                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <!-- Zip Code and Country-->

                                        </div>
                                        <!-- Present Address -->

                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- /contact information end -->

                        <!-- Terms and conditions -->
                        <div id="accordion" role="tablist" aria-multiselectable="true">
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingOne">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#contactinfo"
                                           aria-expanded="true" aria-controls="contactinfo">
                                            Terms and Conditions
                                        </a>
                                    </h4>
                                </div>

                                <div id="contactinfo" class="panel-collapse collapse in" role="tabpanel"
                                     aria-labelledby="headingOne">
                                    <div class="box-body">

                                        <!-- Gurantor Reference -->
                                        <div class="h-holder">
                                            <div class="heading">Visited or not</div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="reference_gurantor" class="col-sm-12 control-label" style="text-align: left">
                                                            Have you visited our office with all Documents(Tuition will be given after visit in office)</label>

                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">

                                                        <div class="col-sm-1"> <input type="radio" name="visited" id="dovisit" value="1" disabled
                                                                                      <?php  if(isset($teacher_detail->visited)&&($teacher_detail->visited==1)) echo 'checked'; ?> class="minimal"> </div>
                                                        <label for="dovisit" class="col-sm-5 control-label" style="text-align: left"> Visited</label>

                                                        <div class="col-sm-1"> <input type="radio" name="visited" id="notvisit" value="0" disabled
                                                                                      <?php  if(isset($teacher_detail->visited)&&($teacher_detail->visited==0)) echo 'checked'; ?> class="minimal"> </div>
                                                        <label for="notvisit" class="col-sm-5 control-label" style="text-align: left"> Not Visited</label>

                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <!-- Gurantor Reference -->

                                        <!-- Terms and Conditions -->
                                        <div class="h-holder">
                                            <div class="heading">Terms and Conditions <span style="color: red;">*</span></div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label  class="col-sm-12 control-label" style="text-align: left;font-weight: normal">
                                                            <textarea class="form-control" name="terms" id="terms" cols="30" rows="10"
                                                                      style="margin: 0px; width: 100%; background: #eeeeee;" readonly
                                                            ><?php echo isset($teacher_detail->terms) ? $teacher_detail->terms : '';?></textarea>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">

                                                        <div class="col-sm-1"> <input type="radio" name="accept" id="do" disabled
                                                                                      <?php  if(isset($teacher_detail->accept)&&($teacher_detail->accept==1)) echo 'checked'; ?> value="1" class="minimal" required> </div>
                                                        <label for="do" class="col-sm-5 control-label" style="text-align: left"> I accept</label>

                                                        <div class="col-sm-1"> <input type="radio" name="accept" id="not" disabled
                                                                                      <?php if(isset($teacher_detail->accept)&&($teacher_detail->accept==0)) echo 'checked'; ?> value="0" class="minimal" required> </div>
                                                        <label for="not" class="col-sm-5 control-label" style="text-align: left"> I don't accept</label>

                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <!-- Terms and Conditions -->

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Terms and conditions end -->

                        <div id="accordion" role="tablist" aria-multiselectable="true" style="display: none;">
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingOne">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#otherdetail"
                                           aria-expanded="true" aria-controls="otherdetail">
                                            Admin Use Only
                                        </a>
                                    </h4>
                                </div>
                                <div id="otherdetail" class="panel-collapse collapse in" role="tabpanel"
                                     aria-labelledby="headingOne">
                                    <div class="box-body">

                                        <!-- Registeration No and Band Category-->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="zip_code_p" class="col-sm-4 control-label">
                                                        Is Active(?)</label>

                                                    <div class="col-sm-8">
                                                        <select class="form-control select2" id="is_active" name="is_active"
                                                                data-placeholder="Please Select">
                                                            <option value="1"
                                                            <?php if (isset($teacher_detail->is_active) && ($teacher_detail->is_active == 1)) echo "selected"; ?>>
                                                                Yes
                                                            </option>
                                                            <option value="2"
                                                            <?php if (isset($teacher_detail->is_active) && ($teacher_detail->is_active == 2)) echo "selected"; ?>>
                                                                No
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="country_p" class="col-sm-4 control-label">
                                                        Is Approved(?)</label>

                                                    <div class="col-sm-8">

                                                        <select class="form-control select2" id="is_approved" name="is_approved"
                                                                data-placeholder="Please Select">

                                                            <option value="1"
                                                            <?php if (isset($teacher_detail->is_approved) && ($teacher_detail->is_approved == 1)) echo "selected"; ?>>
                                                                Yes
                                                            </option>
                                                            <option value="2"
                                                            <?php if (isset($teacher_detail->is_approved) && ($teacher_detail->is_approved == 2)) echo "selected"; ?>>
                                                                No
                                                            </option>
                                                        </select>

                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <!-- Registeration No and Band Category-->

                                        <!-- Active and Approved-->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="zip_code_p" class="col-sm-4 control-label">
                                                        Registration No</label>

                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control" id="registeration_no"
                                                               value="<?php echo isset($teacher_detail->registeration_no) ? $teacher_detail->registeration_no : 'Unassigned'; ?>"
                                                               name="registeration_no" placeholder="Registration No"
                                                               maxlength="100">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="country_p" class="col-sm-4 control-label">
                                                        Band Category</label>

                                                    <div class="col-sm-8">

                                                        <select class="form-control" id="teacher_band_id"
                                                                name="teacher_band_id">
                                                            <?php foreach($teacher_band as $band): ?>
                                                            <option value="<?php echo $band->id; ?>"<?php if (isset($teacher_detail->teacher_band_id) && $band->id == $teacher_detail->teacher_band_id) echo "selected" ?>><?php echo $band->name; ?></option>
                                                            <?php endforeach; ?>
                                                        </select>

                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <!-- Active and Approved-->

                                        <?php if(isset($teacher_labels)): ?>
                                        <div class="form-group">
                                            <label for="labels" class="col-sm-2 control-label">Labels</label>

                                            <div class="col-sm-10">
                                                <select class="form-control select2" multiple="multiple" id="labels"
                                                        name="labels[]"
                                                        data-placeholder="Select Labels">
                                                    <?php foreach($labels as $label): ?>
                                                    <option value="<?php echo $label->id; ?>"
                                                    <?php if (isset($teacher_labels) && in_array($label->id, $teacher_labels)) echo "selected";  ?>>
                                                        <?php echo $label->name; ?></option>
                                                    <?php endforeach; ?>

                                                </select>
                                                <input type="hidden" name="label_change" id="label_change" value=""/>
                                            </div>
                                        </div>
                                        <?php endif; ?>

                                        <div class="form-group">
                                            <label for="other_detail" class="col-sm-2 control-label">Other
                                                Details</label>

                                            <div class="col-sm-10">
                                                <textarea class="form-control" rows="3" name="other_detail"
                                                          maxlength="300">
                                                    <?php echo isset($teacher_detail->other_detail) ? $teacher_detail->other_detail : ''; ?></textarea>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- /other detail -->
                        <div class="box-footer">

                            <?php if(!empty($teacher_detail)): ?>
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
    <link rel="stylesheet" href="https://jqueryvalidation.org/files/demo/site-demos.css">

    @endsection

    @section('page_specific_scripts')
            <!-- FastClick -->
    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('plugins/jQuerymask/jquery.mask.min.js') }}"></script>
    <script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('/plugins/iCheck/icheck.min.js') }}" type="text/javascript"></script>

    <script src="{{asset('/js/jquery.validate.min.js')}}"></script>
    <script src="{{asset('/js/additional-methods.min.js')}}"></script>
@endsection

@section('page_specific_inline_scripts')
    <script>

        jQuery(document).ready(function(){

            //iCheck for checkbox and radio inputs
            //initialize icheck box
            $(function () {

                $('input.minimal').iCheck({
                    checkboxClass: 'icheckbox_square-blue',
                    radioClass: 'iradio_square-blue',
                    increaseArea: '20%' // optional
                });
            });

            //load cities on provice select
            $(document).on('change', "#province", function () {

                var id = this.value;
                $("#wait").modal();
                $.ajax({

                    url: '{{url('load/province/cities')}}',
                    type: "post",
                    data: {'provinceid': id, '_token': $('input[name=_token]').val()},

                    success: function (response) {

                        var test = JSON.stringify(response);
                        var data = JSON.parse(test);
                        var options = data['options'];
                        $("#provinceCities").empty();
                        $("#provinceCities").append(options);
                        $(".select2").select2();
                        $("#wait").modal('hide');

                    }

                });


            });
            //load cities on provice select end

            //load cities on provice select for present address
            $(document).on('change', "#province_p", function () {

                var id = this.value;
                $("#wait").modal();
                $.ajax({

                    url: '{{url('load/province/cities')}}',
                    type: "post",
                    data: {'provinceid': id, '_token': $('input[name=_token]').val()},

                    success: function (response) {

                        var test = JSON.stringify(response);
                        var data = JSON.parse(test);
                        var options = data['options_p'];
                        $("#provinceCities_p").empty();
                        $("#provinceCities_p").append(options);
                        $(".select2").select2();
                        $("#wait").modal('hide');

                    }

                });
            });
            //load cities on provice select for present address end

            var password = document.getElementById("password")
                    , confirm_password = document.getElementById("confirm_password");

            function validatePassword(){
                if(password.value != confirm_password.value) {
                    confirm_password.setCustomValidity("Passwords Don't Match");
                } else {
                    confirm_password.setCustomValidity('');
                }
            }
            password.onchange = validatePassword;
            confirm_password.onkeyup = validatePassword;

            //Initialize Select2 Elements
            $(".select2").select2();


            $('.select2').change(function(){
                $('#label_change').val('change');
            });

            $('.category').change(function () {
                $('#category_change').val('change');
            });

            $('.institute').change(function () {
                $('#institute_change').val('change');
            });

            $('#mobile1').mask('00000000000');
            $('#mobile2').mask('00000000000');
            $('#landline').mask('00000000000');
            $('#cnic_number').mask("00000-0000000-0", {placeholder: "_____-________-_"});
            //$('#expected_minimum_fee').mask("00000");
            $('#no_of_children').mask("000");
            $('#zip_code').mask("00000");

            jQuery('.btn-photo').on('click', function(event) {
                jQuery('.teacher-image').toggle('show');
            });

            jQuery('.btn-bill').on('click', function(event) {
                jQuery('.teacher-bill').toggle('show');
            });

            jQuery('.btn-cnic-back').on('click', function(event) {
                jQuery('.teacher-cnic-back').toggle('show');
            });

            jQuery('.btn-cnic-front').on('click', function(event) {
                jQuery('.teacher-cnic-front').toggle('show');
            });

        });

        jQuery( document ).ready( function( $ ) {

            $(document).on("click", ":submit", function(e){
                $("#submitbtnValue").val($(this).val());
            });


            $( '#teacher-post' ).on( 'submit', function(e) {

                e.preventDefault();

                var formData = new FormData($(this)[0]);
                $.ajax({

                    url: '{{url("saveprofile")}}',
                    type: "POST",
                    data: formData,
                    async: true,
                    beforeSend: function(){
                        $("#wait").modal();
                    },

                    success: function (data) {

                        $('#wait').modal('hide');
                        var teacherid = data['teacherid'];
                        var success = data['success'];

                        if(success=='save'){

                            var redirect_url = '{{url("teacher")}}';
                            $( ".modal-footer" ).append( $( '<a class="btn btn-outline" ' +
                                    'href="'+redirect_url+'">OK</a>' ) );
                            toastr.success('Teacher Profile Save Successfully!');

                        }

                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });


            });
        });

        //Date picker
        $('#datepicker').datepicker({
            autoclose: true,
        });

    </script>
@endsection

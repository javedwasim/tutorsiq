@extends('layouts.app')

@section('htmlheader_title')
    @role('teacher')
    Teahcer Registeration
    @endrole

    <?php if(!empty($teacher_detail)): ?>
    {{ trans('Admin | Teacher | Update') }}
    <?php else: ?>
    {{ trans('Admin | Teacher | Add') }}
    <?php endif; ?>

@endsection

@section('contentheader_title')
    <?php if(!empty($teacher_detail)): ?>
    {{ trans('Update Teacher') }}
    <?php else: ?>
    {{ trans('Add New Teacher') }}
    <?php endif; ?>
@endsection

@section('main-content')

    <div class="spark-screen">
        <div class="row">
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-info">
                    <!-- form start -->
                    <form class="form-horizontal" method="post" action="{{ url('/teacher-post') }}" id="teacher-post"
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

                                        <div class="form-group">
                                            <label for="firstname" class="col-sm-2 control-label">First Name <span
                                                        style="color: red">*</span></label>

                                            <div class="col-sm-10">
                                                <input type="text" class="form-control " id="firstname" name="firstname"
                                                       placeholder="Teacher Name" maxlength="100"
                                                       value="<?php echo isset($teacher_detail->firstname) ? $teacher_detail->firstname : ''; ?>"
                                                       required>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="lastname" class="col-sm-2 control-label">Last Name<span
                                                        style="color: red">*</span></label>

                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="lastname" name="lastname"
                                                       placeholder="Last Name"
                                                       value="<?php echo isset($teacher_detail->lastname) ? $teacher_detail->lastname : ''; ?>"
                                                       maxlength="100" required>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="email" class="col-sm-2 control-label">Email<span
                                                        style="color: red">*</span></label>

                                            <div class="col-sm-10">
                                                <input type="email" class="form-control" id="email" name="email"
                                                       value="<?php echo isset($teacher_detail->email) ? $teacher_detail->email : ''; ?>"
                                                       placeholder="Email"
                                                       maxlength="100" <?php echo isset($teacher_detail->email) ? 'disabled' : 'required'; ?>>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="password" class="col-sm-2 control-label">Password<span
                                                        style="color: red">*</span></label>

                                            <div class="col-sm-10">

                                                <input type="password" class="form-control" id="password"
                                                       value="<?php echo isset($teacher_detail->password) ? $teacher_detail->password : ''; ?>"
                                                       name="password" placeholder="password"
                                                       minlength="6" <?php echo isset($teacher_detail->password) ? 'required' : 'required'; ?>>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="password" class="col-sm-2 control-label">Confirm Password<span
                                                        style="color: red">*</span></label>

                                            <div class="col-sm-10">
                                                <input type="password" class="form-control"
                                                       placeholder="Confirm Password"
                                                       value="<?php echo isset($teacher_detail->password) ? $teacher_detail->password : ''; ?>"
                                                       id="confirm_password" <?php echo isset($teacher_detail->password) ? 'required' : 'required'; ?>>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="gender_id" class="col-sm-2 control-label">Gender<span
                                                        style="color: red">*</span></label>

                                            <div class="col-sm-10">
                                                <select class="form-control" id="gender_id" name="gender_id" required>
                                                    <option value="">Please Select</option>
                                                    <?php foreach($genders as $gender): ?>
                                                    <option value="<?php echo $gender->id; ?>"<?php if (isset($teacher_detail->gender_id) && $gender->id == $teacher_detail->gender_id) echo "selected" ?>><?php echo $gender->name; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="marital_status_id" class="col-sm-2 control-label">
                                                Marital Status</label>

                                            <div class="col-sm-10">
                                                <select class="form-control" id="marital_status_id"
                                                        name="marital_status_id" required>
                                                    <option value="">Please Select</option>
                                                    <?php foreach($maritals as $marital): ?>
                                                    <option value="<?php echo $marital->id; ?>"<?php if (isset($teacher_detail->marital_status_id) && $marital->id == $teacher_detail->marital_status_id) echo "selected" ?>><?php echo $marital->name; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="no_of_children" class="col-sm-2 control-label">
                                                No of Children's
                                            </label>

                                            <div class="col-sm-10">
                                                <input type="text" maxlength="3" class="form-control"
                                                       value="<?php echo isset($teacher_detail->no_of_children) ? $teacher_detail->no_of_children : ''; ?>"
                                                       id="no_of_children" name="no_of_children"
                                                       placeholder="No of Children's">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="father_name" class="col-sm-2 control-label">Father Name
                                                <span style="color: red">*</span>
                                            </label>

                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="father_name"
                                                       value="<?php echo isset($teacher_detail->father_name) ? $teacher_detail->father_name : ''; ?>"
                                                       name="father_name" placeholder="Father Name" maxlength="100"
                                                       required>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="religion" class="col-sm-2 control-label">Religion
                                                <span style="color: red">*</span>
                                            </label>

                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="religion" name="religion"
                                                       value="<?php echo isset($teacher_detail->religion) ? $teacher_detail->religion : ''; ?>"
                                                       placeholder="Religion" maxlength="20" required>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="expected_minimum_fee" class="col-sm-2 control-label">
                                                Minimum Fee
                                                <span style="color: red">*</span>
                                            </label>

                                            <div class="col-sm-10">
                                                <select name="expected_minimum_fee" id="expected_minimum_fee" class="form-control select2"
                                                        data-placeholder="Select Tuition Fee" required>
                                                    <option value=""></option>
                                                    <?php for($i=1; $i<=100; $i++){ ?>
                                                    <option value="<?php echo $i; ?>"
                                                    <?php if(isset($teacher_detail->expected_minimum_fee) && $teacher_detail->expected_minimum_fee== $i) echo "selected"; ?>><?php echo $i."K"; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="suitable_timings" class="col-sm-2 control-label">
                                                Suitable Timings
                                                <span style="color: red">*</span>
                                            </label>

                                            <div class="col-sm-10">
                                                <select class="form-control select2" id="suitable_timings" name="suitable_timings"
                                                        data-placeholder="Select Suitable Timings">
                                                    <option value=""></option>
                                                    <option value="morning"
                                                    <?php if(isset($teacher_detail->suitable_timings) && $teacher_detail->suitable_timings=='morning') echo "selected"; ?>>Morning</option>
                                                    <option value="evening"
                                                    <?php if(isset($teacher_detail->suitable_timings) && $teacher_detail->suitable_timings=='evening') echo "selected"; ?>>Evening</option>
                                                    <option value="anytime"
                                                    <?php if(isset($teacher_detail->suitable_timings) && $teacher_detail->suitable_timings=='anytime') echo "selected"; ?>>AnyTime</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="tuition_category_id" class="col-sm-2 control-label">Tuition Categories
                                                <span style="color: red">*</span>
                                            </label>

                                            <div class="col-sm-10">

                                                <select class="form-control select2 category" multiple="multiple" id="tuition_category_id"
                                                        name="tuition_category_id[]" required
                                                        data-placeholder="Select Tuition Categories">
                                                    <?php foreach($tuition_categories as $category): ?>
                                                    <option value="<?php echo $category->id; ?>"
                                                    <?php if (isset($tuition_categories) && in_array($category->id, $tuitioncategories)) echo "selected";  ?>>
                                                        <?php echo $category->name; ?></option>
                                                    <?php endforeach; ?>

                                                </select>
                                                <input type="hidden" name="category_change" id="category_change" value=""/>

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="cnic_number" class="col-sm-2 control-label">Preferred Institute
                                                <span style="color: red">*</span>
                                            </label>

                                            <div class="col-sm-10">

                                                <select class="form-control select2 institute" multiple="multiple" id="institute_id"
                                                        name="institute_id[]" required  data-placeholder="Select Preferred Institute">
                                                    <?php foreach($instututes as $instutute): ?>
                                                    <option value="<?php echo $instutute->id; ?>"
                                                    <?php if (isset($tuition_categories) && in_array($instutute->id, $preferredinstitute)) echo "selected";  ?>>
                                                        <?php echo $instutute->name; ?></option>
                                                    <?php endforeach; ?>

                                                </select>
                                                <input type="hidden" name="institute_change" id="institute_change" value=""/>

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="labels" class="col-sm-2 control-label">Experience<span
                                                        style="color: red">*</span></label>

                                            <div class="col-sm-10">

                                                <select class="form-control select2"  id="experience" name="experience"
                                                        data-placeholder="Select Experience">
                                                    <option value=""></option>
                                                    <option value="lessthen1"
                                                    <?php if (isset($teacher_detail->experience) && ($teacher_detail->experience == 'lessthen1')) echo "selected"; ?>>less then1</option>
                                                    <option value="1"
                                                    <?php if (isset($teacher_detail->experience) && ($teacher_detail->experience == '1')) echo "selected"; ?>>1+</option>
                                                    <option value="5"
                                                    <?php if (isset($teacher_detail->experience) && ($teacher_detail->experience == '5')) echo "selected"; ?>>5+</option>
                                                    <option value="10"
                                                    <?php if (isset($teacher_detail->experience) && ($teacher_detail->experience == '10')) echo "selected"; ?>>10+</option>
                                                    <option value="15"
                                                    <?php if (isset($teacher_detail->experience) && ($teacher_detail->experience == '15')) echo "selected"; ?>>15+</option>

                                                </select>

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="cnic_number" class="col-sm-2 control-label">CNIC Number
                                                <span style="color: red">*</span>
                                            </label>

                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="cnic_number"
                                                       value="<?php echo isset($teacher_detail->cnic_number) ? $teacher_detail->cnic_number : ''; ?>"
                                                       name="cnic_number" placeholder="_____-________-_" maxlength="15"
                                                       required>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="cnic_front_image" class="col-sm-2 control-label">Upload CNIC
                                                front Image<span style="color: red">*</span></label>

                                            <div class="col-sm-4">
                                                <input type="file" id="cnic_front_image" name="cnic_front_image"
                                                       class="upload-demo"
                                                <?php echo !empty($teacher_detail->cnic_front_image) ? '' : 'required'; ?> >
                                            </div>
                                            <?php if(!empty($teacher_detail->cnic_front_image)) :?>
                                            <label for="degree_document" class="col-sm-6 control-label"
                                                   style="text-align: left;">

                                                <button type='button' class="btn btn-primary btn-cnic-front">
                                                    <i class="fa  fa-eye"></i> View Image
                                                </button>

                                                <div class="box-body teacher-cnic-front" style="display: none;">
                                                    <img src="<?php  echo url("/local/teachers/" . $teacher_detail->id . "/cnic/" . $teacher_detail->cnic_front_image); ?>"
                                                         alt="Teacher Documents" width="100" height="100">
                                                </div>

                                            </label>
                                            <?php endif; ?>
                                        </div>

                                        <div class="form-group">
                                            <label for="cnic_back_image" class="col-sm-2 control-label">Upload CNIC back
                                                Image<span style="color: red">*</span></label>

                                            <div class="col-sm-4">
                                                <input type="file" id="cnic_back_image" name="cnic_back_image"
                                                       class="upload-demo" <?php echo !empty($teacher_detail->cnic_back_image) ? '' : 'required'; ?>>
                                            </div>
                                            <?php if(!empty($teacher_detail->cnic_back_image)) :?>
                                            <label for="degree_document" class="col-sm-6 control-label"
                                                   style="text-align: left;">

                                                <button type='button' class="btn btn-primary btn-cnic-back">
                                                    <i class="fa  fa-eye"></i> View Image
                                                </button>

                                                <div class="box-body teacher-cnic-back" style="display: none;">
                                                    <img src="<?php  echo url("/local/teachers/" . $teacher_detail->id . "/cnic/" . $teacher_detail->cnic_back_image); ?>"
                                                         alt="Teacher Documents" width="100" height="100">
                                                </div>
                                            </label>
                                            <?php endif; ?>
                                        </div>

                                        <div class="form-group">
                                            <label for="cnic_back_image" class="col-sm-2 control-label">Upload Personal
                                                Photo </label>

                                            <div class="col-sm-4">
                                                <input type="file" id="teacher_photo" name="teacher_photo"
                                                       class="upload-demo">
                                            </div>
                                            <?php if(!empty($teacher_detail->teacher_photo)) :?>
                                            <label for="degree_document" class="col-sm-6 control-label"
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

                                        <div class="form-group">
                                            <label for="strength" class="col-sm-2 control-label">Your Strengths
                                            </label>

                                            <div class="col-sm-10">
                                                <textarea class="form-control" rows="3" name="strength" maxlength="300"
                                                          required>
                                                    <?php echo isset($teacher_detail->strength) ? $teacher_detail->strength : ''; ?>
                                                </textarea>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /personal information -->

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
                                        <div class="form-group">
                                            <label for="datepicker" class="col-sm-2 control-label">D.O.B
                                                <span style="color: red">*</span>
                                            </label>

                                            <div class="col-sm-10">
                                                <input type="text" class="form-control pull-right" id="datepicker"
                                                       value="<?php echo isset($teacher_detail->dob) ? $teacher_detail->dob : ''; ?>"
                                                       name="dob" placeholder="DOB" required>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="landline" class="col-sm-2 control-label">Landline</label>

                                            <div class="col-sm-10">
                                                <input type="number" class="form-control pull-right" id="landline"
                                                       value="<?php echo isset($teacher_detail->landline) ? $teacher_detail->landline : ''; ?>"
                                                       name="landline" placeholder="Landline">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="mobile1" class="col-sm-2 control-label">Mobile(Primary)
                                                <span style="color: red">*</span>
                                            </label>

                                            <div class="col-sm-10">
                                                <input type="text" class="form-control pull-right" id="mobile1"
                                                       value="<?php echo isset($teacher_detail->mobile1) ? $teacher_detail->mobile1 : ''; ?>"
                                                       name="mobile1"
                                                       placeholder="Enter your mobile without country code in this format: 03xx1234567"
                                                       maxlength="11" required>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="mobile2" class="col-sm-2 control-label">Mobile(Secondry)</label>

                                            <div class="col-sm-10">
                                                <input type="text" class="form-control pull-right" id="mobile2"
                                                       value="<?php echo isset($teacher_detail->mobile2) ? $teacher_detail->mobile2 : ''; ?>"
                                                       name="mobile2" maxlength="20"
                                                       placeholder="Enter your mobile without country code in this format: 03xx1234567">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="address_line1" class="col-sm-2 control-label">Address
                                                Line1
                                                <span style="color: red">*</span>
                                            </label>

                                            <div class="col-sm-10">
                                                <input type="text" class="form-control pull-right" id="address_line1"
                                                       value="<?php echo isset($teacher_detail->address_line1) ? $teacher_detail->address_line1 : ''; ?>"
                                                       name="address_line1" placeholder="Address Line1" maxlength="255"
                                                       required>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="address_line2" class="col-sm-2 control-label">Address
                                                Line2
                                                <span style="color: red">*</span>
                                            </label>

                                            <div class="col-sm-10">
                                                <input type="text" class="form-control pull-right" id="address_line2"
                                                       value="<?php echo isset($teacher_detail->address_line2) ? $teacher_detail->address_line2 : ''; ?>"
                                                       name="address_line2" placeholder="Address Line2" maxlength="255"
                                                       required>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="address_line2" class="col-sm-2 control-label">City
                                                <span style="color: red">*</span>
                                            </label>

                                            <div class="col-sm-10">
                                                <input type="text" class="form-control pull-right" id="city" name="city"
                                                       value="<?php echo isset($teacher_detail->city) ? $teacher_detail->city : ''; ?>"
                                                       placeholder="City" maxlength="100" required>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="province" class="col-sm-2 control-label">Province
                                                <span style="color: red">*</span>
                                            </label>

                                            <div class="col-sm-10">
                                                <input type="text" class="form-control pull-right" id="province"
                                                       value="<?php echo isset($teacher_detail->province) ? $teacher_detail->province : ''; ?>"
                                                       name="province" placeholder="Province" maxlength="100" required>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="zip_code" class="col-sm-2 control-label">Zip Code
                                                <span style="color: red">*</span>
                                            </label>

                                            <div class="col-sm-10">
                                                <input type="text" class="form-control pull-right" id="zip_code"
                                                       value="<?php echo isset($teacher_detail->zip_code) ? $teacher_detail->zip_code : ''; ?>"
                                                       name="zip_code" placeholder="zip Code" maxlength="5" required>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="country" class="col-sm-2 control-label">Country
                                                <span style="color: red">*</span>
                                            </label>

                                            <div class="col-sm-10">
                                                <input type="text" class="form-control pull-right" id="country"
                                                       value="<?php echo isset($teacher_detail->country) ? $teacher_detail->country : ''; ?>"
                                                       name="country" placeholder="Country" maxlength="20" required>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- /contact information -->

                        <div id="accordion" role="tablist" aria-multiselectable="true">
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

                                        <div class="form-group">
                                            <label for="registeration_no" class="col-sm-2 control-label">Registration No
                                                <span style="color: red">*</span>

                                            </label>

                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="registeration_no"
                                                       value="<?php echo isset($teacher_detail->registeration_no) ? $teacher_detail->registeration_no : 'Unassigned'; ?>"
                                                       name="registeration_no" placeholder="Registration No"
                                                       maxlength="100" required>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="teacher_band_id" class="col-sm-2 control-label">Band
                                                Category<span style="color: red">*</span></label>

                                            <div class="col-sm-10">
                                                <select class="form-control" id="teacher_band_id" name="teacher_band_id"
                                                        required>
                                                    <?php foreach($teacher_band as $band): ?>
                                                    <option value="<?php echo $band->id; ?>"<?php if (isset($teacher_detail->teacher_band_id) && $band->id == $teacher_detail->teacher_band_id) echo "selected" ?>><?php echo $band->name; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="teacher_band_id" class="col-sm-2 control-label">Age<span style="color: red">*</span></label>

                                            <div class="col-sm-10">
                                                <select class="form-control select2"  id="age" name="age"
                                                        data-placeholder="Select Age" required>

                                                    <option value=""></option>

                                                    <option value="15"<?php if (isset($teacher_detail->age) && ($teacher_detail->age == 15)) echo "selected"; ?>>
                                                        15 Years & Above</option>
                                                    <option value="20"<?php if (isset($teacher_detail->age) && ($teacher_detail->age == 20)) echo "selected"; ?>>
                                                        20 Years & Above</option>
                                                    <option value="25"<?php if (isset($teacher_detail->age) && ($teacher_detail->age == 25)) echo "selected"; ?>>
                                                        25 Years & Above</option>
                                                    <option value="30"<?php if (isset($teacher_detail->age) && ($teacher_detail->age == 30)) echo "selected"; ?>>
                                                        30 Years & Above</option>
                                                    <option value="35"<?php if (isset($teacher_detail->age) && ($teacher_detail->age == 35)) echo "selected"; ?>>
                                                        35 Years & Above</option>
                                                    <option value="40"<?php if (isset($teacher_detail->age) && ($teacher_detail->age == 40)) echo "selected"; ?>>
                                                        40 Years & Above</option>
                                                    <option value="45"<?php if (isset($teacher_detail->age) && ($teacher_detail->age == 45)) echo "selected"; ?>>
                                                        45 Years & Above</option>
                                                    <option value="50"<?php if (isset($teacher_detail->age) && ($teacher_detail->age == 50)) echo "selected"; ?>>
                                                        50 Years & Above</option>

                                                </select>
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <label for="other_detail" class="col-sm-2 control-label">Other
                                                Details</label>

                                            <div class="col-sm-10">
                                                <textarea class="form-control" rows="3" name="other_detail"
                                                          maxlength="300">
                                                    <?php echo isset($teacher_detail->other_detail) ? $teacher_detail->other_detail : ''; ?></textarea>
                                            </div>
                                        </div>
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
                                            <label for="labels" class="col-sm-2 control-label">Is Active ?</label>

                                            <div class="col-sm-10">
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

                                        <div class="form-group">
                                            <label for="labels" class="col-sm-2 control-label">Is Approved ?</label>

                                            <div class="col-sm-10">
                                                <select class="form-control select2" id="is_approved" name="is_approved"
                                                        data-placeholder="Please Select"  >

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
                            </div>

                        </div>

                        <!-- /other detail -->
                        <div class="box-footer">
                            <a href="{{url('admin/teachers')}}" class="btn btn-warning pull-right">
                                <i class="fa fa-w fa-remove"></i> Cancel
                            </a>
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

</body>
@endsection
@include('layouts.partials.modal')
@section('page_specific_styles')
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
    <link rel="stylesheet" href="https://jqueryvalidation.org/files/demo/site-demos.css">
@endsection

@section('page_specific_scripts')
        <!-- FastClick -->
    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('plugins/jQuerymask/jquery.mask.min.js') }}"></script>
    <script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>

    <script src="{{asset('/js/jquery.validate.min.js')}}"></script>
    <script src="{{asset('/js/additional-methods.min.js')}}"></script>
@endsection

@section('page_specific_inline_scripts')


    <script>

        jQuery(document).ready(function () {

            $(".upload-demo").change(function() {

                var val = $(this).val();

                switch(val.substring(val.lastIndexOf('.') + 1).toLowerCase()){
                    case 'gif': case 'jpg': case 'png':
                    //alert("an image");
                    break;
                    default:
                        $(this).val('');
                        // error message here
                        alert("Please select an image");
                        break;
                }
            });

            var password = document.getElementById("password")
                    , confirm_password = document.getElementById("confirm_password");

            function validatePassword() {
                if (password.value != confirm_password.value) {
                    confirm_password.setCustomValidity("Passwords Don't Match");
                } else {
                    confirm_password.setCustomValidity('');
                }
            }

            password.onchange = validatePassword;
            confirm_password.onkeyup = validatePassword;

            //Initialize Select2 Elements
            $(".select2").select2();


            $('.select2').change(function () {
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
            $('#cnic_number').mask("00000-0000000-0", {placeholder: "_____-________-_"});
            //$('#expected_minimum_fee').mask("00000");
            $('#no_of_children').mask("000");
            $('#zip_code').mask("00000");
            //$('#experience').mask("0.00", {placeholder: "0.00"});

            jQuery('.btn-photo').on('click', function (event) {
                jQuery('.teacher-image').toggle('show');
            });

            jQuery('.btn-cnic-back').on('click', function (event) {
                jQuery('.teacher-cnic-back').toggle('show');
            });

            jQuery('.btn-cnic-front').on('click', function (event) {
                jQuery('.teacher-cnic-front').toggle('show');
            });

        });

        jQuery(document).ready(function ($) {

            $(document).on("click", ":submit", function (e) {
                $("#submitbtnValue").val($(this).val());
            });


            $('#teacher-post').on('submit', function (e) {

                e.preventDefault();

                var formData = new FormData($(this)[0]);
                $.ajax({

                    url: '{{url("teacher-post")}}',
                    type: "POST",
                    data: formData,
                    async: true,
                    beforeSend: function () {
                        $("#wait").modal();
                    },

                    success: function (data) {

                        $('#wait').modal('hide');

                        //alert(data['success']);
                        console.log(data['success']['email']);
                        var teacherid = data['teacherid'];
                        var success = data['success'];
                        var error = data['success']['email'];


                        if (success == 'saveandadd') {

                            var redirect_url = '{{url("admin/teachers/add")}}';
                            $(".modal-footer").append($('<a class="btn btn-outline" ' +
                                    'href="' + redirect_url + '">OK</a>'));
                            $('#myModal').modal();

                        } else if (success == 'save') {

                            var redirect_url = '{{url("admin/teachers")}}';
                            $(".modal-footer").append($('<a class="btn btn-outline" ' +
                                    'href="' + redirect_url + '">OK</a>'));
                            $('#myModal').modal();

                        } else {
                            $('#warning').modal();
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
            autoclose: true
        });
    </script>


@endsection

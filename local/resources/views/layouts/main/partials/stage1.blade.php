<article class="a-padd works11">
    <div class="container">
        @include('layouts.main.partials.wizard')

        <div class="row setup-content" id="step-1">
            <div class="box box-danger">
                <div class="box-header with-border">
                    <h3 class="box-title">Personal Information</h3>
                </div>
                <!-- /.box-header -->
                <form method="post" action="{{ url('/tutorsignup/step2') }}" enctype="multipart/form-data" id="signupform" >
                    <input type="hidden" name="step" value="2">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="tuid" value="<?php echo !empty(session('tuid'))?session('tuid'):''; ?>">
                    <input type="hidden" name="teacherid" value="<?php echo !empty(session('teacherid'))?session('teacherid'):''; ?>">
                    <div class="box-body">

                        <!-- Full Name and Email -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="firstname" class="col-sm-4 control-label">
                                        Full Name<span style="color: red">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control " id="fullname" name="fullname"
                                               value="<?php echo !empty($step1Data)?$step1Data['fullname']:''; ?>"  placeholder="Enter Full Name" maxlength="100">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="col-sm-4 control-label">Email<span
                                                style="color: red">*</span></label>

                                    <div class="col-sm-8">
                                        <input type="email" class="form-control" id="email" name="email"
                                               value="<?php echo !empty($step1Data['email'])?$step1Data['email']:''; ?>"
                                               placeholder="Email" maxlength="100">
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
                                            name="password" placeholder="password" minlength="6" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="confirm_password" class="col-sm-4 control-label">Confirm
                                        Password<span
                                                style="color: red">*</span></label>

                                    <div class="col-sm-8">
                                        <input type="password" class="form-control" id="confirm_password"
                                               name="confirm_password"placeholder="Confirm Password" required>
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
                                               name="father_name" placeholder="Father's/Husband Name" maxlength="100"
                                               value="<?php echo !empty($step1Data)?$step1Data['father_name']:''; ?>"  required>
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-6">

                                <div class="form-group">
                                    <label for="gender_id" class="col-sm-4 control-label">
                                        Gender<span style="color: red;">*</span></label>

                                    <div class="col-sm-8">
                                        <select class="form-control select2" id="gender_id"
                                                data-placeholder="Select Gender" required
                                                name="gender_id">
                                            <option value=""></option>
                                            <?php foreach($genders as $gender): ?>
                                            <option value="<?php echo $gender->id; ?>"
                                            <?php if (!empty($step1Data) && $step1Data['gender_id'] == $gender->id) {
                                                echo "selected"; } ?>><?php echo $gender->name; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                            </div>

                        </div>
                        <!-- Gender and Father Name -->

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
                                            <option value="morning" <?php if (!empty($step1Data) && $step1Data['suitable_timings'] == 'morning') {
                                                echo "selected"; } ?>>
                                                Morning
                                            </option>
                                            <option value="evening"  <?php if (!empty($step1Data) && $step1Data['suitable_timings'] == 'evening') {
                                                echo "selected"; } ?>>
                                                Evening
                                            </option>
                                            <option value="anytime" <?php if (!empty($step1Data) && $step1Data['suitable_timings'] == 'anytime') {
                                                echo "selected"; } ?>>
                                                Both(Morning & Evening)
                                            </option>
                                        </select>
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
                                            <option value="own" <?php if (!empty($step1Data) && $step1Data['livingin'] == 'own') {
                                                echo "selected"; } ?>>
                                                Own House
                                            </option>
                                            <option value="rent" <?php if (!empty($step1Data) && $step1Data['livingin'] == 'rent') {
                                                echo "selected"; } ?>>
                                                Rented House
                                            </option>
                                            <option value="hostel"<?php if (!empty($step1Data) && $step1Data['livingin'] == 'hostel') {
                                                echo "selected"; } ?>>
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

                                            <option value="15"<?php if (!empty($step1Data) && $step1Data['age'] == 15) {
                                                echo "selected"; } ?>>
                                                15 plus
                                            </option>

                                            <option value="25"<?php if (!empty($step1Data) && $step1Data['age'] == 25) {
                                                echo "selected"; } ?>>
                                                25 plus
                                            </option>
                                            <option value="30"<?php if (!empty($step1Data) && $step1Data['age'] == 30) {
                                                echo "selected"; } ?>>
                                                30 plus
                                            </option>
                                            <option value="35"<?php if (!empty($step1Data) && $step1Data['age'] == 35) {
                                                echo "selected"; } ?>>
                                                35 plus
                                            </option>
                                            <option value="40"<?php if (!empty($step1Data) && $step1Data['age'] == 40) {
                                                echo "selected"; } ?>>
                                                40 plus
                                            </option>
                                            <option value="50"<?php if (!empty($step1Data) && $step1Data['age'] == 50) {
                                                echo "selected"; } ?>>
                                                50 plus
                                            </option>
                                            <option value="60"<?php if (!empty($step1Data) && $step1Data['age'] == 60) {
                                                echo "selected"; } ?>>
                                                60 plus
                                            </option>

                                        </select>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-6">

                                <div class="form-group">
                                    <label for="experience" class="col-sm-4 control-label" style="max-width: 100%;">
                                        Your Teaching Experience Period<span style="color:red;">*</span></label>

                                    <div class="col-sm-8">
                                        <select class="form-control select2" id="experience"
                                                name="experience" required
                                                data-placeholder="Select Experience">
                                            <option value=""></option>
                                            <option value="15"<?php if (!empty($step1Data) && $step1Data['experience'] == '15') {
                                                echo "selected"; } ?>>
                                                Fifteen Years Plus
                                            </option>
                                            <option value="10"<?php if (!empty($step1Data) && $step1Data['experience'] == '10') {
                                                echo "selected"; } ?>>
                                                Ten Years Plus
                                            </option>
                                            <option value="5"<?php if (!empty($step1Data) && $step1Data['experience'] == '5') {
                                                echo "selected"; } ?>>
                                                Five Years Plus
                                            </option>
                                            <option value="1"<?php if (!empty($step1Data) && $step1Data['experience'] == '1') {
                                                echo "selected"; } ?>>
                                                One Years Plus
                                            </option>
                                            <option value="0.5"<?php if (!empty($step1Data) && $step1Data['experience'] == '0.5') {
                                                echo "selected"; } ?>>
                                                less then one year
                                            </option>
                                            <option value="0"<?php if (!empty($step1Data) && $step1Data['experience'] == '0') {
                                                echo "selected"; } ?>>
                                                Fresh
                                            </option>
                                        </select>

                                    </div>
                                </div>

                            </div>

                        </div>
                        <!-- Age and Expereince -->

                        <!-- Marital Status and Religion -->
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
                                            <option value="<?php echo $marital->id; ?>"<?php if (!empty($step1Data) && $step1Data['marital_status_id'] == $marital->id) {
                                                echo "selected"; } ?>>
                                                <?php echo $marital->name; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-6">

                                <div class="form-group">
                                    <label for="added_by" class="col-sm-4 control-label">
                                        Religion</label>

                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="religion"
                                              value="<?php if (isset($step1Data['religion'])){echo $step1Data['religion']; }?>" name="religion"  placeholder="Religion" maxlength="20" required>
                                    </div>
                                </div>

                            </div>

                        </div>
                        <!-- Marital Status and Religion -->

                        <!-- Religion and Minimum Fee -->
                        <div class="row">

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
                                            <?php if (isset($step1Data['expected_minimum_fee']) && $step1Data['expected_minimum_fee'] == 4) echo "selected"; ?> >4000 to 8000</option>
                                            <option value="8"
                                            <?php if (isset($step1Data['expected_minimum_fee']) && $step1Data['expected_minimum_fee'] == 8) echo "selected"; ?> >8000 to 12000</option>
                                            <option value="12"
                                            <?php if (isset($step1Data['expected_minimum_fee']) && $step1Data['expected_minimum_fee'] == 12) echo "selected"; ?> >12000 to 15000</option>
                                            <option value="15"
                                            <?php if (isset($step1Data['expected_minimum_fee']) && $step1Data['expected_minimum_fee'] == 15) echo "selected"; ?> >15000 to 20000</option>
                                            <option value="20"
                                            <?php if (isset($step1Data['expected_minimum_fee']) && $step1Data['expected_minimum_fee'] == 20) echo "selected"; ?> >20000 to 30000</option>
                                            <option value="30"
                                            <?php if (isset($step1Data['expected_minimum_fee']) && $step1Data['expected_minimum_fee'] == 30) echo "selected"; ?> >30000 to 40000</option>

                                        </select>
                                    </div>
                                </div>

                            </div>

                        </div>
                        <!-- Religion and Minimum Fee -->

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
                                                  maxlength="300"><?php if (isset($step1Data['reference_for_rent'])){echo $step1Data['reference_for_rent']; }?></textarea>
                                    </div>
                                </div>

                            </div>

                        </div>
                        <!-- Teacher reference for rented house -->

                        <!-- Past Teaching Experience -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="past_experience" class="col-sm-12 control-label" style="text-align: left">
                                        Write about your past teaching experiecne</label>

                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <textarea class="form-control" rows="10"
                                             name="past_experience"><?php if (isset($step1Data['past_experience'])){echo $step1Data['past_experience']; }?></textarea>
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
                                                  maxlength="1000"><?php if (isset($step1Data['strength'])){echo $step1Data['strength']; }?></textarea>
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
                                        <textarea class="form-control" rows="3" name="reference_for_rent" maxlength="300"><?php if (isset($step1Data['reference_for_rent'])){echo $step1Data['reference_for_rent']; }?></textarea>
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
                                        <textarea class="form-control" rows="3" name="reference_gurantor" required
                                                  maxlength="300"><?php if (isset($step1Data['reference_gurantor'])){echo $step1Data['reference_gurantor']; }?></textarea>
                                    </div>
                                </div>

                            </div>

                        </div>
                        <!-- Gurantor Reference -->

                        <!-- Your About Us -->
                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="about_us" class="col-sm-12 control-label" style="text-align: left">Comments About Us(MAX 1000 Words)</label>
                                </div>

                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <textarea class="form-control" rows="3" name="about_us"
                                                   maxlength="1000"><?php if (isset($step1Data['about_us'])){echo $step1Data['about_us']; }?></textarea>
                                    </div>
                                </div>

                            </div>

                        </div>
                        <!-- Your About Us -->

                        <button type="submit" class="btn btn11 outline pull-right">Next Step</button>
                    </div>
                    <!-- /.box-body -->
                </form>
            </div>
        </div>

    </div>
</article>
<article class="a-padd works11">
    <div class="container">
        @include('layouts.main.partials.wizard')

        <div class="row setup-content" id="step-2">
            <div class="box box-danger">
                <div class="box-header with-border">
                    <h3 class="box-title">Contact Information</h3>
                </div>
                <!-- /.box-header -->
                <form method="post" action="{{ url('/tutorsignup/step3') }}" enctype="multipart/form-data" id="stage2form"  >

                    <input type="hidden" name="step" value="3">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="tuid" value="<?php echo !empty(session('tuid'))?session('tuid'):''; ?>">
                    <input type="hidden" name="teacherid" value="<?php echo !empty(session('teacherid'))?session('teacherid'):''; ?>">

                    <div class="box-body">



                        <!-- DOB and LandLine -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group step2-overflow">
                                    <label for="firstname" class="col-sm-4 control-label">
                                        D.O.B<span style="color: red;">*</span></label>

                                    <div class="col-sm-8">
                                        <input type="text" class="form-control pull-right dob-pointer"
                                               id="datepicker"
                                               value="<?php echo !empty($step2Data)?$step2Data['dob']:''; ?>"
                                               name="dob" placeholder="D.O.B" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group step2-overflow">
                                    <label for="email" class="col-sm-4 control-label" style="max-width: 100%">
                                        Contact No(Home PTCL)</label>

                                    <div class="col-sm-8">
                                        <input type="text" class="form-control pull-right"
                                               id="landline" name="landline" placeholder="Contact No(Home PTCL)"
                                               value="<?php echo !empty($step2Data)?$step2Data['landline']:''; ?>" >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- DOB and LandLine -->

                        <!-- Mobile Primary and Secondry -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group step2-overflow">
                                    <label for="mobile1" class="col-sm-4 control-label" style="max-width: 100%">
                                        Personal Contact No<span style="color: red">*</span></label>

                                    <div class="col-sm-8">
                                        <input type="text" class="form-control pull-right" id="mobile1"
                                               value="<?php echo !empty($step2Data)?$step2Data['mobile1']:''; ?>" name="mobile1"
                                               placeholder="Personal Contact No" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group step2-overflow">
                                    <label for="personal_contactno2" style="max-width: 100%"
                                           class="col-sm-4 control-label">Personal Contact No2.</label>

                                    <div class="col-sm-8">
                                        <input type="text" class="form-control pull-right" id="personal_contactno2"
                                               value="<?php echo !empty($step2Data)?$step2Data['personal_contactno2']:''; ?>"
                                               name="personal_contactno2"  placeholder="Personal Contact No2">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- Mobile Primary and Secondry -->

                        <!-- Father/Husband and emergency number -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group step2-overflow">
                                    <label for="guardian_contact_no" class="col-sm-4 control-label" style="max-width: 100%">
                                        Father/Husband's Contact No<span style="color: red;">*</span>
                                    </label>

                                    <div class="col-sm-8">
                                        <input type="text" class="form-control pull-right" id="guardian_contact_no"
                                               value="<?php echo !empty($step2Data)?$step2Data['guardian_contact_no']:''; ?>" name="guardian_contact_no"
                                               placeholder="Father/Husband's Contact No" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group step2-overflow">
                                    <label for="email" style="max-width: 100%"
                                           class="col-sm-4 control-label">Any Other Emergency Contact No</label>

                                    <div class="col-sm-8">
                                        <input type="text" class="form-control pull-right" id="mobile2"
                                               value="<?php echo !empty($step2Data)?$step2Data['mobile2']:''; ?>"
                                               name="mobile2"  placeholder="Any Other Emergency Contact No">
                                    </div>
                                </div>
                            </div>


                        </div>
                        <!-- Father/Husband and emergency number -->

                        <!-- CNIC number -->
                        <div class="row">
                            <div class="col-md-6">

                                <div class="form-group step2-overflow">
                                    <label for="cnic_number" class="col-sm-4 control-label">
                                        CNIC No <span style="color: red;">*</span></label>

                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="cnic_number"
                                               value="<?php echo !empty($step2Data)?$step2Data['cnic_number']:''; ?>"
                                               name="cnic_number" placeholder="_____-________-_"
                                               maxlength="15" required>
                                    </div>
                                </div>

                            </div>


                        </div>
                        <!-- CNIC number -->

                        <!-- CNIC Number and  Personal Photo -->
                        <div class="row">

                            <div class="col-md-6">

                                <div class="form-group step2-overflow">
                                    <label for="teacher_photo" class="col-sm-4 control-label" style="max-width: 100%">
                                        Upload Personal Photo</label>

                                    <div class="col-sm-8">

                                        <input type="file" id="teacher_photo" name="teacher_photo"
                                               class="upload-demo">
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

                                <div class="form-group step2-overflow">
                                    <label for="electricity_bill" class="col-sm-4 control-label" style="max-width: 100%">
                                        Scanned copy of electricity bill</label>

                                    <div class="col-sm-8">

                                        <input type="file" id="electricity_bill" name="electricity_bill"
                                               class="upload-demo">
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
                                    <label for="cnic_front_image" class="col-sm-4 control-label" style="max-width: 100%">
                                        CNIC Scanned Front Image <span style="color: red;">*</span></label>

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
                                    <label for="cnic_back_image" class="col-sm-4 control-label" style="max-width: 100%">
                                        CNIC Scanned Back Image<span style="color: red;">*</span></label>

                                    <div class="col-sm-8">
                                        <input type="file" id="cnic_back_image" name="cnic_back_image"
                                               class="upload-demo" <?php if(empty($teacher_detail->cnic_back_image)) echo 'required'; ?> >
                                        <?php if(!empty($teacher_detail->cnic_back_image)) :?>
                                        <label for="" class="col-sm-6 control-label"
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

                        <!-- Permanent Address -->
                        <div class="h-holder">
                            <div class="heading">Permanent Address Detail
                                <span style="color: red;">*</span></div>

                            <!-- address-line one and two input-->
                            <div class="row step2-overflow">
                                <div class="col-md-12">
                                    <div class="form-group ">
                                        <label for="address_line1" class="col-sm-2 control-label">
                                            Address <span style="color:red;">*</span></label>

                                        <div class="col-sm-10">
                                            <textarea name="address_line1" id="address_line1" class="form-control"  maxlength="300" required
                                              placeholder="Permanent Address(As on CNIC)" rows="2"><?php echo !empty($step2Data)?$step2Data['address_line1']:''; ?></textarea>

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
                                                    name="province" required
                                                    data-placeholder="Select Province">
                                                <option value=""></option>
                                                <?php foreach($provinces as $province): ?>
                                                <option value="<?php echo $province->id; ?>"
                                                <?php if(isset($step2Data) && $step2Data['province']==$province->id ) echo "selected"; ?>  >
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
                                                    required  data-placeholder="Select City">
                                                <option value=""></option>
                                                <?php foreach($cities as $city): ?>
                                                <option value="<?php echo $city->id; ?>"
                                                <?php if(isset($step2Data) && $step2Data['city']==$city->id ) echo "selected"; ?> >
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
                                                   id="zip_code" name="zip_code" placeholder="zip Code"
                                                   value="<?php echo !empty($step2Data)?$step2Data['zip_code']:''; ?>" maxlength="10">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="country" class="col-sm-4 control-label">
                                            Country<span style="color:red;">*</span></label>

                                        <div class="col-sm-8">

                                            <select class="form-control select2" id="country"
                                                    name="country" required
                                                    data-placeholder="Select Country">
                                                <option value=""></option>
                                                <option value="Pakistan" selected>
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
                            <div class="heading">Present Address Detail<span style="color:red;">*</span></div>

                            <!-- address-line one and two input-->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="address_line1_p" class="col-sm-2 control-label">
                                            Address<span style="color:red;">*</span></label>

                                        <div class="col-md-10">
                                            <textarea name="address_line1_p" id="address_line1_p"   maxlength="300" required
                                                  placeholder="Enter Your Present Address"    class="form-control" rows="2"><?php echo !empty($step2Data)?$step2Data['address_line1_p']:''; ?></textarea>

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
                                            <select class="form-control select2" id="province"
                                                    name="province_p" required
                                                    data-placeholder="Select Province">
                                                <option value=""></option>
                                                <?php foreach($provinces as $province): ?>
                                                <option value="<?php echo $province->id; ?>"
                                                <?php if(isset($step2Data) && $step2Data['province_p']==$province->id ) echo "selected"; ?> >
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
                                                <?php if(isset($step2Data) && $step2Data['city_p']==$province->id ) echo "selected"; ?> >
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
                                                   id="zip_code_p" name="zip_code_p" placeholder="zip Code"
                                                   value="<?php echo !empty($step2Data)?$step2Data['zip_code_p']:''; ?>"
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
                                                <option value="Pakistan" selected >
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

                        <button type="submit" class="btn btn11 outline pull-right">Next Step</button>
                        <a href="<?php echo URL::to('tutorsignup');  ?>" class="btn btn11 outline pull-left">Previous Step</a>
                    </div>
                    <!-- /.box-body -->
                </form>
            </div>
        </div>

    </div>
</article>

@section('page_specific_inline_scripts')

    @if (session('status'))
        <script>
            $(document).ready(function() {
                toastr.success('{{session('status')}}');
            });
        </script>
    @endif

    <script>
        $(document).ready(function() {
            $(".select2").select2();
            //Date picker
            $('#datepicker').datepicker({
                autoclose: true,
            });

            $('#landline').mask('00000000000');
            $('#mobile1').mask('00000000000');
            $('#mobile2').mask('00000000000');
            $('#personal_contactno2').mask('00000000000');
            $('#guardian_contact_no').mask('00000000000');
            $('#cnic_number').mask("00000-0000000-0", {placeholder: "_____-________-_"});

            $("#stage2form").validate({
                rules: {

                    cnic_front_image : {required: true},
                    cnic_back_image : {required: true},
                    dob : {required: true},
                    mobile1 : {required: true},
                    guardian_contact_no : {required: true},
                    cnic_number : {required: true},
                    address_line1 : {required: true},
                    province : {required: true},
                    city : {required: true},
                    address_line1_p : {required: true},
                    province_p : {required: true},
                    city_p : {required: true},


                },
                messages: {
                    cnic_front_image: "Please Upload CNIC Scanned Front Image",
                    cnic_back_image: "Please Upload CNIC Scanned Back Image",
                    dob: "Please Enter Your DOB",
                    mobile1: "Please Enter Mobile No",
                    cnic_number: "Please Enter CNIC NO",
                    address_line1: "Please Enter Permanent Address(As on CNIC)",
                    province: "Please Select Province",
                    city: "Please Select City",
                    address_line1_p: "Please Enter Your Present Address",
                    province_p: "Please Select Province",
                    city_p: "Please Select City",


                },
                tooltip_options: {
                    cnic_front_image: {trigger:'focus'},
                    cnic_back_image: {trigger:'focus'},
                    dob: {trigger:'focus'},
                    mobile1: {trigger:'focus'},
                    guardian_contact_no: {trigger:'focus'},
                    cnic_number: {trigger:'focus'},
                    address_line1: {trigger:'focus'},
                    province: {trigger:'focus'},
                    city: {trigger:'focus'},
                    address_line1_p: {trigger:'focus'},
                    province_p: {trigger:'focus'},
                    city_p: {trigger:'focus'},


                },
            });

            //load cities on provice select
            $(document).on('change', "#province", function () {

                var id = this.value;
                $("#wait").modal();
                $.ajax({

                    url: '{{url('province/cities')}}',
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

        });
    </script>
@endsection
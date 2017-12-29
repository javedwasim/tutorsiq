<div class="col-lg-9">

    <div class="box detail-box">
        <div class="teacher-box-holder">
            <div class="img-holder desktop-show" style="width: 100px">
                <?php if(isset($details->gender_id)&&($details->gender_id==1)): ?>
                <img src="{{URL::asset("img/male_avatar.png")}}"
                     alt="profile Pic" class="img-circle teacher-photo">
                <?php elseif(isset($details->gender_id)&&($details->gender_id==2)): ?>
                <img src="{{URL::asset("img/female_avatar.png")}}"
                     alt="profile Pic" class="img-circle teacher-photo">
                <?php endif; ?>

            </div>
            <div class="row">
                <div class="col-xl-7 col-lg-12">
                    <div class="img-holder mobile-show">
                        <?php if(isset($details->gender_id)&&($details->gender_id==1)): ?>
                        <img src="{{URL::asset("img/male_avatar.png")}}"
                             alt="profile Pic" class="img-circle teacher-photo">
                        <?php elseif(isset($details->gender_id)&&($details->gender_id==2)): ?>
                        <img src="{{URL::asset("img/female_avatar.png")}}"
                             alt="profile Pic" class="img-circle teacher-photo">
                        <?php endif; ?>
                    </div>
                    <div class="inner-holder">
                        <h3 class="name"><?php echo isset($details->registeration_no) && !empty($details->registeration_no) ? $details->registeration_no : ""; ?></h3>
                        <strong class="qua-title"><?php echo isset($qualifications->name) && !empty($qualifications->name) ? $qualifications->name : ""; ?></strong>
                        <div class="ratings">
                            <ul class="list-inline">
                                <li class="light"><i class="fa fa-star"></i></li>
                                <li class="light"><i class="fa fa-star"></i></li>
                                <li class="light"><i class="fa fa-star"></i></li>
                                <li class="light"><i class="fa fa-star"></i></li>
                                <li class="light"><i class="fa fa-star"></i></li>
                            </ul>
                        </div>
                        <div class="button">
                            <form action="{{ url('teacher-details/tutor/request') }}" method="post" id="request_tutor">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="teacherid" value="<?php echo $id; ?>">
                                <input type="hidden" name="studentid" value="<?php echo $studentid; ?>">
                                <button type="submit" class="btn btn11 outline">Request Tutor</button>
                            </form>
                        </div>
                        <?php if(isset($details->expected_minimum_fee)): ?>
                        <div class="fee-package">
                            <span>PKR</span>
                            <strong><?php echo $details->expected_minimum_fee . "K - " . $details->expected_max_fee . "K"; ?></strong>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-xl-5 col-lg-12">
                    <table class="table">
                        <tbody>
                        <?php if(isset($details->age)): ?>
                        <tr>
                            <td><b>Age:</b></td>
                            <td class="type-info"><?php echo $details->age; ?></td>
                        </tr>
                        <?php endif; ?>

                        <?php if(isset($details->experience)): ?>
                        <tr>
                            <td><b>Experience:</b></td>
                            <td class="type-info"><?php echo $details->experience . " Years"; ?></td>
                        </tr>
                        <?php endif; ?>

                        <?php if(isset($details->marital_status_id)): ?>
                        <tr>
                            <td><b>Marital Status:</b></td>
                            <td class="type-info"><?php echo $details->marital_status_id == 1 ? 'Married' : 'Single'; ?></td>
                        </tr>
                        <?php endif; ?>

                        <tr>
                            <td><b>Status:</b></td>
                            <td class="type-info">
                                <?php if(isset($details->is_active)): ?>
                                <span class="badge badge-success badge-pill">Available</span>
                                <?php endif; ?>
                            </td>
                        </tr>

                        <?php if(isset($details->city_name)): ?>
                        <tr>
                            <td><b>Location:</b></td>
                            <td class="type-info"><?php echo $details->city_name . ", Pakistan"; ?></td>
                        </tr>
                        <?php endif; ?>

                        <?php if(isset($details->religion)): ?>
                        <tr>
                            <td><b>Religion:</b></td>
                            <td class="type-info"><?php echo $details->religion; ?></td>
                        </tr>
                        <?php endif; ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <div class="detail-nav">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#profile" role="tab">Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#experience" role="tab">Job Experience</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#location" role="tab">Preferred Locations</a>
            </li>
        </ul>
    </div>
    <div class="box">

        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane active" id="profile" role="tabpanel">
                <div class="detail-box-inner">

                    <!-- Teacher Strength -->
                    <?php if(isset($details->strength) && !empty($details->strength)): ?>
                    <div class="hold">
                        <h4>About</h4>
                        <p><?php echo $details->strength; ?></p>
                    </div>
                    <?php endif; ?>
                <!-- Preferred Subjects -->

                    <?php if(isset($subjects) && !empty($subjects)): ?>
                    <div class="hold">
                        <h4 class="subject">Prefered Subject</h4>
                        <ul class="subject list-inline">
                        <?php foreach ($subjects as $subject): $classSubjects = explode(":", $subject->subjects);?>
                        <!-- clss name -->
                            <li class="bold"><?php echo $classSubjects[0]; ?></li>
                            <!-- clss subjects -->
                            <li><?php echo $classSubjects[1]; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>

                <!-- Teacher Education -->
                    <?php if(isset($qdetails) && !empty($qdetails)): ?>
                    <div class="hold">
                        <h4 class="education">Education</h4>
                        <?php foreach($qdetails as $qualification): ?>

                        <div class="edu">
                            <strong><?php echo $qualification->qualification_name; ?></strong>
                            <span><?php echo $qualification->institution; ?></span>
                            <span class="date"><?php echo $qualification->passing_year; ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                <!-- Tuition Categories -->
                    <?php if(isset($tuitionCategories) && !empty($tuitionCategories)): ?>
                    <div class="hold">
                        <h4 class="skills">Subjects/Categories</h4>
                        <ul class="skill-list list-inline">
                            <?php foreach ($tuitionCategories as $category): ?>
                            <li><?php echo $category->name; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>

                    <?php if(isset($details->suitable_timings)): ?>
                    <div class="hold">
                        <h4 class="timings">Timings</h4>
                        <span class="timing"><?php echo $details->suitable_timings; ?></span>
                    </div>
                    <?php endif; ?>

                <!-- Tuition Categories -->
                    <?php if(isset($institutes) && !empty($institutes)): ?>
                    <div class="hold">
                        <h4 class="institute">Prefered Institution</h4>
                        <div class="institute-hold">
                            <div class="row">
                                <?php foreach($institutes as $institute): ?>
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <img src="{{ asset("img/$institute->logo") }}" alt="image">
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="tab-pane job-exp" id="experience" role="tabpanel">
                <div class="detail-box-inner">
                    <div class="hold">
                        <?php if(isset($details->past_experience)): ?>
                        <div class="edu">
                            <p><?php echo $details->past_experience; ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="location" role="tabpanel">
                <div class="detail-box-inner">
                    <?php if(isset($locations) && !empty($locations)): ?>
                    <div class="hold">
                        <h4 class="location">Preferred Location</h4>
                        <?php foreach ($locations as $location): $zloc = explode(':', $location->zone_locations);  ?>
                        <div class="zone">
                            <h5><?php echo $zloc[0]; ?></h5>
                            <ul class="list-inline">
                                <?php $locations = explode(",", $zloc[1]);
                                //dd($locations);
                                for($i = 0;$i < count($locations);$i++){

                                ?>
                                <li><?php echo $locations[$i]; ?></li>
                                <?php } ?>
                            </ul>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>

@section('page_specific_scripts')

    <script src="{{ asset('plugins/jQuery/jQuery-2.1.4.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>

@endsection


@section('page_specific_inline_scripts')
    <script>
        jQuery(document).ready(function ($) {

            $('#request_tutor').on('submit', function (e) {

                e.preventDefault();
                var formData = new FormData($(this)[0]);

                $.ajax({

                    url: 'tutor/request',
                    type: "post",
                    data: formData,
                    beforeSend: function () {
                        $("#wait").modal();
                    },
                    success: function (data) {

                        $('#wait').modal('hide');

                        $(".request-tutor").empty();
                        $('.request-tutor').html(data);
                        $('#request-tutor').modal();


                    },
                    cache: false,
                    contentType: false,
                    processData: false

                });

            });



        });

    </script>

@endsection
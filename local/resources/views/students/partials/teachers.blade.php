<div class="col-xl-6 col-lg-12">
    <?php foreach($teachers as $teacher): ?>
        <div class="main-box box">
            <div class="teacher-box-holder">
                <div class="img-holder">
                    <?php if(isset($teacher->gender_id)&&($teacher->gender_id==1)): ?>
                        <img src="{{URL::asset("img/male_avatar.png")}}"
                             alt="profile Pic" class="img-circle teacher-photo">
                    <?php elseif(isset($teacher->gender_id)&&($teacher->gender_id==2)): ?>
                        <img src="{{URL::asset("img/female_avatar.png")}}"
                             alt="profile Pic" class="img-circle teacher-photo">
                    <?php endif; ?>
                </div>
                <div class="inner-holder">
                    <h3 class="name"><?php echo isset($teacher->registeration_no) ? $teacher->registeration_no : ''; ?></h3>
                    <strong class="qua-title"><?php echo $teacher->qualifications; ?></strong>
                    <div class="ratings">
                        <ul class="list-inline">
                            <li class="light"><i class="fa fa-star"></i></li>
                            <li class="light"><i class="fa fa-star"></i></li>
                            <li class="light"><i class="fa fa-star"></i></li>
                            <li class="light"><i class="fa fa-star"></i></li>
                            <li class="light"><i class="fa fa-star"></i></li>
                        </ul>
                    </div>
                    <p><?php echo $teacher->strength; ?></p>
                    <div class="fee-package">
                        <span>PKR</span>
                        <strong><?php echo $teacher->expected_minimum_fee."K - ".$teacher->expected_max_fee."K"; ?></strong>
                    </div>
                </div>
                <div class="bottom">
                    <ul class="list-inline bottom-detail">
                        <li><div class="age">Age: <span><?php echo $teacher->age; ?></span></div></li>
                        <li><div class="experience">Experience: <span><?php echo $teacher->experience." Years"; ?></span></div></li>
                        <li><div class="view">Views: <span>280</span></div></li>
                        <li><div class="location">Location:<span> <?php echo $teacher->city_name." Pakistan"; ?></span></div></li>
                        <li><div class="location">Religion:<span> <?php echo $teacher->religion; ?></span></div></li>
                        <li class="button pull-right"><a href="<?php echo url('/')."/teacher-details/".$teacher->id; ?>" class="btn btn11 outline btn-sm">View Details</a></li>
                    </ul>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <!-- Teachers Search Box -->


    <div class="custom-pag">
        <?php echo $teachers->render(); ?>
    </div>
    <div class="" style="display:inline-block;font-size: 14px;font-family: 'Source Sans Pro', sans-serif;">Showing
        <?php echo isset($offset) ? $offset : ''; ?> to
        <?php echo isset($perpage_record) ? $perpage_record : ''; ?> of
        <?php echo $count; ?> entries
    </div>
</div>
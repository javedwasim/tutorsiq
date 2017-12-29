<?php $tutorsignup = Request::segment(1); ?>
<?php if(!isset($user) && $tutorsignup == 'tutorsignup' ) : ?>
<div class="alert alert-danger alert-dismissible">
    <h4><i class="icon fa fa-info"></i> Are You Tutor , Looking for Home Tutoring Jobs ? Sign up here!</h4>
</div>
<br>
<?php endif; ?>
<div class="stepwizard col-md-offset-3">

    <div class="stepwizard-row setup-panel">
        <div class="stepwizard-step">
            <a href="#step-1"
               class="<?php if (isset($step) && ($step == 1)) {
                   echo "btn btn-red btn-circle";
               } else {
                   echo "btn btn-default btn-circle disabled";
               } ?>">1</a>
            <p>Step 1</p>
        </div>
        <div class="stepwizard-step">
            <a href="#step-2"
               class="<?php if (isset($step) && ($step == 2)) {
                   echo "btn btn-red btn-circle";
               } else {
                   echo "btn btn-default btn-circle disabled";
               } ?>">2</a>
            <p>Step 2 </p>
        </div>
        <div class="stepwizard-step">
            <a href="#step-3"
               class="<?php if (isset($step) && ($step == 3)) {
                   echo "btn btn-red btn-circle";
               } else {
                   echo "btn btn-default btn-circle disabled";
               } ?>">3</a>
            <p>Step 3</p>
        </div>
        <div class="stepwizard-step">
            <a href="#step-3"
               class="<?php if (isset($step) && ($step == 4)) {
                   echo "btn btn-red btn-circle";
               } else {
                   echo "btn btn-default btn-circle disabled";
               } ?>">4</a>
            <p>Step 4</p>
        </div>
        <div class="stepwizard-step">
            <a href="#step-3"
               class="<?php if (isset($step) && ($step == 5)) {
                   echo "btn btn-red btn-circle";
               } else {
                   echo "btn btn-default btn-circle disabled";
               } ?>">5</a>
            <p>Step 5</p>
        </div>
        <div class="stepwizard-step">
            <a href="#step-3"
               class="<?php if (isset($step) && ($step == 6)) {
                   echo "btn btn-red btn-circle";
               } else {
                   echo "btn btn-default btn-circle disabled";
               } ?>">6</a>
            <p>Step 6</p>
        </div>
    </div>
</div>
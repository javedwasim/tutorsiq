<form action="{{ url('tutor/applied/request') }}" method="post" id="tutorRequestForm">

    <input type="hidden" name="applicationids" id="applicationids">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="teacherid" id="teacherid" value="<?php echo $teacherid; ?>">
    <input type="hidden" name="studentid" id="studentid" value="<?php echo $studentid; ?>">

    <div class="modal-body">

        <div class="form-group">

            <input type="text" class="form-control" id="tuition_date" name="tuition_date"
                   placeholder="Enter Tuition Date" required>
        </div>

        <div class="form-group">

            <input type="text" class="form-control" id="contact_person" name="contact_person"
                   placeholder="Enter Contact Person Name" maxlength="100" required>
        </div>

        <div class="form-group">

            <input type="text" class="form-control" id="contact_no"
                   name="contact_no" maxlength="20" value="" placeholder="Contact No" required>

        </div>

        <div class="form-group">

            <input type="text" class="form-control" id="contact_no2"
                   name="contact_no2" maxlength="20" value="" placeholder="Contact No 2" >

        </div>

        <div class="form-group">

            <input type="number" class="form-control" id="no_of_students" name="no_of_students"
                   placeholder="Enter Number Of Students"
                   onKeyDown="if(this.value.length==3 && event.keyCode!=8) return false;"
                   min="1" max="10" required>
        </div>

        <div class="form-group">

            <select name="csm[]" id="csm" class="form-control select2"
                   multiple="multiple" data-placeholder="Select Grade" required>
                <option></option>

                <?php foreach($classes as $class): ?>
                <option value="<?php echo $class->id; ?>"><?php echo $class->name; ?></option>
                <?php endforeach; ?>
            </select>

        </div>

        <div class="form-group">

            <select class="form-control select2 institute" id="institutes" name="institutes[]"
                    data-placeholder="Select Institute" required>
                <option value=""></option>
                <?php foreach($instututes as $instutute): ?>
                <option value="<?php echo $instutute->id; ?>">
                    <?php echo $instutute->name; ?></option>
                <?php endforeach; ?>

            </select>
        </div>

        <div class="form-group">

            <select name="location_id" id="location_id" class="form-control select2"
                    data-placeholder="Select Location" required >
                <option value="">Select Location</option>
                <?php foreach($locations as $location): ?>
                <option value="<?php echo $location->id; ?>"<?php if (isset($tuition->location_id) && $tuition->location_id == $location->id) echo 'selected'; ?>>
                    <?php echo $location->locations; ?>
                </option>
                <?php endforeach; ?>

            </select>

        </div>

        <div class="form-group">
            <textarea class="form-control" rows="3" id="address" placeholder="Enter Complete Address"
                      name="address" required ></textarea>
        </div>



        <button type="submit" class="btn btn11 pull-right">Apply</button>
        <div class="clearfix"></div>
    </div>

</form>

<link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datepicker/datepicker3.css') }}">
<script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>
<script src="{{ asset('plugins/toastr/toastr.min.js') }}" type="text/javascript"></script>

<script>

    jQuery(document).ready(function ($) {

        $(".select2").select2();

        $('#tuition_date').datepicker({autoclose: false});

    });

    jQuery(document).ready(function ($) {

        $('#tutorRequestForm').on('submit', function (e) {

            e.preventDefault();
            var formData = new FormData($(this)[0]);

            $.ajax({

                url: 'tutor/applied/request',
                type: "post",
                data: formData,
                beforeSend: function () {
                    $("#wait").modal();
                },
                success: function (data) {
                    console.log(data);
                    $('#wait').modal('hide');
                    toastr.success('Tuition Save Successfully!');
                    window.location.href = "{{route('studenthome')}}"
                },
                cache: false,
                contentType: false,
                processData: false

            });

        });





    });

</script>
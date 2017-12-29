<article class="a-padd works11">
    <div class="container">
        @include('layouts.main.partials.wizard')

        <div class="row setup-content" id="step-2">
            <div class="box box-danger">
                <div class="box-header with-border">
                    <h3 class="box-title">Preferred Intitutes and Locations</h3>
                </div>
                <!-- /.box-header -->
                <form method="post" action="{{ url('/tutorsignup/step6') }}" enctype="multipart/form-data" id="stage5form" >

                    <input type="hidden" name="step" value="3">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="tuid" value="<?php echo !empty(session('tuid'))?session('tuid'):''; ?>">
                    <input type="hidden" name="teacherid" value="<?php echo $teacherid; ?>">
                    <input type="hidden" name="subject" class="form-control" value="New location required to be entered in system">

                    <div class="box-body">
                        <div class="form-group">
                            <label for="passing_year1" class="col-sm-2 control-label">Preferred Intitutes<span
                                        style="color: red">*</span></label>

                            <div class="col-sm-10">
                                <select name="institutes[]" id="institutes" class="form-control select2"
                                        multiple="multiple" data-placeholder="Select Institutes" required>
                                    <option></option>
                                    <?php foreach($institutes as $i): ?>
                                    <option value="<?php echo $i->id; ?>"
                                    <?php if (isset($step5Data['institutes']) && in_array($i->id, $step5Data['institutes'])) echo "selected";  ?> >
                                        <?php echo $i->name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="passing_year1" class="col-sm-2 control-label">Zone Locations<span
                                        style="color: red">*</span></label>

                            <div class="col-sm-10">
                                <select name="locations[]" id="locations" class="form-control select2"
                                        multiple="multiple" data-placeholder="Select Locations" required>
                                    <option></option>
                                    <?php foreach($zoneLocations as $locations): ?>
                                    <option value="<?php echo $locations->id."-".$locations->zid; ?>"
                                    <?php if (isset($step5Data['locations']) && in_array($locations->id, $step5Data['locations'])) echo "selected";  ?> >
                                        <?php echo $locations->zonelocations; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="box-header with-border">
                            <h3 class="box-title">If you not found any required information related to location please inform us. </h3>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <div class="form-group">
                                <textarea class="form-control" name="body"  rows="3" placeholder="Write Message ..."></textarea>
                            </div>

                        </div>

                        <button type="submit" class="btn btn11 outline pull-right">Next Step</button>
                        <a href="<?php echo URL::to('tutorsignup/step4');  ?>" class="btn btn11 outline pull-left">Previous Step</a>
                    </div>
                    <!-- /.box-body -->
                </form>

            </div>
        </div>

    </div>
</article>

@section('page_specific_inline_scripts')
    <script>
        $(document).ready(function () {

            $("#stage5form").validate({
                rules: {
                    institutes: {required: true},
                    locations: {required: true},

                },
                messages: {
                    institutes: "Please Select Preferred Subjects",
                    locations: "Please Select Tuition Categories",
                },
                tooltip_options: {
                    institutes: {trigger: 'focus'},
                    locations: {trigger: 'focus'},

                },
            });

            //initialize select2
            $(".select2").select2();

            //send email to admin for other than above
            //send email to admin for other than above
            $( '#adminEmailLocationForm' ).on( 'submit', function(e) {


                e.preventDefault();
                var formData = new FormData($(this)[0]);

                $.ajax({

                    url:'{{url("send/email/location")}}',
                    type: "POST",
                    data: formData,
                    async: false,
                    beforeSend: function(){
                        $("#wait").modal();
                    },
                    success: function (response) {

                        $('#wait').modal('hide');
                        toastr.success('Email Sent To Admin Successfully!');

                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });


            });


        });
    </script>
@endsection
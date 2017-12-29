<article class="a-padd works11">
    <div class="container">
        @include('layouts.main.partials.wizard')

        <div class="row setup-content" id="step-2">
            <div class="box box-danger">
                <div class="box-header with-border">
                    <h3 class="box-title">Subject Preferences</h3>
                </div>
                <!-- /.box-header -->
                <form method="post" action="{{ url('/tutorsignup/step5') }}" enctype="multipart/form-data"
                      id="stage4form">

                    <input type="hidden" name="step" value="3">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="tuid"
                           value="<?php echo !empty(session('tuid')) ? session('tuid') : ''; ?>">
                    <input type="hidden" name="teacherid"
                           value="<?php echo !empty(session('teacherid')) ? session('teacherid') : ''; ?>">
                    <input type="hidden" name="subject" class="form-control"
                           value="New grade or subject required to be entered in system">
                    <input type="hidden" name="gradeSelected" id="gradeSelected" class="form-control" value="">

                    <div class="box-body">

                        <div class="form-group">
                            <label for="passing_year1" class="col-sm-2 control-label">Preferred Subjects<span
                                        style="color: red">*</span></label>

                            <div class="col-sm-10">
                                <select name="csm[]" id="csm" class="form-control select2"
                                        multiple="multiple" data-placeholder="Select Grade" required>
                                    <option></option>
                                    <?php foreach($classes as $class): ?>
                                    <option value="<?php echo $class->id; ?>"
                                    <?php if (isset($step4Data) && in_array($class->id, $step4Data['csm'])) echo "selected";  ?>><?php echo $class->name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="passing_year1" class="col-sm-2 control-label">Categories<span
                                        style="color: red">*</span></label>

                            <div class="col-sm-10">
                                <select name="categories[]" id="categories" class="form-control select2"
                                        multiple="multiple" data-placeholder="Select Categories" required>
                                    <option></option>
                                    <?php foreach($tuition_categories as $category): ?>
                                    <option value="<?php echo $category->id; ?>"
                                    <?php if (isset($step4Data) && in_array($category->id, $step4Data['categories'])) echo "selected";  ?>><?php echo $category->name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="box-header with-border">
                            <h3 class="box-title">If you not found any required information related to grade or subject
                                please inform us. </h3>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <div class="form-group">
                                <textarea class="form-control" name="body" rows="3"  placeholder="Write Message ..."></textarea>
                            </div>

                        </div>
                        <a href="<?php echo URL::to('tutorsignup/step3');  ?>" class="btn btn11 outline">
                            Previous Step</a>
                        <button type="submit" class="btn btn11 outline pull-right">Next Step</button>
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

            $("#stage4form").validate({
                rules: {
                    csm: {required: true},
                    categories: {required: true},

                },
                messages: {
                    csm: "Please Select Preferred Subjects",
                    categories: "Please Select Tuition Categories",
                },
                tooltip_options: {
                    csm: {trigger: 'focus'},
                    categories: {trigger: 'focus'},

                },
            });

            //initialize select2
            $(".select2").select2();

            //send email to admin for other than above
            $('#adminEmailForm').on('submit', function (e) {

                e.preventDefault();
                var formData = new FormData($(this)[0]);

                $.ajax({

                    url: '{{url("send/email")}}',
                    type: "POST",
                    data: formData,
                    async: false,
                    beforeSend: function () {
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
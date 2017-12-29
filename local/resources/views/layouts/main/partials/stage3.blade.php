<article class="a-padd works11">
    <div class="container">
        @include('layouts.main.partials.wizard')

        <div class="row setup-content" id="step-2">
            <div class="box box-danger">
                <div class="box-header with-border">

                    <div class="row">
                        <div class="col-md-6">
                            <h3 class="box-title">1st Highest Degree Detail</h3>
                        </div>
                        <div class="col-md-6">
                            <h3 class="box-title">2nd Highest Degree Detail</h3>
                        </div>
                    </div>

                </div>

                <!-- /.box-header -->
                <form method="post" action="{{ url('/tutorsignup/step4') }}" enctype="multipart/form-data" id="stage3form" >
                    <input type="hidden" name="step" value="3">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="tuid" value="<?php echo !empty(session('tuid'))?session('tuid'):''; ?>">
                    <input type="hidden" name="teacherid" value="<?php echo !empty(session('teacherid'))?session('teacherid'):''; ?>">
                    <div class="box-body">

                        <!-- Qualification1 and Qualification2 -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password" class="col-sm-4 control-label">Qualification<span
                                                style="color: red">*</span></label>

                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="qualification_name"
                                               name="qualification_name"
                                               value="<?php echo !empty($step3Data)?$step3Data['qualification_name']:''; ?>"
                                               placeholder="Enter 1st Qualification" maxlength="100" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="confirm_password" class="col-sm-4 control-label">Qualification<span
                                       style="color: red">*</span></label>

                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="qualification_name2"
                                               name="qualification_name2"
                                               value="<?php echo !empty($step32Data)?$step32Data['qualification_name']:''; ?>"
                                               placeholder="Enter 2nd Qualification" maxlength="100" required>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- Qualification1 and Qualification2 -->

                        <!-- Qualification Level1 and Qualification Level2 -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password" class="col-sm-4 control-label">Qualification Level<span
                                                style="color: red">*</span></label>

                                    <div class="col-sm-8">
                                        <select class="form-control select2" id="highest_degree"
                                                name="highest_degree"
                                                required data-placeholder="Select Highest Degree">
                                            <option value=""></option>
                                            <option value="1st" selected >
                                                1st Highest
                                            </option>
                                            <option value="2nd"  disabled >
                                                2nd Highest
                                            </option>
                                            <option value="other" disabled>
                                                Other
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="confirm_password" class="col-sm-4 control-label">Qualification Level<span
                                                style="color: red">*</span></label>

                                    <div class="col-sm-8">
                                        <select class="form-control select2" id="highest_degree2"
                                                name="highest_degree2" required data-placeholder="Select Highest Degree">
                                            <option value=""></option>
                                            <option value="1st" disabled >
                                                1st Highest
                                            </option>
                                            <option value="2nd"  selected >
                                                2nd Highest
                                            </option>
                                            <option value="other" disabled>
                                                Other
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- Qualification Level1 and Qualification Level2 -->

                        <!-- Qualification Status1 and Qualification Status2 -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password" class="col-sm-4 control-label">Qualification Status<span
                                                style="color: red">*</span></label>

                                    <div class="col-sm-8">
                                        <label>
                                            <input type="radio" name="continue" class="minimal" required
                                                   value="completed"<?php if(isset($step3Data)&&$step32Data['continue']=='completed') echo 'checked' ?>>
                                            Completed
                                        </label>
                                        <label>
                                            <input type="radio" name="continue" class="minimal" required
                                                   value="continue"<?php if(isset($step3Data)&&$step32Data['continue']=='continue') echo 'checked' ?>>
                                            Continue
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="confirm_password" class="col-sm-4 control-label">Qualification Level2<span
                                                style="color: red">*</span></label>

                                    <div class="col-sm-8">
                                        <label>
                                        <input type="radio" name="continue2" class="minimal" required
                                               value="completed" <?php if(isset($step32Data)&&$step32Data['continue']=='completed') echo 'checked' ?> >
                                            Completed
                                        </label>
                                        <label>
                                            <input type="radio" name="continue2" class="minimal" required
                                                   value="continue2" <?php if(isset($step32Data)&&$step32Data['continue']=='continue2') echo 'checked' ?>>
                                            Continue
                                        </label>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- Qualification Status1 and Qualification Status2 -->

                        <!-- Degree Progress1 and Progress2 -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password" class="col-sm-4 control-label" style="max-width: 100%">
                                        Degree Progress (if Continue)<span style="color: red">*</span></label>

                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="higher_degree"
                                               name="higher_degree"  value="<?php echo !empty($step3Data)?$step3Data['higher_degree']:''; ?>"
                                               placeholder="Higher Degree" maxlength="250">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="confirm_password" class="col-sm-4 control-label" style="max-width: 100%">
                                        Degree Progress(if Continue)<span style="color: red">*</span></label>

                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="higher_degree2"
                                               name="higher_degree2"  value="<?php echo !empty($step32Data)?$step32Data['higher_degree']:''; ?>"
                                               placeholder="Higher Degree" maxlength="250">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- Degree Progress1 and Progress2 -->

                        <!-- Degree Progress1 and Progress2 -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password" class="col-sm-4 control-label">Elective Subjects<span
                                                style="color: red">*</span></label>

                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="elective_subjects"
                                               name="elective_subjects"  value="<?php echo !empty($step3Data)?$step3Data['elective_subjects']:''; ?>"
                                               placeholder="Elective/Main Subjects" maxlength="250" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="confirm_password" class="col-sm-4 control-label">Elective Subjects<span
                                                style="color: red">*</span></label>

                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="elective_subjects2"
                                               name="elective_subjects2"  value="<?php echo !empty($step32Data)?$step32Data['elective_subjects']:''; ?>"
                                               placeholder="Elective/Main Subjects" maxlength="250" required>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- Degree Progress1 and Progress2 -->

                        <!-- College/University/Board -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password" class="col-sm-4 control-label">College/University/Board<span
                                                style="color: red">*</span></label>

                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="institution" name="institution"
                                               value="<?php echo !empty($step3Data)?$step3Data['institution']:''; ?>"
                                               placeholder="College/University/Board" maxlength="100" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="confirm_password" class="col-sm-4 control-label">College/University/Board<span
                                                style="color: red">*</span></label>

                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="institution2" name="institution2"
                                               value="<?php echo !empty($step32Data)?$step32Data['institution']:''; ?>"
                                               placeholder="College/University/Board" maxlength="100" required>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- College/University/Board -->

                        <!-- Year Passes -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password" class="col-sm-4 control-label">Year Passed<span
                                                style="color: red">*</span></label>

                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="passing_year"
                                               name="passing_year" value="<?php echo !empty($step3Data)?$step3Data['passing_year']:''; ?>"
                                               placeholder="Year Passed" maxlength="30" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="confirm_password" class="col-sm-4 control-label">Year Passed<span
                                                style="color: red">*</span></label>

                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="passing_year2"
                                               name="passing_year2"   value="<?php echo !empty($step32Data)?$step32Data['passing_year']:''; ?>"
                                               placeholder="Year Passed" maxlength="30" required>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- Year Passes -->

                        <!-- Grade/DIV -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password" class="col-sm-4 control-label">Grade/DIV<span
                                                style="color: red">*</span></label>

                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="grade" name="grade"
                                               value="<?php echo !empty($step3Data)?$step3Data['grade']:''; ?>"
                                               placeholder="Grade/DIV" maxlength="20" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="confirm_password" class="col-sm-4 control-label">Grade/DIV<span
                                                style="color: red">*</span></label>

                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="grade2" name="grade2"
                                               value="<?php echo !empty($step32Data)?$step32Data['grade']:''; ?>"
                                               placeholder="Grade/DIV" maxlength="20" required>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- Grade/DIV-->

                        <!-- Degree Document -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password" class="col-sm-4 control-label">Degree Document</label>

                                    <div class="col-sm-8">
                                        <input type="file" name="degree_document" id="degree_document">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="confirm_password" class="col-sm-4 control-label">Degree Document</label>

                                    <div class="col-sm-8">
                                        <input type="file" name="degree_document2" id="degree_document2">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- Grade/DIV-->



                        <button type="submit" class="btn btn11 outline pull-right">Next Step</button>
                        <a href="<?php echo URL::to('tutorsignup/step2');  ?>" class="btn btn11 outline pull-left">Previous Step</a>
                    </div>
                    <!-- /.box-body -->
                </form>
            </div>
        </div>

    </div>
</article>

@section('page_specific_inline_scripts')
    <script>
        $(document).ready(function() {
            $("#stage3form").validate({
                rules: {

                    qualification_name : {required: true},
                    qualification_name2 : {required: true},
                    continue : {required: true},
                    continue2 : {required: true},
                    elective_subjects : {required: true},
                    elective_subjects2 : {required: true},
                    institution : {required: true},
                    institution2 : {required: true},
                    passing_year : {required: true},
                    passing_year2 : {required: true},
                    grade : {required: true},
                    grade2 : {required: true},



                },
                messages: {
                    qualification_name: "Please Enter 1st Highest Degree",
                    qualification_name2: "Please Enter 2nd Highest Degree",
                    continue: "Please Select Qualification Status",
                    continue2: "Please Select Qualification Status",
                    elective_subjects: "Please Enter Elective Subjects",
                    elective_subjects2: "Please Enter Elective Subjects",
                    institution: "Please Enter College/University/Board",
                    institution2: "Please Enter College/University/Board",
                    passing_year: "Please Enter Year Passed",
                    passing_year2: "Please Enter Year Passed",
                    grade: "Please Enter Grade/DIV",
                    grade2: "Please Enter Grade/DIV",



                },
                tooltip_options: {
                    qualification_name: {trigger:'focus'},
                    qualification_name2: {trigger:'focus'},
                    continue: {trigger:'focus'},
                    continue2: {trigger:'focus'},
                    elective_subjects: {trigger:'focus'},
                    elective_subjects2: {trigger:'focus'},
                    institution: {trigger:'focus'},
                    institution2: {trigger:'focus'},
                    passing_year: {trigger:'focus'},
                    passing_year2: {trigger:'focus'},
                    grade: {trigger:'focus'},
                    grade2: {trigger:'focus'},



                },
            });



        });
    </script>
@endsection
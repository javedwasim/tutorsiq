<div class="box box-primary collapsed-box">
    <a href="#">
        <div class="box-header with-border" data-widget="collapse">
            <i class="fa fa-plus pull-right teacher-detial" style="font-size:12px;
        margin-top: 5px;"></i>
            <h1 class="box-title">Teacher Details</h1>
        </div>
    </a>

    <div class="box-body" id="teacher-detial">
        <div class="row">

            <div class="col-md-12">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#qualification" data-toggle="tab">Qualifications</a></li>
                        <li><a href="#preference" data-toggle="tab">Preferred Subjects</a></li>
                        <li><a href="#institutes" data-toggle="tab">Preferred Institutes</a></li>
                        <li><a href="#grade" data-toggle="tab">Grades/Subjects</a></li>
                        <li><a href="#location" data-toggle="tab">Locations</a></li>
                        <li><a href="#label" data-toggle="tab">Labels</a></li>
                        <li><a href="#history" data-toggle="tab">Tuition History</a></li>
                        <li><a href="#tuition_applied_listy" data-toggle="tab">Tuition Applications</a></li>
                    </ul>
                    <div class="tab-content">

                        <div class="active tab-pane" id="qualification">
                            @include('layouts.partials.tutorqualificationlist')
                        </div>

                        <div class="tab-pane" id="preference">
                            @include('layouts.partials.tutorpreferencelist')
                        </div>

                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="institutes">
                            @include('layouts.partials.tutorinstituteslist')
                        </div>
                        <!-- /.tab-pane -->

                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="grade">
                            @include('layouts.partials.gradesubjectslist')
                        </div>
                        <!-- /.tab-pane -->

                        <!-- /.tab-pane -->

                        <div class="tab-pane" id="location">
                            @include('layouts.partials.locationpreferencelist')
                        </div>
                        <!-- /.tab-pane -->

                        <div class="tab-pane" id="label">
                            @include('layouts.partials.teacherlabelslist')
                        </div>

                        <div class="tab-pane" id="history">
                            @include('layouts.partials.tutortuitionhistorylist')
                        </div>

                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="tuition_applied_listy">
                            @include('layouts.partials.teachertuitions')
                        </div>

                    </div>
                    <!-- /.tab-content -->
                </div>
                <!-- /.nav-tabs-custom -->
            </div>
            <!-- /.col -->
        </div>
    </div>
</div>
<!-- /.box -->
<div class="box box-primary collapsed-box">
    <a href="#">
        <div class="box-header with-border" data-widget="">
            <!--<i class="fa fa-minus pull-right teacher-detial" style="font-size:12px; margin-top: 5px;"></i>-->
            <h1 class="box-title">Tuition Details</h1>
        </div>
    </a>

    <div class="box-body" id="tuition-detial">
        <div class="row">

            <div class="col-md-12">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tuition_detail_list" data-toggle="tab">Grade+Subjects</a></li>
                        <li><a href="#bookmark" data-toggle="tab">Bookmark</a></li>
                        <li><a href="#label" data-toggle="tab">Labels</a></li>
                        <li><a href="#teacher_applications_list" data-toggle="tab">Teacher Applications</a></li>


                    </ul>
                    <div class="tab-content">
                        <div class="active tab-pane" id="tuition_detail_list">
                            @include('layouts.partials.tuitiondetaillist')
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="bookmark">
                            @include('layouts.partials.teacherbookmark')
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="label">
                            @include('layouts.partials.tuitionlabels')
                        </div>

                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="teacher_applications_list">
                            @include('layouts.partials.teacherapplications')
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
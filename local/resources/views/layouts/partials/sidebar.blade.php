<!-- Left side column. contains the logo and sidebar -->
<?php if (isset($current_route)) {
    $current_route = $current_route;
} else {
    $current_route = '';
}  ?>
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        @if (! Auth::guest())
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="{{asset('/img/user2-160x160.jpg')}}" class="img-circle" alt="User Image"/>
                </div>
                <div class="pull-left info">
                    <p>{{ Auth::user()->name }}</p>
                    <!-- Status -->
                    <a href="#"><i class="fa fa-circle text-success"></i> {{ trans('adminlte_lang::message.online') }}
                    </a>
                </div>
            </div>
    @endif

    <!-- search form (Optional) -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control"
                       placeholder="{{ trans('adminlte_lang::message.search') }}..."/>
                <span class="input-group-btn">
                        <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i
                                    class="fa fa-search"></i>
                        </button>
                      </span>
            </div>
        </form>
        <!-- /.search form -->

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <li class="header">{{ trans('adminlte_lang::message.header') }}</li>
            <!-- Optionally, you can add icons to the links -->
            <li><a href="{{ url('home') }}"><i class='fa fa-home'></i>
                    <span>{{ trans('adminlte_lang::message.home') }}</span></a></li>

            @role('admin')


            <li class="treeview <?php if ($current_route == 'admin/subjects'
                || $current_route == 'admin/classes' || $current_route == 'admin/class/subject/mappings'
                || $current_route == 'admin/locations' || $current_route == 'admin/bands' || $current_route == 'admin/tuition/status'
                || $current_route == 'admin/tuition/status' || $current_route == 'admin/assignstatus' || $current_route == 'admin/tuition/categories'
                || $current_route == 'admin/notes' || $current_route == 'admin/labels' || $current_route == 'admin/degree/levels'
                || $current_route == 'admin/institutes' || $current_route == 'admin/referrers' || $current_route == 'admin/zones'
                || $current_route == 'admin/application/status'
            ) echo 'active'; ?>">

                <a href="#"><i class='fa fa-fw fa-group text-red'></i>
                    <span>{{ trans('adminlte_lang::message.defination-section') }}</span>
                    <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">

                    <li class="<?php if (isset($current_route) && $current_route == 'admin/subjects') echo 'active'; ?>">
                        <a href="{{ url('admin/subjects') }}"><i class='fa fa-book'></i>
                            <span>{{ trans('adminlte_lang::message.teacher-subjects') }}</span></a></li>
                    <li class="<?php if (isset($current_route) && $current_route == 'admin/classes') echo 'active'; ?>">
                        <a href="{{ url('admin/classes') }}"><i class='fa  fa-building-o'></i>
                            <span>{{ trans('adminlte_lang::message.teacher-classes') }}</span></a></li>
                    <li class="<?php if (isset($current_route) && $current_route == 'admin/class/subject/mappings') echo 'active'; ?>">
                        <a href="{{ url('admin/class/subject/mappings') }}"><i class='fa  fa-sitemap'></i>
                            <span>{{ trans('adminlte_lang::message.class-subject') }}</span></a></li>

                    <li class="<?php if (isset($current_route) && $current_route == 'admin/zones') echo 'active'; ?>">
                        <a href="{{ url('admin/zones') }}"><i class='fa  fa-map-marker'></i>
                            <span>{{ trans('adminlte_lang::message.zone-location') }}</span></a></li>

                    <li class="<?php if (isset($current_route) && $current_route == 'admin/locations') echo 'active'; ?>">
                        <a href="{{ url('admin/locations') }}"><i class='fa  fa-map-marker'></i>
                            <span>{{ trans('adminlte_lang::message.teacher-location') }}</span></a></li>

                    <li class="<?php if (isset($current_route) && $current_route == 'admin/bands') echo 'active'; ?>">
                        <a href="{{ url('admin/bands') }}"><i class='fa  fa-circle-o'></i>
                            <span>{{ trans('adminlte_lang::message.teacher-band') }}</span></a></li>

                    <li class="<?php if (isset($current_route) && $current_route == 'admin/tuition/status') echo 'active'; ?>">
                        <a href="{{ url('admin/tuition/status') }}"><i class='fa  fa-circle-o'></i>
                            <span>{{ trans('adminlte_lang::message.tuition-status') }}</span></a></li>

                    <li class="<?php if (isset($current_route) && $current_route == 'admin/tuition/categories') echo 'active'; ?>">
                        <a href="{{ url('admin/tuition/categories') }}"><i class='fa  fa-circle-o'></i>
                            <span>{{ trans('adminlte_lang::message.tuition-category') }}</span></a></li>

                    <li class="<?php if (isset($current_route) && $current_route == 'admin/notes') echo 'active'; ?>">
                        <a href="{{ url('admin/notes') }}"><i class='fa  fa-circle-o'></i>
                            <span>{{ trans('adminlte_lang::message.special-notes') }}</span></a></li>

                    <li class="<?php if (isset($current_route) && $current_route == 'admin/labels') echo 'active'; ?>">
                        <a href="{{ url('admin/labels') }}"><i class='fa  fa-circle-o'></i>
                            <span>{{ trans('adminlte_lang::message.teacher-label') }}</span></a></li>

                    <li class="<?php if (isset($current_route) && $current_route == 'admin/institutes') echo 'active'; ?>">
                        <a href="{{ url('admin/institutes') }}"><i class='fa  fa-circle-o'></i>
                            <span>{{ trans('adminlte_lang::message.institutes') }}</span></a></li>

                    <li class="<?php if (isset($current_route) && $current_route == 'admin/referrers') echo 'active'; ?>">
                        <a href="{{ url('admin/referrers') }}"><i class='fa  fa-circle-o'></i>
                            <span>{{ trans('adminlte_lang::message.referrer') }}</span></a></li>

                    <li class="<?php if (isset($current_route) && $current_route == 'admin/application/status') echo 'active'; ?>">
                        <a href="{{ url('admin/application/status') }}"><i class='fa  fa-circle-o'></i>
                            <span>{{ trans('adminlte_lang::message.application-status') }}</span></a></li>

                </ul>
            </li>


            <li class="<?php if (isset($current_route) && $current_route == 'admin/teachers') echo 'active'; ?>">
                <a href="{{ url('admin/teachers') }}"><i class='fa fa-users text-aqua'></i>
                    <span>{{ trans('adminlte_lang::message.admin-teacher') }}</span></a></li>

            <li class="<?php if (isset($current_route) && $current_route == 'admin/tuitions') echo 'active'; ?>">
                <a href="{{ url('admin/tuitions') }}"><i class='fa  fa-circle-o text-yellow'></i>
                    <span>{{ trans('adminlte_lang::message.tuition-message') }}</span></a></li>

            <li class="<?php if (isset($current_route) && $current_route == 'admin/tuitions') echo 'active'; ?>">
                <a href="javascript:void(0)" id="s_tuitions"><i class='fa  fa-circle-o text-green'></i>
                    <span>{{ trans('adminlte_lang::message.student-tutiions') }}</span></a></li>

            <li class="<?php if (isset($current_route) && $current_route == 'admin/templates') echo 'active'; ?>">
                <a href="{{ url('admin/templates') }}"><i class='fa  fa-envelope-o'></i>
                    <span>{{ trans('adminlte_lang::message.email-template') }}</span></a></li>

            <li class="<?php if (isset($current_route) && $current_route == 'admin/global/teachers') echo 'active'; ?>">
                <a href="{{ url('admin/global/teachers') }}"><i class='fa fa-globe text-blue'></i>
                    <span>{{ trans('adminlte_lang::message.global-list') }}</span></a></li>

            <li class="<?php if (isset($current_route) && $current_route == 'admin/global/tuitions') echo 'active'; ?>">
                <a href="{{ url('admin/global/tuitions') }}"><i class='fa fa-globe text-danger'></i>
                    <span>{{ trans('adminlte_lang::message.tuition-global-list') }}</span></a></li>

            <li class="<?php if (isset($current_route) && $current_route == 'admin/global/email') echo 'active'; ?>">
                <a href="{{ url('admin/global/email') }}"><i class='fa  fa-envelope-o text-lime'></i>
                    <span>{{ trans('adminlte_lang::message.bulk-email') }}</span></a></li>

            <li class="<?php if (isset($current_route) && $current_route == 'admin/customer/phone') echo 'active'; ?>">
                <a href="{{ url('admin/customer/phone') }}"><i class='fa fa-phone-square text-purple'></i>
                    <span>{{ trans('adminlte_lang::message.customer-phone') }}</span></a></li>

            <li class="<?php if (isset($current_route) && $current_route == 'admin/tuitions/followup') echo 'active'; ?>">
                <a href="{{ url('admin/tuitions/followup') }}"><i class='fa fa-phone-square text-yellow'></i>
                    <span>{{ trans('adminlte_lang::message.tuition-followup') }}</span></a></li>

            <li class="<?php if (isset($current_route) && $current_route == 'admin/global/notes') echo 'active'; ?>">
                <a href="{{ url('admin/global/notes') }}"><i class='fa fa-fw fa-file-image-o'></i>
                    <span>{{ trans('adminlte_lang::message.global-notepad') }}</span></a></li>

            @endrole
        <!-- Admin Role Ends -->
            <!-- Teacher Role Starts -->
            @role('teacher')

            <li class="<?php if (isset($current_route) && $current_route == 'teacher') echo 'active'; ?>">
                <a href="{{url('teacher')}}"><i class='fa fa-circle-o text-red'></i>
                    <span>{{ trans('adminlte_lang::message.profile') }}</span></a></li>

            <li class="<?php if (isset($current_route) && $current_route == 'tuition/categories') echo 'active'; ?>">
                <a href="{{url('tuition/categories')}}"><i class='fa fa-circle-o text-yellow'></i>
                    <span>{{ trans('adminlte_lang::message.tuition-category') }}</span></a></li>

            <li class="<?php if (isset($current_route) && $current_route == 'prefered/institutes') echo 'active'; ?>">
                <a href="{{url('prefered/institutes')}}"><i class='fa fa-circle-o text-aqua'></i>
                    <span>{{ trans('adminlte_lang::message.prefered-institute') }}</span></a></li>

            <li class="<?php if (isset($current_route) && $current_route == 'qualifications') echo 'active'; ?>">
                <a href="{{url('qualifications')}}"><i class='fa fa-circle-o'></i>
                    <span>{{ trans('adminlte_lang::message.teacher-qualification') }}</span></a></li>

            <li class="<?php if (isset($current_route) && $current_route == 'preferences') echo 'active'; ?>">
                <a href="{{url('preferences')}}"><i class='fa fa-circle-o text-red'></i>
                    <span>{{ trans('adminlte_lang::message.teacher-preferences') }}</span></a></li>


            <li class="<?php if (isset($current_route) && $current_route == 'tuitions') echo 'active'; ?>">
                <a href="{{url('tuitions')}}"><i class='fa fa-circle-o text-aqua'></i>
                    <span>{{ trans('adminlte_lang::message.teacher-tuitions') }}</span></a></li>

            <li class="<?php if (isset($current_route) && $current_route == 'locations') echo 'active'; ?>">
                <a href="{{url('locations')}}"><i class='fa fa-circle-o text-yellow'></i>
                    <span>{{ trans('adminlte_lang::message.prefer-location') }}</span></a></li>

            <li class="<?php if (isset($current_route) && $current_route == 'tuition/details') echo 'active'; ?>">
                <a href="{{url('tuition/details')}}"><i class='fa fa-circle-o'></i>
                    <span>{{ trans('adminlte_lang::message.tuition-search') }}</span></a></li>


            <li class="<?php if (isset($current_route) && $current_route == 'tuition/search') echo 'active'; ?>">

                <a href="{{url('tuition/search')}}"><i class='fa fa-circle-o'></i>
                    <span>{{ trans('adminlte_lang::message.advance-search') }}</span></a>

            </li>

            <li class="<?php if (isset($current_route) && $current_route == 'tuition/automatched') echo 'active'; ?>">

                <a href="{{url('tuition/automatched')}}" class="automatched-btn"><i class='fa fa-circle-o'></i>
                    <span>{{ trans('adminlte_lang::message.auto-tuitions') }}</span></a>
            </li>

            <li class="<?php if (isset($current_route) && $current_route == 'applications') echo 'active'; ?>">

                <a href="{{url('applications')}}" class="automatched-btn"><i class='fa fa-circle-o'></i>
                    <span>{{ trans('adminlte_lang::message.tuition-applications') }}</span></a>
            </li>

            @endrole
        </ul><!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->

</aside>

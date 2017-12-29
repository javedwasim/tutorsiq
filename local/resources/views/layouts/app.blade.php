<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

@section('htmlheader')
@include('layouts.partials.htmlheader')
@show

        <!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| SKINS         | skin-blue                               |
|               | skin-black                              |
|               | skin-purple                             |
|               | skin-yellow                             |
|               | skin-red                                |
|               | skin-green                              |
|---------------------------------------------------------|
|LAYOUT OPTIONS | fixed                                   |
|               | layout-boxed                            |
|               | layout-top-nav                          |
|               | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->

<body class="skin-blue sidebar-mini">
<div class="wrapper">

    @include('layouts.partials.mainheader')

    @include('layouts.partials.sidebar')

            <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        @include('layouts.partials.contentheader')
                <!-- Main content -->
        <section class="content">

            <!-- Your Page Content Here -->
            @yield('main-content')
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

    @include('layouts.partials.controlsidebar')

    @include('layouts.partials.footer')

</div><!-- ./wrapper -->

    @section('scripts')
    @include('layouts.partials.scripts')
    @show


    <!-- this page specific styles -->
    @yield('page_specific_styles')
    <!-- this page specific scripts -->
    @yield('page_specific_scripts')
    <!-- this page specific inline scripts -->
    @yield('page_specific_inline_scripts')
</body>
<form class="pull-right form-group" method="post" action="{{ url('admin/tuitions') }}" id="studentTuition" style="display: none;">

    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="start_date" id="start_date" value="<?php echo date('d/m/Y', strtotime(Carbon\Carbon::now()->subDays(90))); ?>">
    <input type="hidden" name="end_date" id="end_date" value="<?php echo  date('d/m/Y', strtotime(Carbon\Carbon::now()));; ?>">
    <input type="hidden" name="tuition_date" id="tuition_date" value="90">
    <input type="hidden" name="student_tutiions" id="student_tutiions" value="student_tutiions">
    <button type="submit" class="btn btn-primary pull-right studentTuition" >
        <i class="fa fa-fw fa-volume-up"></i>Student Tuitions</button>&nbsp;&nbsp;

</form>
<script src="{{ asset('plugins/toastr/toastr.min.js') }}" type="text/javascript"></script>
<script type="text/javascript">
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "positionClass": "toast-top-center",
        "preventDuplicates": true,
        "toastClass": "animated fadeInDown",
        "onclick": null,
        "showDuration": "10000",
        "hideDuration": "5000",
        "timeOut": "2000",
        "extendedTimeOut": "0",
        "showEasing": "linear",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
</script>
<script>
    //select students tutiion.
    $('#s_tuitions').on('click', function (e) {
        $('.studentTuition').trigger('click');
    });


    $('.todayTuitions').on( 'click', function () {

        $('.studentTuition').trigger('click');


    } );
</script>
</html>

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
   <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="margin: 10px !important;">


                <!-- Main content -->
        <section class="content">
            <!-- Your Page Content Here -->
            @yield('main-content')
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

    @include('layouts.partials.controlsidebar')

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

<script>
jQuery(document).ready(function ($) {
    $('.automatched-btn').on('click', function () {

        $('#automatched').trigger('click');
    });
});
</script>
<script>

    $('.teacher_photo').on('click', function() {

        $("#teacher_profile_image").attr("src",this.id);
        $("#teacher_profile_photo").modal();

    });

    $('.global-list').on('click', function () {

        var teacherid = this.id;

        $.ajax({

            url: '{{url("admin/global")}}',
            type: "post",
            data: {'teacherid': teacherid, '_token': $('input[name=_token]').val()},

            success: function (response) {

                var test = JSON.stringify(response);
                var data = JSON.parse(test);
                var success = data['success'];
                console.log(data['teacher']);
                $('#myModal').modal();

            }

        });

    });

</script>

</html>

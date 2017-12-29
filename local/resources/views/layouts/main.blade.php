<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

@section('htmlheader')
    @include('layouts.main.htmlheader')
@show

@section('mainheader')
    @include('layouts.main.mainheader')
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
<section>
    <!-- Your Page Content Here -->
    @yield('main-content')
</section>

@include('layouts.main.footer')

@section('scripts')
    @include('students.partials.scripts')
@show

<!-- this page specific styles -->
@yield('page_specific_styles')
<!-- this page specific scripts -->
@yield('page_specific_scripts')
<!-- this page specific inline scripts -->
@yield('page_specific_inline_scripts')
<!-- this page specific bootstrap modals-->
@yield('pagemodal')

</body>

</html>

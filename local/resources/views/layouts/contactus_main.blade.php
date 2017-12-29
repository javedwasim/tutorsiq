<!DOCTYPE html>
<html lang="en">

@section('htmlheader')
    @include('layouts.main.htmlheader')
@show

<header>
    <?php $user = Auth::user(); $tutorsignup = Request::segment(1); ?>

    <?php if($tutorsignup != 'classified') : ?>

    <nav class="navbar navbar-toggleable-md navbar-inverse def_nav11">
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse"
                data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="<?php echo URL::to('/');  ?>"><img src="{{ asset('img/logo.png') }}" alt=""></a>

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="<?php echo URL::to('/');  ?>">Home <span class="sr-only">(current)</span></a>
                </li>
                <?php if(!isset($user)): ?>

                <li class="nav-item">
                    <a class="nav-link" href="<?php echo URL::to('/contactus');  ?>">Contact Us</a>
                </li>
                <?php endif; ?>

                <?php if(isset($user)): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo URL::to('search-teacher');  ?>">Find Tutors</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo URL::to('applied/tuitions');  ?>">Applied Tutors</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo URL::to('call/academy');  ?>">Call Academy</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo URL::to('logout');  ?>">Sign Out</a>
                </li>
                <?php endif; ?>

                <?php if(!isset($user)): ?>
                <li class="nav-item">
                    <a href="{{url('login')}}" class="nav-link">login</a>
                </li>

                <?php endif; ?>

            </ul>

        </div>
    </nav>

    <?php endif; ?>

</header>

<body class="skin-blue sidebar-mini">
<section>
    @yield('main-content')
</section>

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

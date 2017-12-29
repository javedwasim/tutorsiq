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

    <?php if( !isset($user) && $tutorsignup != 'tutorsignup' && $tutorsignup != 'thankyou' && $tutorsignup != 'classified') : ?>

        <div class="banner-content d-flex">

        <div class="container">
            <div class="row">

                <div class="col-lg-7 col-md-9">
                    <h1 class="mb-4">We Help Students & Tutors
                        To Find Each Other</h1>
                    <a href="javascript:void(0);" class="btn btn11 mr-2 register-student">Find Tutor</a>
                    <a href="<?php echo URL::to('tutorsignup');  ?>" class="btn btn11 outline">Find Student</a>
                </div>

            </div>
        </div>

    </div>

    <?php endif; ?>

</header>
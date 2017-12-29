@extends('layouts.matched_teachers')

    @section('contentheader_description')

    @endsection


    @section('main-content')
            <!-- Tutor Search Filters -->
    @include('layouts.partials.matched_global_teacher_filters')

            <!-- Parent Grid View -->
        <?php if(isset($screen) && $screen == 'admin/global/teachers/matched'): ?>
            @include('layouts.partials.matched_global_teachers')
        <?php endif; ?>

@endsection
@extends('layouts.app')

@section('htmlheader_title')
    @role('teacher')
    Matched Tuitions
    @endrole
    @role('admin')
    Admin | Bands
    @endrole
@endsection

@section('contentheader_title')
    <?php
        if (!empty($subject_name)) {
            echo 'Tuition Search By Subject : ' . $subject_name;
        }elseif(!empty($class_name)){
            echo 'Tuition Search By Class : ' . $class_name;
        }elseif(!empty($location_name)){
            echo 'Tuition Search By Location : ' . $location_name;
        }elseif(!empty($category)){
            echo 'Tuition Search By Category : ' . $category;
        }else{
            echo 'Tuition For Me';
        }
    ?>
@endsection

@section('contentheader_description')

@endsection


@section('main-content')

    @include('teachers.matched')

@endsection
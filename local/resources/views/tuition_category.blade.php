@extends('layouts.app')

@section('htmlheader_title')
    @role('teacher')
    Teacher Portal
    @endrole
    @role('admin')
    Admin | Subjects | Grades
    @endrole
    @endsection

    @section('contentheader_title')
    {{ trans('Subjects/Grades Categories') }}
    @endsection

    @section('contentheader_description')

    @endsection


    @section('main-content')

    @include('layouts.partials.tuition_categories')

    @endsection









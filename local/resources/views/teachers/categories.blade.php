@extends('layouts.app')

@section('htmlheader_title')
    @role('teacher')
    Tuition Categories
    @endrole
    @role('admin')
    Admin | Categories
    @endrole
    @endsection

    @section('contentheader_title')
    {{ trans('Tuition Categories') }}
    @endsection

    @section('contentheader_description')

    @endsection


    @section('main-content')

    @include('teachers.tuitioncategories')

    @endsection









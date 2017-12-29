@extends('layouts.app')

@section('htmlheader_title')
    @role('teacher')
    Prefered Institutes
    @endrole
    @role('admin')
    Admin | Institutes
    @endrole
    @endsection

    @section('contentheader_title')
    {{ trans('Preferred Institutes') }}
    @endsection

    @section('contentheader_description')

    @endsection


    @section('main-content')

    @include('teachers.preferedinstitute')

    @endsection









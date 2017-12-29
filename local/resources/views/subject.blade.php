@extends('layouts.app')

@section('htmlheader_title')
    @role('teacher')
    Teacher Portal
    @endrole
    @role('admin')
    Admin | Subjects
    @endrole
    @endsection

    @section('contentheader_title')
    {{ trans('Subjects') }}
    @endsection

    @section('contentheader_description')

    @endsection


    @section('main-content')

    @include('layouts.partials.subjects')

    @endsection









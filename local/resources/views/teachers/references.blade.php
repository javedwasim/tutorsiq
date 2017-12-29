@extends('layouts.app')

@section('htmlheader_title')
    @role('teacher')
    Teacher References
    @endrole
    @role('admin')
    Admin | Bands
    @endrole
    @endsection

    @section('contentheader_title')
    {{ trans('References') }}
    @endsection

    @section('contentheader_description')

    @endsection


    @section('main-content')

    @include('teachers.tutorreferenceslist')

    @endsection









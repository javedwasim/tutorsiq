@extends('layouts.app')

@section('htmlheader_title')
    @role('teacher')
    Tuitions
    @endrole
    @role('admin')
    Admin | Bands
    @endrole
    @endsection

    @section('contentheader_title')
    {{ trans('Tuitions') }}
    @endsection

    @section('contentheader_description')

    @endsection

    @section('main-content')

    @include('teachers.details')

    @endsection
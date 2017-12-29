@extends('layouts.app')

@section('htmlheader_title')
    @role('teacher')
    Teacher Portal
    @endrole
    @role('admin')
    Admin | Special  Notes
    @endrole
    @endsection

    @section('contentheader_title')
    {{ trans('Special Notes') }}
    @endsection

    @section('contentheader_description')

    @endsection


    @section('main-content')

    @include('layouts.partials.notes')

    @endsection









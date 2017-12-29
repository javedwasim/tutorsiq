@extends('layouts.app')

@section('htmlheader_title')
    @role('teacher')
    Teacher Portal
    @endrole
    @role('admin')
    Admin | Referrers
    @endrole
    @endsection

    @section('contentheader_title')
    {{ trans('Bands') }}
    @endsection

    @section('contentheader_description')

    @endsection


    @section('main-content')

    @include('layouts.partials.referrers')

    @endsection









@extends('layouts.app')

@section('htmlheader_title')
    {{ trans('adminlte_lang::message.unauthorised') }}
@endsection

@section('contentheader_title')
    {{ trans('adminlte_lang::message.403error') }}
@endsection

@section('$contentheader_description')
@endsection

@section('main-content')

<div class="error-page">
    <h2 class="headline text-yellow"> 403</h2>
    <div class="error-content">
        <h3><i class="fa fa-warning text-yellow"></i> Oops! {{ trans('adminlte_lang::message.pagerestricted') }}.</h3>
        <p>
            {{ trans('adminlte_lang::message.notauthorised') }}
            {{ trans('adminlte_lang::message.mainwhile') }} <a href='{{ url('/') }}'>{{ trans('adminlte_lang::message.returndashboard') }}</a>
        </p>

    </div><!-- /.error-content -->
</div><!-- /.error-page -->
@endsection
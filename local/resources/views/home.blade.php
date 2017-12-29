@extends('layouts.app')

@section('htmlheader_title')
    @role('admin')
        Admin Portal
    @else
        Home Portal
    @endrole
@endsection

@section('contentheader_title')
    {{ trans('Admin Portal') }}
@endsection

@section('main-content')
    <div class="container spark-screen">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Admin Portal</div>
                    <div class="panel-body">
                        {{ trans('adminlte_lang::message.logged') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

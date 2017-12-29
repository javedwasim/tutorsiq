<!DOCTYPE html>
<html>
<head>
    <link href="{{asset('/css/landingpage.css')}}" rel="stylesheet" type="text/css"/>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet"
          type="text/css"/>
    <!-- Ionicons -->
    <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css"/>
    <!-- Theme style -->
    <link href="{{ asset('/css/AdminLTE.css') }}" rel="stylesheet" type="text/css"/>
    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
          page. However, you can choose any other skin. Make sure you
          apply the skin class to the body tag so the changes take effect.
    -->
    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
    <link href="{{ asset('/css/skins/skin-blue.css') }}" rel="stylesheet" type="text/css"/>
    <!-- iCheck -->
    <link href="{{ asset('/plugins/iCheck/square/blue.css') }}" rel="stylesheet" type="text/css"/>


</head>

<body class="hold-transition register-page">
<div class="register-box">
    <div class="register-logo">
        <a href="{{ url('/home') }}"><b>Home</b>Tuition</a>
    </div>

    @if (session('status'))
        <div class="alert alert-danger">
            <strong>Whoops!</strong> {{ trans('adminlte_lang::message.someproblems') }}<br><br>
            <ul>{{ session('status') }}</ul>
        </div>
    @endif

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> {{ trans('adminlte_lang::message.someproblems') }}<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="register-box-body">
        <p class="login-box-msg">{{ trans('adminlte_lang::message.registermember') }}</p>
        <form action="{{ url('register/student') }}" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group has-feedback">
                <input type="text" class="form-control" placeholder="{{ trans('adminlte_lang::message.fullname') }}"
                       name="name" value="{{ old('name') }}" required>
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="email" class="form-control" placeholder="{{ trans('adminlte_lang::message.email') }}"
                       name="email" value="{{ old('email') }}" required>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>

            <div class="form-group has-feedback">
                    <select name="gender" required class="form-control">
                        <option value="" disabled selected>Select Gender</option>
                        <?php foreach ($gender as $gender): ?>
                        <option value="<?php echo $gender->id ?>"><?php echo $gender->name ?></option>
                        <?php endforeach; ?>
                    </select>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>

            <div class="form-group has-feedback">
                <input type="text" class="form-control"
                       placeholder="{{ trans('adminlte_lang::message.phone-number') }}" name="phone"
                       value="{{ old('phone-number') }}"/>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>

            <div class="form-group has-feedback">
                <input type="text" class="form-control"
                       placeholder="{{ trans('adminlte_lang::message.address') }}" name="address_line1"
                       value="{{ old('address') }}"/>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>

            <div class="form-group has-feedback">
                <input type="text" class="form-control"
                       placeholder="{{ trans('adminlte_lang::message.city') }}" name="city"
                       value="{{ old('city') }}"/>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>

            <div class="row">
                <div class="col-md-1">
                    <label>
                        <div class="checkbox_register icheck">
                            <label>
                                <input type="checkbox" name="terms" required>
                            </label>
                        </div>
                    </label>
                </div><!-- /.col -->
                <div class="col-md-6">
                    <div class="form-group">
                        <button type="button" class="btn btn-flat" data-toggle="modal"
                                data-target="#termsModal">{{ trans('adminlte_lang::message.terms') }}</button>
                    </div>
                </div><!-- /.col -->
                <div class="col-md-4 col-md-push-1">
                    <button type="submit"
                            class="btn btn11 outline">{{ trans('adminlte_lang::message.register') }}</button>
                </div><!-- /.col -->
            </div>
        </form>

        <a href="{{ url('/login') }}" class="text-center">{{ trans('adminlte_lang::message.membreship') }}</a>
    </div><!-- /.form-box -->
</div><!-- /.register-box -->
<!-- Terms and conditions modal -->
<div class="modal" id="termsModal" tabindex="-1" role="dialog" aria-labelledby="Terms and conditions" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Terms and conditions</h3>
            </div>

            <div class="modal-body">
                <p>Lorem ipsum dolor sit amet, veniam numquam has te. No suas nonumes recusabo mea, est ut graeci definitiones. His ne melius vituperata scriptorem, cum paulo copiosae conclusionemque at. Facer inermis ius in, ad brute nominati referrentur vis. Dicat erant sit ex. Phaedrum imperdiet scribentur vix no, ad latine similique forensibus vel.</p>
                <p>Dolore populo vivendum vis eu, mei quaestio liberavisse ex. Electram necessitatibus ut vel, quo at probatus oportere, molestie conclusionemque pri cu. Brute augue tincidunt vim id, ne munere fierent rationibus mei. Ut pro volutpat praesent qualisque, an iisque scripta intellegebat eam.</p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn11 outline custom-close">OK</button>
            </div>
        </div>
    </div>
</div>
@include('layouts.partials.scripts_auth')
<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });

    //remove body padding padding added by modal
    $('#register-view').on('hidden.bs.modal', function () {
        $("body").css({"padding":"0px"});
    });

    $(function () {
        $(".custom-close").on('click', function() {
            $('#termsModal').modal('hide');
        });
    });

</script>
@include('students.modal')
</body>

</html>

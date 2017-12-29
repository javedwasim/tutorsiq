<article class="a-padd works11">
    <div class="container">

        <div class="jumbotron text-xs-center">
            <h1 class="display-3">Thank You!</h1>
            <p class="lead"><strong>Please check your email</strong> for further instructions on how to complete your
                account setup.</p>
            <hr>
            <form method="post" action="{{ url('/tutorsignup/finish') }}" enctype="multipart/form-data" id="sendemailform" >
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="teacherid" value="<?php echo !empty(session('teacherid'))?session('teacherid'):''; ?>">
                <input type="hidden" name="confirmation_code" value="<?php echo session('confirmation_code'); ?>">
                <p>
                    Having trouble? <a href=""><button type="submit" class="btn btn11 outline ">Send Email</button></a>
                </p>
            </form>
            <p class="lead">

                <a href="<?php echo URL::to('/');  ?>" class="btn btn11">Continue to homepage</a>
            </p>
        </div>
    </div>
</article>

@section('page_specific_inline_scripts')
    <script>
        $( '#sendemailform' ).on( 'submit', function(e) {

            e.preventDefault();
            var formData = new FormData($(this)[0]);

            $.ajax({

                url:'{{url("tutorsignup/troubleemail")}}',
                type: "POST",
                data: formData,
                async: false,
                beforeSend: function(){
                    $("#wait").modal();
                },
                success: function (response) {

                    $('#wait').modal('hide');
                    toastr.success('Thanks for registeration. Please check your mail to activate your account');

                },
                cache: false,
                contentType: false,
                processData: false
            });


        });
    </script>
@endsection
<footer class="a-padd pb-0">

    <?php $user = Auth::user(); $tutorsignup = Request::segment(1); ?>

    <?php if(!isset($user) && $tutorsignup != 'classified') : ?>
        <div class=" container">
            <div class="row">

                <div class="col-md-4 mb-4">
                    <div>
                        <img src="{{asset('img/logo.png')}}" class="mb-md-4 mb-sm-3" alt="">
                        <p>
                            Aliquam lacus tur http:///lucrative.com lobortis quis dolor sed, nec convallis velit vestibulum
                            ac
                            dignissim rhoncus neque.
                        </p>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="ml-md-5">
                        <h3 class="mb-md-5 mb-sm-4">E-mail Us</h3>
                        <p><i class="fa fa-envelope"> </i> info@tutorsiq.com</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div>
                        <h3 class="mb-md-5 mb-sm-4">Subscribe To Our Newsletter</h3>
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="yourmail@mail.com">
                            <span class="input-group-btn">
                                <button class="btn" type="button">Send</button>
                              </span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="bottomfooter pt-3 mt-5 bg-inverse">
            <div class="container">

                <div class="row">

                    <div class="col-md-6">
                        <p class="m-0">© 2017 Tutorsiq. All Rights Reserved </p>
                    </div>

                    <div class="col-md-6 text-right">
                        <ul class="list-inline">
                            <li class="list-inline-item"><a href="#" class="fa fa-facebook"></a></li>
                            <li class="list-inline-item"><a href="#" class="fa fa-twitter"></a></li>
                            <li class="list-inline-item"><a href="#" class="fa fa-google-plus"></a></li>
                        </ul>
                    </div>

                </div>

            </div>
        </div>
    <?php endif; ?>

</footer>
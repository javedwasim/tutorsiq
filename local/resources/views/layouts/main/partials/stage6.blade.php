<article class="a-padd works11">
    <div class="container">
        @include('layouts.main.partials.wizard')

        <div class="row setup-content" id="step-2">
            <div class="box box-danger">
                <div class="box-header with-border">
                    <h3 class="box-title">Terms and Conditions</h3>
                </div>
                <!-- /.box-header -->
                <form method="post" action="{{ url('/tutorsignup/finish') }}" enctype="multipart/form-data" id="stage6form" >

                    <input type="hidden" name="step" value="3">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="tuid" value="<?php echo !empty(session('tuid'))?session('tuid'):''; ?>">
                    <input type="hidden" name="teacherid" value="<?php echo !empty(session('teacherid'))?session('teacherid'):''; ?>">


                    <div class="box-body">
                        <!-- Gurantor Reference -->
                        <div class="h-holder">
                            <div class="heading">Visited or not</div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="reference_gurantor" class="col-sm-12 control-label" style="text-align: left">
                                            Have you visited our office with all Documents(Tuition will be given after visit in office)</label>

                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label  class="col-sm-12 control-label">
                                                <input type="radio" name="visited" id="dovisit" value="1" class="minimal">
                                                Visited
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label  class="col-sm-12 control-label" style="text-align: left;font-weight: normal">
                                                <input type="radio" name="visited" id="notvisit" value="0"class="minimal">
                                                Not Visited
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Gurantor Reference -->
                        <!-- Terms and Conditions -->
                        <div class="h-holder">
                            <div class="heading">Terms and Conditions <span style="color: red;">*</span></div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label  class="col-sm-12 control-label" style="text-align: left;font-weight: normal">
                                            @include('layouts.partials.terms')
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label  class="col-sm-12 control-label">
                                            <input type="radio" name="accept" id="do"
                                                   value="1" class="minimal" required>
                                            I accept
                                        </label>
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label  class="col-sm-12 control-label" style="text-align: left;font-weight: normal">
                                            <input type="radio" name="accept" id="not"
                                                   value="0" class="minimal" required>
                                             I don't accept
                                        </label>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- Terms and Conditions -->
                        <button type="submit" class="btn btn11 outline pull-right">Finish</button>
                        <a href="<?php echo URL::to('tutorsignup/step5');  ?>" class="btn btn11 outline pull-left">Previous Step</a>
                    </div>
                    <!-- /.box-body -->
                </form>
            </div>
        </div>
    </div>
</article>


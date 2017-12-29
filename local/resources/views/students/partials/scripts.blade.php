<!-- jQuery first, then Tether, then Bootstrap JS. -->
<script src="{{ asset('plugins/jQuery/jQuery-2.1.4.min.js') }}"></script>
{{--<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js"></script>--}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>
<script src="{{ asset('js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('js/bootstrap_front.min.js') }}"></script>

<script src="{{ asset('plugins/toastr/toastr.min.js') }}" type="text/javascript"></script>
<script type="text/javascript">
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "positionClass": "toast-top-center",
        "preventDuplicates": true,
        //"toastClass": "animated fadeInDown",
        "onclick": null,
        "showDuration": "1000",
        "hideDuration": "0",
        "timeOut": "0",
        "extendedTimeOut": "1000",
        "showEasing": "linear",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
</script>
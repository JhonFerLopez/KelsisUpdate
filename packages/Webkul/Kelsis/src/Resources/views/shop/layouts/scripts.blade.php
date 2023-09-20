<script type="text/javascript" src="{{ asset(mix('/js/manifest.js', 'themes/velocity/assets')) }}"></script>

<script type="text/javascript" src="{{ asset(mix('/js/velocity-core.js', 'themes/velocity/assets')) }}"></script>

<script type="text/javascript" src="{{ asset(mix('/js/components.js', 'themes/velocity/assets')) }}"></script>

<script type="text/javascript" src="{{ asset(mix('/js/bootstrap.bundle.min.js', 'themes/kelsis/assets')) }}"></script>
<script type="text/javascript" src="{{ asset(mix('/js/tiny-slider.js', 'themes/kelsis/assets')) }}"></script>
<script type="text/javascript" src="{{ asset(mix('/js/custom.js', 'themes/kelsis/assets')) }}"></script>

<script type="text/javascript" src="{{ asset(mix('/js/jquery.countdown.js', 'themes/kelsis/assets')) }}"></script>
<script type="text/javascript" src="{{ asset(mix('/js/jquery.isotope-3.0.6.min.js', 'themes/kelsis/assets')) }}"></script>
<script type="text/javascript" src="{{ asset(mix('/js/waypoints.js', 'themes/kelsis/assets')) }}"></script>
<script type="text/javascript" src="{{ asset(mix('/js/owl.carousel.min.js', 'themes/kelsis/assets')) }}"></script>
<script type="text/javascript" src="{{ asset(mix('/js/jquery.magnific-popup.min.js', 'themes/kelsis/assets')) }}"></script>
<script type="text/javascript" src="{{ asset(mix('/js/jquery.meanmenu.min.js', 'themes/kelsis/assets')) }}"></script>
<script type="text/javascript" src="{{ asset(mix('/js/sticker.js', 'themes/kelsis/assets')) }}"></script>
<script type="text/javascript" src="{{ asset(mix('/js/main.js', 'themes/kelsis/assets')) }}"></script>

<script type="text/javascript">
    (() => {
        /* activate session messages */
        let message = @json($velocityHelper->getMessage());
        if (message.messageType && message.message !== '') {
            window.showAlert(message.messageType, message.messageLabel, message.message);
        }

        /* activate server error messages */
        window.serverErrors = [];
        @if (isset($errors))
            @if (count($errors))
                window.serverErrors = @json($errors->getMessages());
            @endif
        @endif

        /* add translations */
        window._translations = @json($velocityHelper->jsonTranslations());
    })();

    /**
     * Wishist form will dynamically create and execute.
     *
     * @param {!string} action
     * @param {!string} method
     * @param {!string} csrfToken
     */
    function submitWishlistForm(action, method, isConfirm, csrfToken) {
        if (isConfirm && ! confirm('{{ __('shop::app.checkout.cart.cart-remove-action') }}')) return;

        let form = document.createElement('form');
            form.method = 'POST';
            form.action = action;

        let _methodElement = document.createElement('input');
            _methodElement.type = 'hidden';
            _methodElement.name = '_method';
            _methodElement.value = method;
            form.appendChild(_methodElement);

        let _tokenElement = document.createElement('input');
            _tokenElement.type = 'hidden';
            _tokenElement.name ='_token';
            _tokenElement.value = csrfToken;
            form.appendChild(_tokenElement);

        document.body.appendChild(form);
        form.submit();
    }
</script>

@stack('scripts')

<script>
    {!! core()->getConfigData('general.content.custom_scripts.custom_javascript') !!}
</script>

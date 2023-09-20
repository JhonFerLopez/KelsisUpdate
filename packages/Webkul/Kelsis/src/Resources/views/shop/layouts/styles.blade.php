<link href="https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap" rel="stylesheet">

{{-- preloaded fonts --}}
<!--<link rel="preload" href="{{ asset('themes/kelsis/assets/fonts/font-rango/rango.ttf') . '?o0evyv' }}" as="font" crossorigin="anonymous" />-->

{{-- bootstrap --}}
<!--<link rel="stylesheet" href="{{ asset('themes/kelsis/assets/css/bootstrap.min.css') }}" />-->
<link rel="stylesheet" href="{{ asset('themes/kelsis/assets/css/bootstrap5.min.css') }}" />

{{-- bootstrap flipped for rtl --}}
<!--@if (
    core()->getCurrentLocale()
    && core()->getCurrentLocale()->direction === 'rtl'
)
    <link href="{{ asset('themes/kelsis/assets/css/bootstrap-flipped.css') }}" rel="stylesheet">
@endif-->

{{-- mix versioned compiled file --}}
<!--<link rel="stylesheet" href="{{ asset(mix('/css/velocity.css', 'themes/kelsis/assets')) }}" />-->

{{-- extra css --}}
<!--@stack('css')-->

{{-- font-awesome All Ajax --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
<link rel="stylesheet" href="{{ asset(mix('/css/tiny-slider.css', 'themes/kelsis/assets')) }}" />

<link rel="stylesheet" href="{{ asset(mix('/css/all.min.css', 'themes/kelsis/assets')) }}" />
<link rel="stylesheet" href="{{ asset(mix('/css/owl.carousel.css', 'themes/kelsis/assets')) }}" />
<link rel="stylesheet" href="{{ asset(mix('/css/magnific-popup.css', 'themes/kelsis/assets')) }}" />
<link rel="stylesheet" href="{{ asset(mix('/css/animate.css', 'themes/kelsis/assets')) }}" />
<link rel="stylesheet" href="{{ asset(mix('/css/meanmenu.min.css', 'themes/kelsis/assets')) }}" />

<link rel="stylesheet" href="{{ asset(mix('/css/header.css', 'themes/kelsis/assets')) }}" />
<link rel="stylesheet" href="{{ asset(mix('/css/style.css', 'themes/kelsis/assets')) }}" />

{{-- custom css --}}
<!--<style>
    {!! core()->getConfigData('general.content.custom_scripts.custom_css') !!}
</style>-->
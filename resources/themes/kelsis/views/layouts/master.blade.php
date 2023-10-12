<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
	{{-- title --}}
	<title>@yield('page_title')</title>

	{{-- meta data --}}
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta http-equiv="content-language" content="{{ app()->getLocale() }}">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="base-url" content="{{ url()->to('/') }}">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	{!! view_render_event('bagisto.shop.layout.head') !!}

	{{-- for extra head data --}}
	@yield('head')

	{{-- seo meta data --}}
	@yield('seo')

	{{-- fav icon --}}
	@if ($favicon = core()->getCurrentChannel()->favicon_url)
	<link rel="icon" sizes="16x16" href="{{ $favicon }}" />
	@else
	<link rel="icon" sizes="16x16" href="{{ asset('/themes/kelsis/assets/images/static/v-icon.png') }}" />
	@endif

	{{-- all styles --}}
	@include('shop::layouts.styles')
</head>

<body @if (core()->getCurrentLocale() && core()->getCurrentLocale()->direction === 'rtl') class="rtl" @endif>
	{!! view_render_event('bagisto.shop.layout.body.before') !!}
	<!--PreLoader-->
	<div class="loader">
		<div class="loader-inner">
			<div class="circle"></div>
		</div>
	</div>
	<!--PreLoader Ends-->


	<!-- header -->
	<div class="top-header-area @if(request()->is('/')) menu-obasolute @endif" id="sticker">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-sm-12 text-center">
					<div class="main-menu-wrap">
						<!-- logo -->
						<div class="site-logo">
							<a href="/">
							<img class="logo" src="{{ core()->getCurrentChannel()->logo_url ?? asset('themes/velocity/assets/images/logo-text.png') }}" alt="" />
							</a>
						</div>
						<!-- logo -->

						<!-- menu start -->
						<nav class="main-menu">
							<ul>
								<li class="current-list-item"><a href="/">Home</a></li>
								<li><a href="colorantes">Colorantes</a></li>
								<li><a href="#">Categorias</a>
									<ul class="sub-menu">
										<li><a href="aditivos">Aditivos</a></li>
										<li><a href="colorantes">Colorantes</a></li>
									</ul>
								</li>
								<li><a href="news.html">News</a>
									<ul class="sub-menu">
										<li><a href="news.html">News</a></li>
										<li><a href="single-news.html">Single News</a></li>
									</ul>
								</li>
								<li><a href="contact.html">Contact</a></li>
								<li><a href="shop.html">Shop</a>
									<ul class="sub-menu">
										<li><a href="shop.html">Shop</a></li>
										<li><a href="checkout.html">Check Out</a></li>
										<li><a href="single-product.html">Single Product</a></li>
										<li><a href="cart.html">Cart</a></li>
									</ul>
								</li>
								<li>
									<div class="header-icons">
										<a class="mobile-hide search-bar-icon" href="#">
											<img src="{{ asset('themes/kelsis/assets/images/icon-search.svg') }}">
										</a>
										<a class="shopping-cart" href="/checkout/onepage">
											<img src="{{ asset('themes/kelsis/assets/images/cart.svg') }}">
										</a>
										<!--@include('velocity::layouts.top-nav.login-section')-->
									</div>
								</li>
							</ul>
						</nav>
						<a class="mobile-show search-bar-icon" href="#"><i class="fas fa-search"></i></a>
						<div class="mobile-menu"></div>
						<!-- menu end -->
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- header -->

	<!-- search area -->
	<div class="search-area">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<span class="close-btn"><i class="fas fa-window-close"></i></span>
					<div class="search-bar">
						<div class="search-bar-tablecell">
							<h3>Busqueda por:</h3>
							<input type="text" placeholder="Palabra clave">
							<button type="submit">Buscar <i class="fas fa-search"></i></button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end search area -->

	@if(request()->is('/'))
		<!-- hero area -->
		<div class="hero-area hero-bg">
			<div class="container">
				<div class="row">
					<div class="col-lg-9 offset-lg-2 text-center">
						<div class="hero-text">
							<div class="hero-text-tablecell">
								<p class="subtitle">Frutaroma</p>
								<img src="{{ asset('themes/kelsis/assets/images/img-banner.png') }}" class="img-fluid">
								<div class="hero-btns">
									<a href="shop.html" class="boxed-btn">Ver productos</a>
									<a href="contact.html" class="bordered-btn">Contactanos</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- end hero area -->

		<!-- features list section -->
		<div class="list-section pt-80 pb-80">
			<div class="container">
				<div class="row">
					<div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
						<div class="list-box d-flex align-items-center">
							<div class="list-icon">
								<i class="fas fa-shipping-fast"></i>
							</div>
							<div class="content">
								<h3>Free Shipping</h3>
								<p>When order over $75</p>
							</div>
						</div>
					</div>
					<div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
						<div class="list-box d-flex align-items-center">
							<div class="list-icon">
								<i class="fas fa-phone-volume"></i>
							</div>
							<div class="content">
								<h3>24/7 Support</h3>
								<p>Get support all day</p>
							</div>
						</div>
					</div>
					<div class="col-lg-4 col-md-6">
						<div class="list-box d-flex justify-content-start align-items-center">
							<div class="list-icon">
								<i class="fas fa-sync"></i>
							</div>
							<div class="content">
								<h3>Refund</h3>
								<p>Get refund within 3 days!</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- end features list section -->
	@endif
	
	{{-- main app --}}
	<div id="app">	
		<product-quick-view v-if="$root.quickView"></product-quick-view>
		<div class="">
			<div class="row col-12 remove-padding-margin">
				<div class="col-12 no-padding content" id="home-right-bar-container">
					<div class="container-right row no-margin col-12 no-padding">
						{!! view_render_event('bagisto.shop.layout.content.before') !!}
						@yield('content-wrapper')
						{!! view_render_event('bagisto.shop.layout.content.after') !!}
					</div>
				</div>
			</div>
		</div>
		<div class="container">
			{!! view_render_event('bagisto.shop.layout.full-content.before') !!}
			@yield('full-content-wrapper')
			{!! view_render_event('bagisto.shop.layout.full-content.after') !!}
		</div>
	</div>

	@if(request()->is('/'))
		<!-- Start Product Section -->
		<div class="product-section">
			<div class="container">
				<div class="row">
					<!-- Start Column 1 -->
					<div class="col-md-12 col-lg-3 mb-5 mb-lg-0">
						<h2 class="mb-4 section-title">Kelsis</h2>
						<p class="mb-4">
							Kelsis, palabra que tiene como significado “Cima”, “El punto más alto”, con orígenes etimológicos que datan desde las primeras lenguas indoeuropeas.
						</p>
						<p><a href="shop.html" class="btn">Explore</a></p>
					</div> 
					<!-- End Column 1 -->

					<!-- Start Column 2 -->
					<div class="col-12 col-md-4 col-lg-3 mb-5 mb-md-0">
						<a class="product-item" href="cart.html">
							<img src="{{ asset('themes/kelsis/assets/images/colorantes.png') }}" class="img-fluid product-thumbnail">
							<h3 class="product-title">Colorantes</h3>
							<strong class="product-price">$3.967 - 22.078</strong>

							<span class="icon-cross">
								<img src="{{ asset('themes/kelsis/assets/images/cross.svg') }}" class="img-fluid">
							</span>
						</a>
					</div> 
					<!-- End Column 2 -->

					<!-- Start Column 3 -->
					<div class="col-12 col-md-4 col-lg-3 mb-5 mb-md-0">
						<a class="product-item" href="cart.html">
							<img src="{{ asset('themes/kelsis/assets/images/escencias.png') }}" class="img-fluid product-thumbnail">
							<h3 class="product-title">Esencias DeliSabor</h3>
							<strong class="product-price">$3.967 - 22.078</strong>

							<span class="icon-cross">
								<img src="{{ asset('themes/kelsis/assets/images/cross.svg') }}" class="img-fluid">
							</span>
						</a>
					</div>
					<!-- End Column 3 -->

					<!-- Start Column 4 -->
					<div class="col-12 col-md-4 col-lg-3 mb-5 mb-md-0">
						<a class="product-item" href="cart.html">
							<img src="{{ asset('themes/kelsis/assets/images/saborizantes.png') }}" class="img-fluid product-thumbnail">
							<h3 class="product-title">Saborizantes</h3>
							<strong class="product-price">$43.000</strong>

							<span class="icon-cross">
								<img src="{{ asset('themes/kelsis/assets/images/cross.svg') }}" class="img-fluid">
							</span>
						</a>
					</div>
					<!-- End Column 4 -->

				</div>
			</div>
		</div>
		<!-- End Product Section -->
	@endif

	<!-- Start Why Choose Us Section 
	<div class="why-choose-section">
		<div class="container">
			<div class="row justify-content-between">
				<div class="col-lg-6">
					<h2 class="section-title">Why Choose Us</h2>
					<p>Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate velit imperdiet dolor tempor tristique.</p>

					<div class="row my-5">
						<div class="col-6 col-md-6">
							<div class="feature">
								<div class="icon">
									<img src="{{ asset('themes/kelsis/assets/images/truck.svg') }}" alt="Image" class="imf-fluid">
								</div>
								<h3>Fast &amp; Free Shipping</h3>
								<p>Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate.</p>
							</div>
						</div>

						<div class="col-6 col-md-6">
							<div class="feature">
								<div class="icon">
									<img src="{{ asset('themes/kelsis/assets/images/bag.svg') }}" alt="Image" class="imf-fluid">
								</div>
								<h3>Easy to Shop</h3>
								<p>Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate.</p>
							</div>
						</div>

						<div class="col-6 col-md-6">
							<div class="feature">
								<div class="icon">
									<img src="{{ asset('themes/kelsis/assets/images/support.svg') }}" alt="Image" class="imf-fluid">
								</div>
								<h3>24/7 Support</h3>
								<p>Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate.</p>
							</div>
						</div>

						<div class="col-6 col-md-6">
							<div class="feature">
								<div class="icon">
									<img src="images/return.svg" alt="Image" class="imf-fluid">
								</div>
								<h3>Hassle Free Returns</h3>
								<p>Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate.</p>
							</div>
						</div>

					</div>
				</div>

				<div class="col-lg-5">
					<div class="img-wrap">
						<img src="images/why-choose-us-img.jpg" alt="Image" class="img-fluid">
					</div>
				</div>

			</div>
		</div>
	</div>-->
	<!-- End Why Choose Us Section -->

	<!-- Start We Help Section
	<div class="we-help-section">
		<div class="container">
			<div class="row justify-content-between">
				<div class="col-lg-7 mb-5 mb-lg-0">
					<div class="imgs-grid">
						<div class="grid grid-1"><img src="images/img-grid-1.jpg" alt="Untree.co"></div>
						<div class="grid grid-2"><img src="images/img-grid-2.jpg" alt="Untree.co"></div>
						<div class="grid grid-3"><img src="images/img-grid-3.jpg" alt="Untree.co"></div>
					</div>
				</div>
				<div class="col-lg-5 ps-lg-5">
					<h2 class="section-title mb-4">We Help You Make Modern Interior Design</h2>
					<p>Donec facilisis quam ut purus rutrum lobortis. Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate velit imperdiet dolor tempor tristique. Pellentesque habitant morbi tristique senectus et netus et malesuada</p>

					<ul class="list-unstyled custom-list my-4">
						<li>Donec vitae odio quis nisl dapibus malesuada</li>
						<li>Donec vitae odio quis nisl dapibus malesuada</li>
						<li>Donec vitae odio quis nisl dapibus malesuada</li>
						<li>Donec vitae odio quis nisl dapibus malesuada</li>
					</ul>
					<p><a herf="#" class="btn">Explore</a></p>
				</div>
			</div>
		</div>
	</div> -->
	<!-- End We Help Section -->

	<!-- Start Popular Product
	<div class="popular-product">
		<div class="container">
			<div class="row">

				<div class="col-12 col-md-6 col-lg-4 mb-4 mb-lg-0">
					<div class="product-item-sm d-flex">
						<div class="thumbnail">
							<img src="images/product-1.png" alt="Image" class="img-fluid">
						</div>
						<div class="pt-3">
							<h3>Nordic Chair</h3>
							<p>Donec facilisis quam ut purus rutrum lobortis. Donec vitae odio </p>
							<p><a href="#">Read More</a></p>
						</div>
					</div>
				</div>

				<div class="col-12 col-md-6 col-lg-4 mb-4 mb-lg-0">
					<div class="product-item-sm d-flex">
						<div class="thumbnail">
							<img src="images/product-2.png" alt="Image" class="img-fluid">
						</div>
						<div class="pt-3">
							<h3>Kruzo Aero Chair</h3>
							<p>Donec facilisis quam ut purus rutrum lobortis. Donec vitae odio </p>
							<p><a href="#">Read More</a></p>
						</div>
					</div>
				</div>

				<div class="col-12 col-md-6 col-lg-4 mb-4 mb-lg-0">
					<div class="product-item-sm d-flex">
						<div class="thumbnail">
							<img src="images/product-3.png" alt="Image" class="img-fluid">
						</div>
						<div class="pt-3">
							<h3>Ergonomic Chair</h3>
							<p>Donec facilisis quam ut purus rutrum lobortis. Donec vitae odio </p>
							<p><a href="#">Read More</a></p>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div> -->
	<!-- End Popular Product -->

	<!-- Start Blog Section
	<div class="blog-section">
		<div class="container">
			<div class="row mb-5">
				<div class="col-md-6">
					<h2 class="section-title">Recent Blog</h2>
				</div>
				<div class="col-md-6 text-start text-md-end">
					<a href="#" class="more">View All Posts</a>
				</div>
			</div>

			<div class="row">

				<div class="col-12 col-sm-6 col-md-4 mb-4 mb-md-0">
					<div class="post-entry">
						<a href="#" class="post-thumbnail"><img src="images/post-1.jpg" alt="Image" class="img-fluid"></a>
						<div class="post-content-entry">
							<h3><a href="#">First Time Home Owner Ideas</a></h3>
							<div class="meta">
								<span>by <a href="#">Kristin Watson</a></span> <span>on <a href="#">Dec 19, 2021</a></span>
							</div>
						</div>
					</div>
				</div>

				<div class="col-12 col-sm-6 col-md-4 mb-4 mb-md-0">
					<div class="post-entry">
						<a href="#" class="post-thumbnail"><img src="images/post-2.jpg" alt="Image" class="img-fluid"></a>
						<div class="post-content-entry">
							<h3><a href="#">How To Keep Your Furniture Clean</a></h3>
							<div class="meta">
								<span>by <a href="#">Robert Fox</a></span> <span>on <a href="#">Dec 15, 2021</a></span>
							</div>
						</div>
					</div>
				</div>

				<div class="col-12 col-sm-6 col-md-4 mb-4 mb-md-0">
					<div class="post-entry">
						<a href="#" class="post-thumbnail"><img src="images/post-3.jpg" alt="Image" class="img-fluid"></a>
						<div class="post-content-entry">
							<h3><a href="#">Small Space Furniture Apartment Ideas</a></h3>
							<div class="meta">
								<span>by <a href="#">Kristin Watson</a></span> <span>on <a href="#">Dec 12, 2021</a></span>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div> -->
	<!-- End Blog Section -->	

	{{-- footer --}}
	@section('footer')
	{!! view_render_event('bagisto.shop.layout.footer.before') !!}

	@include('shop::layouts.footer.index')

	{!! view_render_event('bagisto.shop.layout.footer.after') !!}
	@show

	{!! view_render_event('bagisto.shop.layout.body.after') !!}

	{{-- alert container --}}
	<div id="alert-container"></div>

	{{-- all scripts --}}
	@include('shop::layouts.scripts')
</body>

</html>
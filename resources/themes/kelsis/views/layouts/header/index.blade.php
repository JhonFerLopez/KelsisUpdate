<header class="">
	<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar ftco-navbar-light" id="ftco-navbar">
		<div class="container k-container">
			<a class="left navbar-brand" href="{{ route('shop.home.index') }}" aria-label="Logo"
			style="border: 1px solid red;">
				<img class="logo" src="{{ core()->getCurrentChannel()->logo_url ?? asset('themes/kelsis/assets/images/logo-text.png') }}" alt="" />
			</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
				<span class="fa fa-bars"></span> Menu
			</button>
			<div class="collapse navbar-collapse" id="ftco-nav" style="border: 1px solid blue;">
				<ul class="navbar-nav m-auto">
					<li class="nav-item active">
						<a href="#" class="nav-link">Home</a>
					</li>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="dropdown04" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Page</a>
						<div class="dropdown-menu" aria-labelledby="dropdown04">
							<a class="dropdown-item" href="#">Page 1</a>
							<a class="dropdown-item" href="#">Page 2</a>
							<a class="dropdown-item" href="#">Page 3</a>
							<a class="dropdown-item" href="#">Page 4</a>
						</div>
					</li>
					<li class="nav-item"><a href="#" class="nav-link">Catalog</a></li>
					<li class="nav-item"><a href="#" class="nav-link">Blog</a></li>
					<li class="nav-item"><a href="#" class="nav-link">Contact</a></li>
				</ul>
			</div>
			@include('kelsis::shop.layouts.particals.search-bar')
			
			<div class="left-wrapper k-left-wrapper" style="border: 1px solid yellow;">

				@include('kelsis::shop.layouts.particals.header-compts', ['isText' => true])

				@include('kelsis::layouts.top-nav.login-section')

				{!! view_render_event('bagisto.shop.layout.header.cart-item.after') !!}
			</div>
	</nav>
</header>

@push('scripts')
<script type="text/javascript">
	(() => {
		document.addEventListener('scroll', e => {
			scrollPosition = Math.round(window.scrollY);

			if (scrollPosition > 50) {
				document.querySelector('header').classList.add('header-shadow');
			} else {
				document.querySelector('header').classList.remove('header-shadow');
			}
		});
	})();
</script>
@endpush

<!doctype html>
<html lang="en" dir="rtl">

@include('back.inc.head')

<body>
	<!--wrapper-->
	<div class="wrapper">
		@include('back.inc.sidebar')
		@include('back.inc.header')

		<!--start page wrapper -->
		<div class="page-wrapper">
			<div class="page-content">
				@yield('body')
			</div>
		</div>
		<!--end page wrapper -->
		<!--start overlay-->
		<div class="overlay toggle-icon"></div>
		<!--end overlay-->
		<!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
		<!--End Back To Top Button-->
		<footer class="page-footer">
			<p class="mb-0">Copyright © 2025. All right reserved.</p>
		</footer>
	</div>
	<!--end wrapper-->

	@include('back.inc.scripts')
    @stack('scripts')
</body>

</html>

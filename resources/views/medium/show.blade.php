<!DOCTYPE html>
<html class="no-js">
<head>
	<meta charset="utf-8">
	<title>{{ $medium->title }}</title>
	<meta name="description" content="">
	<meta name="HandheldFriendly" content="True">
	<meta name="MobileOptimized" content="320">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimal-ui,maximum-scale=2">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimal-ui,maximum-scale=1">
	<meta http-equiv="cleartype" content="on">

	<link rel="stylesheet" href="{{ asset('wow_book/wow_book.css') }}" type="text/css" />
</head>
<body>
	<!-- Add your site or application content here -->
	<div class='book_container'>
		<div id="book">
      <div></div>
    </div>
	</div>

	<!-- if you don't need support for IE8 use jquery 2.1 -->
	<script
        src="https://code.jquery.com/jquery-1.12.4.min.js"
        integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="
        crossorigin="anonymous"></script>

  <script src="{{ asset('wow_book/wow_book.min.js') }}"></script>
  <script src="{{ asset('wow_book/pdf.combined.min.js') }}"></script>
	<script>
		$(function(){

			var bookOptions = {
        pdf: '{{ url(\Storage::url($medium->path)) }}',
				height   : 500,
				width    : 1000,
				maxHeight : 600,

				centeredWhenClosed : true,
				hardcovers : true,
				toolbar : "lastLeft, left, right, lastRight, toc, zoomin, zoomout, slideshow, flipsound, fullscreen, thumbnails, download",
				thumbnailsPosition : 'left',
				responsiveHandleWidth : 50,

				container: window,
				containerPadding: "20px"
			};

			$('#book').wowBook( bookOptions );

		})
	</script>

</body>
</html>

@push('after-styles')
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.2.5/jquery.fancybox.min.css">
@endpush
@push('after-scripts')
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.2.5/jquery.fancybox.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function () {
			$("a.fancy-box").fancybox({});
		})

		function initFancyBox(el, options) 
		{ 
			if (! options) { options = {} }
			let fan = el.fancybox(options) 
		}
	</script>
@endpush

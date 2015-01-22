##
# Handle theme-specific functionality.
# 
# @package Inkblot
##

document.documentElement.className = document.documentElement.className.replace(/no-js/, 'js')

jQuery(($)->
	$('.responsive .banner select').on('change', ($e)->
		if $(this).val()
			window.location.href = $(this).val()
	)
	
	if $('body').hasClass('page-template-templatewebcomic-infinite-php')
		$(window).on('scroll', ($e)->
			if $('[name="inkblot-webcomic-infinite-stop"]').val() or undefined != $('main').data('loading')
				return
			
			if ! $('main').children().length or $('main').children().last().offset().top < $(window).scrollTop() + $(window).height()
				$('main').data('loading', true)
				
				$offset = parseInt($('main').children().length + $('main').data('webcomic-offset'))
				$data =
					'inkblot-webcomic-infinite': true
					page: $('main').data('page-id')
					order: $('main').data('webcomic-order')
					offset: $offset
					collection: $('main').data('webcomic-collection')
				$request = 
					url: window.location.href,
					type: 'post',
					data: $.param($data)
					success: ($data)->
						$('main').append($data)
						
						history.replaceState(
							$data,
							'',
							window.location.href.split('?')[0] + '?offset=' + $offset
						)
					complete: ($object, $status)->
						$('main').removeData('loading')
						
						if $('body').height() <= $(window).height()
							$(window).trigger('scroll')
				
				$.ajax($request)
		).trigger('scroll')
)
( function( $ ) {

	$( document ).ready(function( $ ) {

		// Tabs.
		jQuery('.tabs .tab-links a','.magazine_point_widget_tabbed').on('click', function(e) {
			var currentAttrValue = jQuery(this).attr('href');

			// Show/Hide Tabs
			jQuery('.tabs ' + currentAttrValue).show().siblings().hide();

			// Change/remove current tab to active
			jQuery(this).parent('li').addClass('active').siblings().removeClass('active');

			e.preventDefault();
		});


		// Search icon.
		if( $( '.search-icon' ).length > 0 ) {
			$( '.search-icon' ).click( function( e ) {
				e.preventDefault();
				$( '.search-box-wrap' ).slideToggle();
			});
		}

		// Mobile menu.
		$( '#mobile-trigger' ).sidr({
			timing: 'ease-in-out',
			speed: 500,
			source: '#mob-menu',
			name: 'sidr-main'
		});

		// Implement go to top.
		var $scrollup_object = $( '#btn-scrollup' );
		if ( $scrollup_object.length > 0 ) {
			$( window ).scroll( function() {
				if ( $( this ).scrollTop() > 100 ) {
					$scrollup_object.fadeIn();
				} else {
					$scrollup_object.fadeOut();
				}
			});

			$scrollup_object.click( function() {
				$( 'html, body' ).animate( { scrollTop: 0 }, 600 );
				return false;
			});
		}

		// Breaking news ticker.
		$( '.breaking-news-list' ).slick({
			slidesToShow: 1,
			slidesToScroll: 1,
			draggable: false,
			autoplay: true,
			autoplaySpeed: 2000,
			arrows: false
		});

	});

} )( jQuery );

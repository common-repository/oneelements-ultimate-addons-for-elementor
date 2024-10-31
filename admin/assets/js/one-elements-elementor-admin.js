(function( $ ) {
	
	'use strict';

	/**
	 * 
	 * Add necessary classes to each One Elements for styling purpose
	 * 
	 */

	function add_classes_to_panel_elements( $elements ) {

		// stop if already class added
		if ( $elements.first().hasClass('one-elements__elementor-element') ) return;

		// add the classes to each One elements
		$elements.each(function() {
			
			$(this).closest('.elementor-element').addClass('one-elements__elementor-element');

		});

	}

	/**
	 * 
	 * Move One Elements category to top
	 * 
	 */

	function move_one_elements_category_to_top() {

		var oneElementCats = jQuery('#elementor-panel-category-one_elements, #elementor-panel-category-one_elements_single, #elementor-panel-category-one_elements_archive');

		oneElementCats.parent().prepend( oneElementCats );

	}


	$(window).on('load',function() {

		// Add classes when Initialized
		add_classes_to_panel_elements( $('.one-elements-widget-eicon') );

		move_one_elements_category_to_top();

		// Add classes when Search Changes
		elementor.channels.panelElements.on('filter:change', function() {

			var handler = setTimeout(function() {

				clearTimeout(handler);

				add_classes_to_panel_elements( $('.one-elements-widget-eicon') );

				move_one_elements_category_to_top();

			}, 5 );

		});

		// Add classes when view changes
		$('#elementor-panel').on('click', function() {

			add_classes_to_panel_elements( $('.one-elements-widget-eicon') );

			move_one_elements_category_to_top();

		});

		elementor.getPanelView().on('set:page', function() {

			add_classes_to_panel_elements( $('.one-elements-widget-eicon') );

			move_one_elements_category_to_top();

		});

		$( elementor.getPreviewView().$el ).on( 'click', '.elementor-empty-view, .elementor-element-empty', function() {

			add_classes_to_panel_elements( $('.one-elements-widget-eicon') );

			move_one_elements_category_to_top();

		});


		var carouselElements = ['one-elements-practice_area', 'one-elements-case-study', 'one-elements-logos', 'one-elements-testimonials', 'one-elements-team', 'one-elements-posts'];
		
		var carouselSettings = ['item_per_slide', 'item_per_slide_tablet', 'item_per_slide_mobile', 'slides_to_scroll', 'slides_to_scroll_tablet', 'slides_to_scroll_mobile', 'slides_visibility', 'slide_speed', 'autoplay', 'autoplay_speed', 'infinite_loop', 'pause_on_hover', 'disable_carousel', 'nav_type', 'nav_type_tablet', 'nav_type_mobile', 'prev_icon', 'next_icon'];

		// Dynamic Carousel re initialize in elementor
		elementor.hooks.addAction('panel/open_editor/widget', function( panel, model, view ) {

			var elementIndex = carouselElements.indexOf(model.attributes.widgetType);

			if ( elementIndex < 0 ) return;

			var settingsPrefix = carouselElements[elementIndex].split('-').join('_') + '_c_';

			carouselSettings.forEach( function( setting ) {

				setting = settingsPrefix + setting;

				model.get( "settings" ).on( "change:" + setting, function() {
					view.allowRender = true;
					view.renderOnChange();
				});

			});

		});


	});

})( jQuery );

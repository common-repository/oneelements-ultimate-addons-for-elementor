var isAdminBar      = false,
    isEditMode      = false,
    isInitial       = true;

(function($) {

    var getChildSection = function( model ) {

        var models = [];

        if ( model.attributes.elType == 'section' && model.attributes.isInner ) return [model];

        if ( ['section', 'column'].indexOf( model.attributes.elType ) !== -1 ) {

            models = getChildSections( model.attributes.elements.models );

        }

        return models;

    }

    var getChildSections = function( models ) {

        if ( ! models ) return [];

        var childSections = [];

        $.each( models, function( index, model ) {

            if ( ['section', 'column'].indexOf( model.attributes.elType ) !== -1 ) {
                childSections = childSections.concat( getChildSection( model ) );
            }

        });

        return childSections;

    }

    var getSections = function( includeChild ) {

        if ( ! window.elementor.elements.models ) return [];

        var sections = window.elementor.elements.models;

        includeChild = includeChild || false;

        if ( ! includeChild ) return sections;

        var childSections = getChildSections( sections );

        return sections.concat( childSections );

    }

    var getSectionParallaxSettings = function( sectionId ) {

        if ( ! sectionId ) {
            return false;
        }

        var editorElements      = null,
            sectionsData        = {},
            sectionData         = {},
            sectionParallaxData = {},
            settings            = [];

        if ( ! window.elementor || ! window.elementor.hasOwnProperty( 'elements' ) ) {
            return false;
        }

        editorElements = getSections( true );

        if ( ! editorElements ) {
            return false;
        }

        $.each( editorElements, function( index, obj ) {
            if ( sectionId == obj.id ) {
                sectionData = obj.attributes.settings.attributes;
            }
        });

        if ( ! sectionData.hasOwnProperty( 'one_elements_parallax_layout_list' ) || 0 === Object.keys( sectionData ).length ) {
            return false;
        }

        sectionParallaxData = sectionData[ 'one_elements_parallax_layout_list' ].models;

        $.each( sectionParallaxData, function( index, obj ) {
            settings.push( obj.attributes );
        });

        if ( 0 !== settings.length ) {
            return settings;
        }

        return false;

    };

    var SectionParallaxInit = function( $scope, $ ) {

        if ( $scope.data('element_type') !== 'section' ) return;

        let settings = getSectionParallaxSettings( $scope.data('id') );

        if ( ! settings ) return;

        let oneParallaxSections = {};

        oneParallaxSections[ $scope.data('id') ] = settings;

        var eventData = [{
            element: $scope,
            oneParallaxSections: oneParallaxSections
        }];

        $(document).trigger( 'Section:Parallax:Update', eventData );
        $(document).trigger( 'Layout:Update', eventData );

    };

    var WidgetCounter = function( $scope, $ ) {

        if ( $scope.data('widget_type') !== 'one-elements-counter.default' ) return;

        elementorFrontend.waypoint($scope.find('.one-elements-counter__number'), function () {
            var $number = $(this),
                data = $number.data();

            var decimalDigits = data.toValue.toString().match(/\.(.*)/);

            if (decimalDigits) {
                data.rounding = decimalDigits[1].length;
            }

            $number.numerator(data);
            
        });

    };


    var CarouselInit = function( $scope, $ ) {

        if ( isInitial ) return;

        var $carousel = $scope.find('.one-elements__carousel .one-elements__carousel-inner');

        if ( ! $carousel.length ) return;

        var eventData = [{
            element: $carousel
        }];

        $(document).trigger( 'Carousel:Update', eventData );
        $(document).trigger( 'Layout:Update', eventData );

    };


    var FilterInit = function( $scope, $ ) {

        if ( isInitial ) return;

        var $filter = $scope.find('.one-elements__filter');

        if ( ! $filter.length ) return;

        var eventData = [{
            element: $filter
        }];

        $(document).trigger( 'Filter:Update', eventData );
        $(document).trigger( 'Layout:Update', eventData );

    };


    var LoadMoreInit = function( $scope, $ ) {

        if ( isInitial ) return;

        var $loadMore = $scope.find('.one-elements-button__load_more');

        if ( ! $loadMore.length ) return;

        var eventData = [{
            element: $loadMore
        }];

        $(document).trigger( 'LoadMore:Update', eventData );
        $(document).trigger( 'Layout:Update', eventData );

    };


    var ModernCardsInit = function( $scope, $ ) {

        if ( isInitial ) return;

        var $modernCards = $scope.find('.card_style--modern-card');

        if ( ! $modernCards.length ) return;

        var eventData = [{
            element: $modernCards
        }];

        $(document).trigger( 'ModernCard:Update', eventData );
        $(document).trigger( 'Layout:Update', eventData );

    };


    var AccordionFix = function( $scope, $ ) {

        if ( isInitial ) return;

        var $accordion = $scope.find('.one-elements-accordion');

        if ( ! $accordion.length ) return;

        var eventData = [{
            element: $accordion
        }];

        $(document).trigger( 'Accordion:Update', eventData );
        $(document).trigger( 'Layout:Update', eventData );

    };

    var addPageCustomCss = function() {

        var customCSS = elementor.settings.page.model.get('custom_css');

        if ( customCSS ) {
            customCSS = customCSS.replace(/selector/g, elementor.config.settings.page.cssWrapperSelector);
            elementor.settings.page.getControlsCSS().elements.$stylesheetElement.append(customCSS);
        }

    };

    var addCustomCss = function( css, context ) {

        if ( context && context.model ) {
    
            customCSS = context.model.get('settings').get('custom_css');
    
            if ( customCSS ) {
                css += customCSS.replace(/selector/g, '.elementor-element.elementor-element-' + context.model.id);
            }

        }

        return css;

    };

    var onElementorInit = function() {

        elementor.hooks.addFilter('editor/style/styleText', addCustomCss);

        elementor.settings.page.model.on('change', addPageCustomCss);

        elementor.on('navigator:init', onNavigatorInit);

    };

    var onNavigatorInit = function() {

        elementor.navigator.indicators.customCSS = {
            icon: 'code-bold',
            settingKeys: ['custom_css'],
            title: 'custom_css',
            section: 'section_custom_css'
        };

    };

    var onElementorPreviewLoaded = function() {
        addPageCustomCss();
    };

    $(window).on( 'elementor/frontend/init', function() {

        if ( elementorFrontend.isEditMode() ) {
            isEditMode = true;
        }

        if ( $('body').is('.admin-bar') ) {
            isAdminBar = true;
        }

        // Counter Handler
        elementorFrontend.hooks.addAction( 'frontend/element_ready/widget', WidgetCounter );

        // Carousel Handler
        elementorFrontend.hooks.addAction( 'frontend/element_ready/widget', CarouselInit );

        // Filter Handler
        elementorFrontend.hooks.addAction( 'frontend/element_ready/widget', FilterInit );

        // Load More Handler
        elementorFrontend.hooks.addAction( 'frontend/element_ready/widget', LoadMoreInit );

        // Modern Cards
        elementorFrontend.hooks.addAction( 'frontend/element_ready/widget', ModernCardsInit );

        // Sections Parallax
        elementorFrontend.hooks.addAction( 'frontend/element_ready/global', SectionParallaxInit );

        // Accordion
        elementorFrontend.hooks.addAction( 'frontend/element_ready/global', AccordionFix );

        elementorFrontend.on('components:init', function() {
            var handler = setTimeout(function() {
                isInitial = false;
                clearTimeout( handler );
            }, 1500 );
        });

        // Custom CSS
        if ( 'elementor' in window ) {
            onElementorInit();
            elementor.on('preview:loaded', onElementorInit);
        }

    });
    

})(jQuery);

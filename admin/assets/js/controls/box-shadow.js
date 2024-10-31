jQuery( window ).on( 'elementor:init', function() {

    const BoxShadowControlView = elementor.modules.controls.Box_shadow.extend({
        initSliders: function initSliders() {
            var _this = this;
            var value = this.getControlValue();
            // fix invalid number in slider
            if (_.isEmpty(value)) {
                value = {
                    horizontal : 0,
                    vertical : 0,
                    blur : 0,
                    spread : 0
                }
            }

            this.ui.sliders.each(function (index, slider) {
                var $input = jQuery(slider).next('.elementor-slider-input').find('input');
                var sliderInstance = noUiSlider.create(slider, {
                    start: [value[slider.dataset.input]], //slider.dataset.input = horizontal, vertical, blur spread etc.
                    step: 1,
                    range: {
                        min: +$input.attr('min'),
                        max: +$input.attr('max')
                    },
                    format: {
                        to: function to(sliderValue) {
                            return +sliderValue.toFixed(1);
                        },
                        from: function from(sliderValue) {
                            return +sliderValue;
                        }
                    }
                });
                sliderInstance.on('slide', function (values) {
                    var type = sliderInstance.target.dataset.input;
                    $input.val(values[0]);

                    _this.setValue(type, values[0]);
                });
            });
        }
    });
    
    elementor.addControlView( 'oee_box_shadow', BoxShadowControlView );
});


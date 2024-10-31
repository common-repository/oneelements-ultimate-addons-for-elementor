;
jQuery(window).on('elementor:init', function() {
    'use strict';
    const controlView = elementor.modules.controls.BaseData.extend({

        onReady: function() {
            let e = this,
                uiSelect2 = e.ui.select,
                data_ajax_url =uiSelect2.attr('data-ajax-url');

            uiSelect2.select2({
                ajax: {
                    url: data_ajax_url,
                    dataType: 'json',
                    data: function(param) {
                        return {
                            q: param.term,
                        }
                    }
                },
                cache: true
            });


            let controlVal = (typeof e.getControlValue() !== 'undefined') ? e.getControlValue() : '';
            if ( Array.isArray(controlVal) ) {
                controlVal = e.getControlValue().join()
            }


            jQuery.ajax({
                url: data_ajax_url,
                dataType: 'json',
                beforeSend: function before() {
                    e.addControlSpinner();
                },
                data: {
                    ids: String(controlVal)
                }
            }).then(function(data, textStatus, jqXHR ) {
                e.removeControlSpinner();
                if (data !== null && data.results.length > 0) {
                    jQuery.each(data.results, function(index, value) {
                        let newVal = new Option(value.text, value.id, true, true);
                        uiSelect2.append(newVal).trigger('change')
                    });

                    uiSelect2.trigger({
                        type: 'select2:select',
                        params: {
                            data: data
                        }
                    })
                }
            })
        },
        addControlSpinner: function addControlSpinner() {
            this.ui.select.prop('disabled', true);
            this.$el.find('.elementor-control-title').after('<span class="elementor-control-spinner">&nbsp;<i class="eicon-spinner eicon-animation-spin"></i>&nbsp;</span>');
        },

        removeControlSpinner: function removeControlSpinner() {
            this.$el.find(':input').attr('disabled', false);
            this.$el.find('.elementor-control-spinner').remove();
        },
        onBeforeDestroy: function() {
            if (this.ui.select.data('select2')) {
                this.ui.select.select2('destroy')
            }
            this.el.remove()
        }
    });

    elementor.addControlView('one-elements-select2', controlView)
});
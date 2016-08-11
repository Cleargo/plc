define([
    'jquery',
    'jquery/ui'
], function ($) {
    $.widget("mana.hideShowElementCheckbox", {
        options: {
            affectedElements: [],
            afterChange: false
        },

        _create: function() {
            $(this.element)
                .on('change', $.proxy(this.showOrHide, this));
            this.showOrHide();
        },
        showOrHide: function () {
            var arr = this.options.affectedElements;
            for(var i = 0; i < arr.length; i++ ) {
                var affectedElement = $(arr[i]);
                if ($(this.element.context).val() == "0") {
                    affectedElement.hide();
                } else {
                    affectedElement.show();
                }
            }
            if(typeof this.options.afterChange == "function") {
                this.options.afterChange.apply();
            }
        }
    });

    return $.mana.hideShowElementCheckbox;
});
define(
    [

        'Magento_Checkout/js/view/summary/abstract-total',
        'Magento_Checkout/js/model/totals',
        'ko'
    ],
    function (Component,totals) {
        "use strict";

        return Component.extend({
            defaults: {
                template: 'Mageplaza_GiftCard/checkout/summary/customdiscount'
            },
            isDisplayedCustomdiscount : function(){
                var total = totals.getSegment('customdiscounttotal').value;
                return total >0;
            },
            getCustomDiscount : function(){
                var discount = -totals.getSegment('customdiscounttotal').value;
                return this.getFormattedPrice(discount) ;
            },
            getTitleDiscount : function(){
                var title = totals.getSegment('customdiscounttotal').title;
                return title;
            }
            // ,
            // testKo
        });
    }
);
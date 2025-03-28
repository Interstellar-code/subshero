// Instantiate this class
$(document).ready(function () {
    app.register('settings.billing', new clsApp_Settings_Billing);
});


class clsApp_Settings_Billing {
    e = {
        // Elements

        coupon: {
            form: '#frm_settings_billing_coupon_apply',
            modal: '#modal_settings_billing_coupon_apply',
        },
    };
    d = {
        // Default values

    };
    o = {
        // Objects

    };
    c = {
        // Configuration

    };

    constructor() {
    }

    init() {
    }

    coupon_apply(ctl) {
        app.global.create({
            form: app.settings.billing.e.coupon.form,
            slug: 'settings/billing/coupon/apply',
            btn: ctl,
            success: function (response) {
                if (response.status) {
                    app.alert.succ(response.message);
                    $(app.settings.billing.e.coupon.modal).modal('hide');
                    $(app.settings.billing.e.coupon.form)[0].reset();
                    app.page.switch('settings/billing');
                } else {
                    app.alert.validation(response.message);
                }
            },
        });
    }
}

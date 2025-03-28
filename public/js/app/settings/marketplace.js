// Instantiate this class
$(document).ready(function () {
    app.register('settings.marketplace', new clsApp_Settings_Marketplace);
});


class clsApp_Settings_Marketplace {
    e = {
        // Elements

        edit: {
            form: '#settings_marketplace_update_form',
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
    f = {
        // Callback function

    };
    t = {
        // Template

    };

    constructor() {
    }

    init() {
    }

    update(ctl) {
        app.global.create({
            form: app.settings.marketplace.e.edit.form,
            slug: 'settings/marketplace/update',
            btn: ctl,
            success: function (response) {
                if (response.status) {
                    app.alert.succ(response.message);
                    $(app.settings.marketplace.e.edit.form)[0].reset();
                    app.page.switch('settings/marketplace');
                } else {
                    app.alert.validation(response.message);
                }
            },
        });
    }
}

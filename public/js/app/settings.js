
$(document).ready(function () {
    app.register('settings', new clsApp_Settings);
});



class clsApp_Settings {
    e = {
        // Elements

    };
    d = {
        // Default values

    };
    c = {
        // Configuration

    };

    constructor() {
        lib.def('preference', new clsApp_Settings_Preference(), false, this);
        lib.def('profile', new clsApp_Settings_Profile(), false, this);
        lib.def('contact', new clsApp_Settings_Contact(), false, this);
        lib.def('tag', new clsApp_Settings_Tag(), false, this);
    }

    init() {
    }

    delete(ctl) {
        let id = lib.get_id(ctl);
        if (!id) {
            return false;
        }

        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                let form_data = new FormData();
                form_data.append('_token', app.config.token);
                form_data.append('id', id);

                $.ajax({
                    url: app.url + 'subscription/delete/' + id,
                    type: 'POST',
                    data: form_data,
                    dataType: 'json',
                    beforeSend: function (xhr) {
                        app.loading.btn(ctl);
                    },
                    success: function (response) {
                        if (response) {
                            app.page.switch('subscription');
                            app.alert.succ(response.message);
                        } else {
                            app.alert.warn(response.message);
                        }
                    },
                    error: function (response) {
                        app.alert.response(response);
                    },
                    complete: function () {
                        app.loading.btn(ctl);
                    },
                    processData: false,
                    contentType: false,
                });
            }
        });
    }

    refresh() {
        let form_data = new FormData();
        form_data.append('_token', app.config.token);

        $.ajax({
            url: app.url + 'settings/refresh',
            type: 'POST',
            data: form_data,
            beforeSend: function (xhr) {
                app.loading.block(app.settings.e.container);
            },
            success: function (response) {
                if (response) {
                    $(app.settings.e.container).html(response);
                }
            },
            error: function (response) {
                app.alert.response(response);
            },
            complete: function () {
                app.loading.unblock(app.settings.e.container, true);
            },
            processData: false,
            contentType: false,
        });
    }

}

class clsApp_Settings_Preference {
    e = {
        // Elements

        form: '#frm_settings_preference',
    };
    d = {
        // Default values

    };
    c = {
        // Configuration

    };

    constructor() {
    }

    init() {
    }

    update(ctl) {
        app.global.create({
            form: app.settings.preference.e.form,
            slug: 'settings/preference/update',
            btn: ctl,
            success: function (response) {
                if (response.status) {
                    app.alert.succ(response.message);
                } else {
                    app.alert.validation(response.message);
                }
            },
        });
    }
}


class clsApp_Settings_Profile {
    e = {
        // Elements

        info: {
            form: '#frm_settings_profile_info',
            first_name: '#settings_profile_first_name',
            last_name: '#settings_profile_last_name',
        },
        password: {
            form: '#frm_settings_profile_password',
        },
    };
    d = {
        // Default values

    };
    c = {
        // Configuration

    };

    constructor() {
    }

    init() {
    }

    update_info(ctl) {
        app.global.create({
            form: app.settings.profile.e.info.form,
            slug: 'settings/profile/update/info',
            btn: ctl,
            success: function (response) {
                if (response.status) {
                    $('.user_profile_name').text($(app.settings.profile.e.info.first_name).val() + ' ' + $(app.settings.profile.e.info.last_name).val());
                    app.alert.succ(response.message);
                } else {
                    app.alert.validation(response.message);
                }
            },
        });
    }

    update_password(ctl) {
        app.global.create({
            form: app.settings.profile.e.password.form,
            slug: 'settings/profile/update/password',
            btn: ctl,
            success: function (response) {
                if (response.status) {
                    $(app.settings.profile.e.password.form)[0].reset();
                    app.alert.succ(response.message);
                } else {
                    app.alert.validation(response.message);
                }
            },
        });
    }
}

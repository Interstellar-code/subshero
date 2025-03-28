// Instantiate this class
$(document).ready(function () {
    app.register('settings.alert', new clsApp_Settings_Alert);
});


class clsApp_Settings_Alert {
    e = {
        // Elements

        add: {
            form: '#frm_settings_alert_add',
            name: '#settings_alert_add_name',
            modal: '#modal_settings_alert_add',
            alert_subs_type: '#settings_alert_add_alert_subs_type',
            alert_condition: '#settings_alert_add_alert_condition',
        },
        edit: {
            form: '#frm_settings_alert_edit',
            id: '#settings_alert_edit_id',
            name: '#settings_alert_edit_name',
            modal: '#modal_settings_alert_edit',
            alert_subs_type: '#settings_alert_edit_alert_subs_type',
            alert_condition: '#settings_alert_edit_alert_condition',
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

    create(ctl) {
        app.global.create({
            form: app.settings.alert.e.add.form,
            slug: 'settings/alert/create',
            btn: ctl,
            success: function (response) {
                if (response.status) {
                    app.alert.succ(response.message);
                    $(app.settings.alert.e.add.modal).modal('hide');
                    $(app.settings.alert.e.add.form)[0].reset();
                    app.page.switch('settings/alert');
                    app.settings.alert.create_type_check(app.settings.alert.e.add.alert_subs_type);
                } else {
                    app.alert.validation(response.message);
                }
            },
        });
    }

    edit(ctl) {
        let id = lib.get_id(ctl);
        if (!id) {
            return false;
        }

        let form_data = new FormData();
        form_data.append('_token', app.config.token);
        form_data.append('id', id);

        $.ajax({
            url: app.url + 'settings/alert/edit/' + id,
            type: 'POST',
            data: form_data,
            beforeSend: function (xhr) {
                app.loading.btn(ctl);
            },
            success: function (response) {
                if (response) {
                    $(app.settings.alert.e.edit.modal).find('.modal-content').html(response);
                    $(app.settings.alert.e.edit.modal).modal();
                    app.load.tooltip();
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

    update(ctl) {
        app.global.create({
            form: app.settings.alert.e.edit.form,
            slug: 'settings/alert/update',
            btn: ctl,
            success: function (response) {
                if (response.status) {
                    app.alert.succ(response.message);
                    $(app.settings.alert.e.edit.modal).modal('hide');
                    $(app.settings.alert.e.edit.form)[0].reset();
                    app.page.switch('settings/alert');
                    app.settings.alert.update_type_check(app.settings.alert.e.edit.alert_subs_type);
                } else {
                    app.alert.validation(response.message);
                }
            },
        });
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
                    url: app.url + 'settings/alert/delete/' + id,
                    type: 'POST',
                    data: form_data,
                    dataType: 'json',
                    beforeSend: function (xhr) {
                        app.loading.btn(ctl);
                    },
                    success: function (response) {
                        if (response.status) {
                            app.page.switch('settings/alert');
                            app.alert.succ(response.message);
                            app.load.tooltip();
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

    create_type_check(el) {
        let type_el = $(el);
        let type = type_el.find(':selected').val();

        // Check if this is lifetime
        if (type == 3) {
            $(app.settings.alert.e.add.alert_condition).find('option[value=1]').hide();
            $(app.settings.alert.e.add.alert_condition).find('option[value=2]').hide();
            $(app.settings.alert.e.add.alert_condition).val('');
        }

        // For other types
        else {
            $(app.settings.alert.e.add.alert_condition).find('option[value=1]').show();
            $(app.settings.alert.e.add.alert_condition).find('option[value=2]').show();
        }
    }

    update_type_check(el) {
        let type_el = $(el);
        let type = type_el.find(':selected').val();

        // Check if this is lifetime
        if (type == 3) {
            $(app.settings.alert.e.edit.alert_condition).find('option[value=1]').hide();
            $(app.settings.alert.e.edit.alert_condition).find('option[value=2]').hide();
            $(app.settings.alert.e.edit.alert_condition).val('');
        }

        // For other types
        else {
            $(app.settings.alert.e.edit.alert_condition).find('option[value=1]').show();
            $(app.settings.alert.e.edit.alert_condition).find('option[value=2]').show();
        }
    }
}

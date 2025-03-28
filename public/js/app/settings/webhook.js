// Instantiate this class
$(document).ready(function () {
    app.register('settings.webhook', new clsApp_Settings_Webhook);
});


class clsApp_Settings_Webhook {
    e = {
        // Elements

        add: {
            form: '#frm_settings_webhook_add',
            token: '#frm_settings_webhook_add [name=token]',
            name: '#settings_webhook_add_name',
            endpoint_url: '#settings_webhook_add_endpoint_url',
            endpoint_url_generate: '#settings_webhook_add_endpoint_url_generate',
            endpoint_url_copy: '#settings_webhook_add_endpoint_url_copy',
            events: '#settings_webhook_add_events',
            modal: '#modal_settings_webhook_add',
        },
        edit: {
            form: '#frm_settings_webhook_edit',
            id: '#settings_webhook_edit_id',
            token: '#frm_settings_webhook_edit [name=token]',
            name: '#settings_webhook_edit_name',
            events: '#settings_webhook_edit_events',
            endpoint_url: '#settings_webhook_edit_endpoint_url',
            endpoint_url_generate: '#settings_webhook_edit_endpoint_url_generate',
            endpoint_url_copy: '#settings_webhook_edit_endpoint_url_copy',
            modal: '#modal_settings_webhook_edit',
            modal_body: '#modal_settings_webhook_edit .modal-content',
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
            form: app.settings.webhook.e.add.form,
            slug: 'settings/webhook/create',
            btn: ctl,
            success: function (response) {
                if (response.status) {
                    app.alert.succ(response.message);
                    $(app.settings.webhook.e.add.modal).modal('hide');
                    $(app.settings.webhook.e.add.form)[0].reset();
                    $(app.settings.webhook.e.add.events).val('').trigger('change');
                    app.page.switch('settings/api');
                } else {
                    app.alert.validation(response.message);
                }
            },
        });
    }

    edit(ctl, id) {
        let form_data = new FormData();
        form_data.append('_token', app.config.token);
        form_data.append('id', id);

        $.ajax({
            url: app.url + 'settings/webhook/edit/' + id,
            type: 'POST',
            data: form_data,
            beforeSend: function (xhr) {
                app.loading.btn(ctl);
            },
            success: function (response) {
                if (response) {
                    $(app.settings.webhook.e.edit.modal_body).html(response);
                    $(app.settings.webhook.e.edit.modal).modal();
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
            form: app.settings.webhook.e.edit.form,
            slug: 'settings/webhook/update',
            btn: ctl,
            success: function (response) {
                if (response.status) {
                    app.alert.succ(response.message);
                    $(app.settings.webhook.e.edit.modal).modal('hide');
                    $(app.settings.webhook.e.edit.form)[0].reset();
                    app.page.switch('settings/api');
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
            text: "Are you sure you want to delete this webhook?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                let form_data = new FormData();
                form_data.append('_token', app.config.token);
                form_data.append('id', id);

                $.ajax({
                    url: app.url + 'settings/webhook/delete/' + id,
                    type: 'POST',
                    data: form_data,
                    dataType: 'json',
                    beforeSend: function (xhr) {
                        app.loading.btn(ctl);
                    },
                    success: function (response) {
                        if (response.status) {
                            app.page.switch('settings/api');
                            app.alert.succ(response.message);
                            app.load.tooltip();
                        } else {
                            app.alert.validation(response.message);
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

        // Check if this is for Incoming Webhook
        if (type == 1) {
            $(app.settings.webhook.e.add.endpoint_url).attr('readonly', true);
            $(app.settings.webhook.e.add.endpoint_url_generate).show();
        }

        // Check if this is for Outgoing Webhook
        else if (type == 2) {
            $(app.settings.webhook.e.add.endpoint_url).attr('readonly', false);
            $(app.settings.webhook.e.add.endpoint_url_generate).hide();
        }
    }

    update_type_check(el) {
        let type_el = $(el);
        let type = type_el.find(':selected').val();

        // Check if this is for Incoming Webhook
        if (type == 1) {
            $(app.settings.webhook.e.edit.endpoint_url).attr('readonly', true);
            $(app.settings.webhook.e.edit.endpoint_url_generate).show();
        }

        // Check if this is for Outgoing Webhook
        else if (type == 2) {
            $(app.settings.webhook.e.edit.endpoint_url).attr('readonly', false);
            $(app.settings.webhook.e.edit.endpoint_url_generate).hide();
        }
    }

    create_generate(ctl) {
        let form_data = new FormData();
        form_data.append('_token', app.config.token);

        $.ajax({
            url: app.url + 'settings/webhook/generate',
            type: 'POST',
            data: form_data,
            dataType: 'json',
            beforeSend: function (xhr) {
                app.loading.btn(ctl);
            },
            success: function (response) {
                $(app.settings.webhook.e.add.token).val(response.token);
                $(app.settings.webhook.e.add.endpoint_url).val(response.endpoint_url);
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

    update_generate(ctl) {
        let form_data = new FormData();
        form_data.append('_token', app.config.token);

        $.ajax({
            url: app.url + 'settings/webhook/generate',
            type: 'POST',
            data: form_data,
            dataType: 'json',
            beforeSend: function (xhr) {
                app.loading.btn(ctl);
            },
            success: function (response) {
                $(app.settings.webhook.e.edit.token).val(response.token);
                $(app.settings.webhook.e.edit.endpoint_url).val(response.endpoint_url);
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

    create_copy_endpoint_url(el) {
        lib.do.copy($(app.settings.webhook.e.add.endpoint_url).val())
    }

    update_copy_endpoint_url(el) {
        lib.do.copy($(app.settings.webhook.e.edit.endpoint_url).val())
    }
}
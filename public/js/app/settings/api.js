// Instantiate this class
$(document).ready(function () {
    app.register('settings.api', new clsApp_Settings_Api);
});


class clsApp_Settings_Api {
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
        lib.def('token', new clsApp_Settings_Api_Token(), false, this);
    }

    init() {
    }
}


class clsApp_Settings_Api_Token {
    e = {
        // Elements

        add: {
            form: '#frm_settings_api_token_add',
            name: '#settings_api_token_add_name',
            email: '#settings_api_token_add_email',
            modal: '#modal_settings_api_token_add',
        },
        edit: {
            form: '#frm_settings_api_token_edit',
            id: '#settings_api_token_edit_id',
            name: '#settings_api_token_edit_name',
            email: '#settings_api_token_edit_email',
            modal: '#modal_settings_api_token_edit',
            modal_body: '#modal_settings_api_token_edit .modal-content',
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
            form: app.settings.api.token.e.add.form,
            slug: 'settings/api/token/create',
            btn: ctl,
            success: function (response) {
                if (response.status) {
                    app.alert.succ(response.message);
                    $(app.settings.api.token.e.add.modal).modal('hide');
                    $(app.settings.api.token.e.add.form)[0].reset();
                    app.page.switch('settings/api/token');
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
            url: app.url + 'settings/api/token/edit/' + id,
            type: 'POST',
            data: form_data,
            beforeSend: function (xhr) {
                app.loading.btn(ctl);
            },
            success: function (response) {
                if (response) {
                    $(app.settings.api.token.e.edit.modal_body).html(response);
                    $(app.settings.api.token.e.edit.modal).modal();
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
            form: app.settings.api.token.e.edit.form,
            slug: 'settings/api/token/update',
            btn: ctl,
            success: function (response) {
                if (response.status) {
                    app.alert.succ(response.message);
                    $(app.settings.api.token.e.edit.modal).modal('hide');
                    $(app.settings.api.token.e.edit.form)[0].reset();
                    app.page.switch('settings/api/token');
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
            text: "Are you sure you want to revoke this extension key?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                let form_data = new FormData();
                form_data.append('_token', app.config.token);
                form_data.append('id', id);

                $.ajax({
                    url: app.url + 'settings/api/token/delete/' + id,
                    type: 'POST',
                    data: form_data,
                    dataType: 'json',
                    beforeSend: function (xhr) {
                        app.loading.btn(ctl);
                    },
                    success: function (response) {
                        if (response.status) {
                            app.page.switch('settings/api/token');
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

    copy(json_text) {
        if (!json_text) {
            return false;
        }

        lib.do.copy(atob(json_text));
        // app.load.tooltip();
        return true;
    }
}
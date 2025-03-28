// Instantiate this class
$(document).ready(function () {
	app.register('settings.contact', new clsApp_Settings_Contact);
});


class clsApp_Settings_Contact {
    e = {
        // Elements

        add: {
            form: '#frm_settings_contact_add',
            name: '#settings_contact_add_name',
            email: '#settings_contact_add_email',
            modal: '#modal_settings_contact_add',
        },
        edit: {
            form: '#frm_settings_contact_edit',
            id: '#settings_contact_edit_id',
            name: '#settings_contact_edit_name',
            email: '#settings_contact_edit_email',
            modal: '#modal_settings_contact_edit',
            modal_body: '#modal_settings_contact_edit .modal-body',
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
            form: app.settings.contact.e.add.form,
            slug: 'settings/contact/create',
            btn: ctl,
            success: function (response) {
                if (response.status) {
                    app.alert.succ(response.message);
                    $(app.settings.contact.e.add.modal).modal('hide');
                    $(app.settings.contact.e.add.form)[0].reset();
                    app.page.switch('settings/contact');
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
            url: app.url + 'settings/contact/edit/' + id,
            type: 'POST',
            data: form_data,
            beforeSend: function (xhr) {
                app.loading.btn(ctl);
            },
            success: function (response) {
                if (response) {
                    $(app.settings.contact.e.edit.modal_body).html(response);
                    $(app.settings.contact.e.edit.modal).modal();
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
            form: app.settings.contact.e.edit.form,
            slug: 'settings/contact/update',
            btn: ctl,
            success: function (response) {
                if (response.status) {
                    app.alert.succ(response.message);
                    $(app.settings.contact.e.edit.modal).modal('hide');
                    $(app.settings.contact.e.edit.form)[0].reset();
                    app.page.switch('settings/contact');
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
                    url: app.url + 'settings/contact/delete/' + id,
                    type: 'POST',
                    data: form_data,
                    dataType: 'json',
                    beforeSend: function (xhr) {
                        app.loading.btn(ctl);
                    },
                    success: function (response) {
                        if (response) {
                            app.page.switch('settings/contact');
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
}
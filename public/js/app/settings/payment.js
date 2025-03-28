// Instantiate this class
$(document).ready(function () {
    app.register('settings.payment', new clsApp_Settings_Payment);
});


class clsApp_Settings_Payment {
    e = {
        // Elements

        add: {
            form: '#frm_settings_payment_add',
            modal: '#modal_settings_payment_add',
        },
        edit: {
            form: '#frm_settings_payment_edit',
            modal: '#modal_settings_payment_edit',
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
            form: app.settings.payment.e.add.form,
            slug: 'settings/payment/create',
            btn: ctl,
            success: function (response) {
                if (response.status) {
                    app.alert.succ(response.message);
                    $(app.settings.payment.e.add.form)[0].reset();
                    app.page.switch('settings/payment');
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
            url: app.url + 'settings/payment/edit/' + id,
            type: 'POST',
            data: form_data,
            beforeSend: function (xhr) {
                app.loading.btn(ctl);
            },
            success: function (response) {
                if (response) {
                    $(app.settings.payment.e.edit.modal).find('.modal-body').html(response);
                    $(app.settings.payment.e.edit.modal).modal();
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

    update(ctl, id) {
        app.global.create({
            form: app.settings.payment.e.edit.form,
            slug: 'settings/payment/update/' + id,
            btn: ctl,
            success: function (response) {
                if (response.status) {
                    app.alert.succ(response.message);
                    $(app.settings.payment.e.edit.form)[0].reset();
                    app.page.switch('settings/payment');
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
                    url: app.url + 'settings/payment/delete/' + id,
                    type: 'POST',
                    data: form_data,
                    dataType: 'json',
                    beforeSend: function (xhr) {
                        app.loading.btn(ctl);
                    },
                    success: function (response) {
                        if (response.status) {
                            app.page.switch('settings/payment');
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
}


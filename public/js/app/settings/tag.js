// Instantiate this class
$(document).ready(function () {
    app.register('settings.tag', new clsApp_Settings_Tag);
});


class clsApp_Settings_Tag {
    e = {
        // Elements

        add: {
            form: '#frm_settings_tag_add',
            name: '#settings_tag_add_name',
            modal: '#modal_settings_tag_add',
        },
        edit: {
            form: '#frm_settings_tag_edit',
            id: '#settings_tag_edit_id',
            name: '#settings_tag_edit_name',
            modal: '#modal_settings_tag_edit',
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
            form: app.settings.tag.e.add.form,
            slug: 'settings/tag/create',
            btn: ctl,
            success: function (response) {
                if (response.status) {
                    app.alert.succ(response.message);
                    $(app.settings.tag.e.add.modal).modal('hide');
                    $(app.settings.tag.e.add.form)[0].reset();
                    app.page.switch('settings/tag');
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
            url: app.url + 'settings/tag/get/' + id,
            type: 'POST',
            data: form_data,
            dataType: 'json',
            beforeSend: function (xhr) {
                app.loading.btn(ctl);
            },
            success: function (response) {
                if (response) {
                    $(app.settings.tag.e.edit.id).val(response.id);
                    $(app.settings.tag.e.edit.name).val(response.name);
                    $(app.settings.tag.e.edit.modal).modal();
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
            form: app.settings.tag.e.edit.form,
            slug: 'settings/tag/update',
            btn: ctl,
            success: function (response) {
                if (response.status) {
                    app.alert.succ(response.message);
                    $(app.settings.tag.e.edit.modal).modal('hide');
                    $(app.settings.tag.e.edit.form)[0].reset();
                    app.page.switch('settings/tag');
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
                    url: app.url + 'settings/tag/delete/' + id,
                    type: 'POST',
                    data: form_data,
                    dataType: 'json',
                    beforeSend: function (xhr) {
                        app.loading.btn(ctl);
                    },
                    success: function (response) {
                        if (response.status) {
                            app.page.switch('settings/tag');
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

// Instantiate this class
$(document).ready(function () {
	app.register('settings.team', new clsApp_Settings_Team);
});


class clsApp_Settings_Team {
    e = {
        // Elements

        add: {
            form: '#frm_settings_team_link',
            name: '#settings_team_link_name',
            email: '#settings_team_link_email',
            modal: '#modal_settings_team_link',
        },
        edit: {
            form: '#frm_settings_team_edit',
            id: '#settings_team_edit_id',
            name: '#settings_team_edit_name',
            email: '#settings_team_edit_email',
            modal: '#modal_settings_team_edit',
            modal_body: '#modal_settings_team_edit .modal-body',
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

    link(ctl) {
        app.global.create({
            form: app.settings.team.e.add.form,
            slug: 'settings/team/link',
            btn: ctl,
            success: function (response) {
                if (response.status) {
                    app.alert.succ(response.message);
                    $(app.settings.team.e.add.modal).modal('hide');
                    $(app.settings.team.e.add.form)[0].reset();
                    app.page.switch('settings/team');
                } else {
                    app.alert.validation(response.message);
                }
            },
        });
    }

    unlink(ctl) {
        let id = lib.get_id(ctl);
        if (!id) {
            return false;
        }

        swal({
            title: "Are you sure?",
            text: "Do you want to unlink this user?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                let form_data = new FormData();
                form_data.append('_token', app.config.token);
                form_data.append('id', id);

                $.ajax({
                    url: app.url + 'settings/team/unlink/' + id,
                    type: 'POST',
                    data: form_data,
                    dataType: 'json',
                    beforeSend: function (xhr) {
                        app.loading.btn(ctl);
                    },
                    success: function (response) {
                        if (response) {
                            app.page.switch('settings/team');
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
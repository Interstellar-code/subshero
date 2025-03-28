// Instantiate this class
$(document).ready(function () {
    app.register('calendar', new clsApp_Calendar);
});


class clsApp_Calendar {
    e = {
        // Elements
        notification: {
            edit: {
                modal: '#modal_notification_edit',
                form: '#frm_notification_edit',
                modal_body: '#modal_notification_edit .modal-body',
            }
        }

    };
    d = {
        // Default values

    };
    o = {
        // Objects

        instance: null,
        filter: {
            folder_id: null,
        },
    };
    c = {
        // Configuration

    };

    constructor() {
    }

    init() {
    }

    notification_edit(ctl) {
        const id = ctl.id;
        const type = ctl.type;
        if (!id) {
            return false;
        }

        let form_data = new FormData();
        form_data.append('_token', app.config.token);
        form_data.append('id', id);
        $.ajax({
            url: `${app.url}notification/edit/${type}/${id}`,
            type: 'POST',
            data: form_data,
            success: (response) => {
                if (response) {
                    $(this.e.notification.edit.modal_body).html(response);
                    $(this.e.notification.edit.modal).modal();
                    app.load.tooltip();
                }
            },
            error: (response) => {
                app.alert.response(response);
            },
            processData: false,
            contentType: false,
        });
    }

    notification_delete(ctl) {
        const id = ctl.id;
        const type = ctl.type;
        if (!id) {
            return false;
        }

        swal({
            title: 'Are you sure?',
            text: 'Once deleted, you will not be able to recover this!',
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                let form_data = new FormData();
                form_data.append('_token', app.config.token);
                form_data.append('id', id);

                $.ajax({
                    url: `${app.url}notification/delete/${type}/${id}`,
                    type: 'POST',
                    data: form_data,
                    dataType: 'json',
                    success: (response) => {
                        if (response) {
                            $(this.e.notification.edit.modal).modal('hide');
                            app.alert.succ(response.message);
                            app.page.switch('calendar');
                        } else {
                            app.alert.warn(response.message);
                        }
                    },
                    error: function (response) {
                        app.alert.response(response);
                    },
                    processData: false,
                    contentType: false,
                });
            }
        });
    }

}


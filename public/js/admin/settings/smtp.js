// Instantiate this class
$(document).ready(function () {
	app.register('settings.smtp', new clsApp_Settings_SMTP);
});


class clsApp_Settings_SMTP {
	e = {
		// Elements

		edit: {
			form: '#settings_smtp_update_form',
		},
		test: {
			form: '#settings_smtp_test_form',
		},
		log: {
			preview: {
				modal: '#settings_email_log_preview_modal',
				title: '#settings_email_log_preview_modal .modal-title',
				body: '#settings_email_log_preview_modal .modal-body',
			},
			user_table: '#tbl_settings_user_notifications',
		},
	};
	d = {
		// Default values

	};
	c = {
		// Configuration

	};
	o = {
		// Objects

		log: {
			user_table: null,
		},
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
			form: app.settings.smtp.e.edit.form,
			slug: 'admin/settings/email/smtp/update',
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

	test(ctl) {
		app.global.create({
			form: app.settings.smtp.e.test.form,
			slug: 'admin/settings/email/smtp/test',
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

	log_view(el) {
		el = $(el);
		if (!el.length || !app.settings.smtp.o.log.user_table) {
			return false;
		}

		// Reset selection
		$(app.settings.smtp.e.log.user_table).find('tr.selected').removeClass('selected');

		// Get selected item fom DataTables
		el.closest('tr').addClass('selected');

		let item = app.settings.smtp.o.log.user_table.row('.selected').data();
		if (!item) {
			return false;
		}

		// Reset selection
		el.closest('tr').removeClass('selected');

		// Render data
		let iframe = document.createElement('iframe');
		iframe.className = 'email_log_preview_iframe';
		$(app.settings.smtp.e.log.preview.body).html(iframe);
		iframe.contentWindow.document.open();
		iframe.contentWindow.document.write(lib.do.decode_html(item.body));
		iframe.contentWindow.document.close();
		$(app.settings.smtp.e.log.preview.title).text(item.subject);
		$(app.settings.smtp.e.log.preview.modal).modal();
	}

	delete_all_logs(ctl) {
		swal({
			title: "Are you sure?",
			text: "This action will delete all email logs except current month.",
			icon: "warning",
			buttons: true,
			dangerMode: true,
		}).then((willDelete) => {
			if (willDelete) {
				let form_data = new FormData();
				form_data.append('_token', app.config.token);

				$.ajax({
					url: app.url + 'admin/settings/email/logs/delete_all',
					type: 'POST',
					data: form_data,
					dataType: 'json',
					beforeSend: function (xhr) {
						app.loading.btn(ctl);
					},
					success: function (response) {
						if (response.status) {
							app.page.switch('admin/settings/email/smtp');
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
// Instantiate this class
$(document).ready(function () {
	app.register('settings.template', new clsApp_Settings_Template);
});


class clsApp_Settings_Template {
	e = {
		// Elements

		add: {
			form: '#settings_email_template_create_form',
			fields_container: '#settings_email_template_create_fields_container',
			subject: '#settings_email_template_create_subject',
			body: '#settings_email_template_create_body',
			preview: {
				modal: '#settings_email_template_create_preview_modal',
				title: '#settings_email_template_create_preview_modal .modal-title',
				body: '#settings_email_template_create_preview_modal .modal-body',
			},
		},

		edit: {
			form: '#settings_email_template_update_form',
			fields_container: '#settings_email_template_update_fields_container',
			subject: '#settings_email_template_update_subject',
			body: '#settings_email_template_update_body',
			preview: {
				modal: '#settings_email_template_update_preview_modal',
				title: '#settings_email_template_update_preview_modal .modal-title',
				body: '#settings_email_template_update_preview_modal .modal-body',
			},
		},
		test: {
			form: '#settings_template_test_form',
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

	create(ctl) {
		app.global.create({
			form: app.settings.template.e.add.form,
			slug: 'admin/settings/email/template/create',
			btn: ctl,
			success: function (response) {
				if (response.status) {
					app.alert.succ(response.message);
					app.page.switch('admin/settings/email/template');
				} else {
					app.alert.validation(response.message);
				}
			},
		});
	}

	update(ctl) {
		app.global.create({
			form: app.settings.template.e.edit.form,
			slug: 'admin/settings/email/template/update',
			btn: ctl,
			success: function (response) {
				if (response.status) {
					app.alert.succ(response.message);
					app.page.switch('admin/settings/email/template');
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
					url: app.url + 'admin/settings/email/template/delete/' + id,
					type: 'POST',
					data: form_data,
					dataType: 'json',
					beforeSend: function (xhr) {
						app.loading.btn(ctl);
					},
					success: function (response) {
						if (response) {
							app.page.switch('admin/settings/email/template');
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

	create_type_change(el) {
		let select_box = $(el);
		let template_name = select_box.find(':selected').val();

		if (select_box.length && template_name) {
			let fields_container = $(app.settings.template.e.add.fields_container);

			fields_container.children('[data-template_name!="' + template_name + '"]').hide();
			fields_container.children('[data-template_name="' + template_name + '"]').show();
		}
	}

	update_type_change(el) {
		let select_box = $(el);
		let template_name = select_box.find(':selected').val();

		if (select_box.length && template_name) {
			let fields_container = $(app.settings.template.e.edit.fields_container);

			fields_container.children('[data-template_name!="' + template_name + '"]').hide();
			fields_container.children('[data-template_name="' + template_name + '"]').show();
		}
	}

	create_preview(el) {
		let iframe = document.createElement('iframe');
		iframe.className = 'email_template_preview_iframe';
		$(app.settings.template.e.add.preview.body).html(iframe);
		iframe.contentWindow.document.open();
		iframe.contentWindow.document.write($(app.settings.template.e.add.body).val());
		iframe.contentWindow.document.close();
		$(app.settings.template.e.add.preview.title).text($(app.settings.template.e.add.subject).val());
		$(app.settings.template.e.add.preview.modal).modal();
	}

	update_preview(el) {
		let iframe = document.createElement('iframe');
		iframe.className = 'email_template_preview_iframe';
		$(app.settings.template.e.edit.preview.body).html(iframe);
		iframe.contentWindow.document.open();
		iframe.contentWindow.document.write($(app.settings.template.e.edit.body).val());
		iframe.contentWindow.document.close();
		$(app.settings.template.e.edit.preview.title).text($(app.settings.template.e.edit.subject).val());
		$(app.settings.template.e.edit.preview.modal).modal();
	}

	copied(el){
		$('.tooltip .tooltip-inner').text('Copied');
	}
}
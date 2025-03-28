// Instantiate this class
$(document).ready(function () {
	app.register('settings.webhook', new clsApp_Settings_Webhook);
});


class clsApp_Settings_Webhook {
	e = {
		// Elements

		edit: {
			form: '#settings_webhook_update_form',
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
		$('#settings_webhook_log_preview_modal').on('hide.bs.modal', function (event) {
			// $('#settings_webhook_log_preview_modal .modal-body pre').text('');
			$('#settings_webhook_log_preview_modal .modal-body').scrollTop(0);
		});
	}

	update(ctl) {
		app.global.create({
			form: app.settings.webhook.e.edit.form,
			slug: 'admin/settings/webhook/update',
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

	log_view(ctl) {
		let id = lib.get_id(ctl);
		if (!id) {
			return false;
		}

		let form_data = new FormData();
		form_data.append('_token', app.config.token);
		form_data.append('id', id);

		$.ajax({
			url: app.url + 'admin/settings/webhook/log/' + id,
			type: 'POST',
			data: form_data,
			dataType: 'json',
			beforeSend: function (xhr) {
				app.loading.btn(ctl);
			},
			success: function (response) {
				if (response) {
					$('#settings_webhook_log_preview_modal .modal-body pre').text(response.request);
					$('#settings_webhook_log_preview_modal').modal();
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
}
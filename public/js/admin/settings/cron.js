// Instantiate this class
$(document).ready(function () {
	app.register('settings.cron', new clsApp_Settings_Cron);
});


class clsApp_Settings_Cron {
	e = {
		// Elements

	};
	d = {
		// Default values

	};
	c = {
		// Configuration

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

	log_download(ctl) {
		let id = lib.get_id(ctl);
		if (!id) {
			return false;
		}

		let form_data = new FormData();
		form_data.append('_token', app.config.token);
		form_data.append('path', id);

		$.ajax({
			url: app.url + 'admin/settings/cron/log/download',
			type: 'POST',
			data: form_data,
			dataType: 'json',
			beforeSend: function (xhr) {
				app.loading.btn(ctl);
			},
			success: function (response) {
				if (response.status) {
					lib.do.download(response.filename, response.content);
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

	log_delete(ctl) {
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
				form_data.append('path', id);

				$.ajax({
					url: app.url + 'admin/settings/cron/log/delete',
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
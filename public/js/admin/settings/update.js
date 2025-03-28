// Instantiate this class
$(document).ready(function () {
	app.register('settings.update', new clsApp_Settings_Update);
});


class clsApp_Settings_Update {
	e = {
		// Elements

		add: {
			form: '#settings_email_template_create_form',
		},

		edit: {
			form: '#settings_email_template_update_form',
		},
		test: {
			form: '#settings_template_test_form',
		},
	};
	d = {
		// Default values

	};
	o = {
		// Objects

		command: 'version_check',
	};
	c = {
		// Configuration
		all_command: {},
	};

	constructor() {
		lib.def('timeline', new clsApp_Settings_Update_Timeline(), false, this);
	}

	init() {
	}

	handle(ctl) {
		let message = app.settings.update.c.all_command[app.settings.update.o.command].message.request;
		if (message) {
			app.settings.update.timeline.info(message);
		}

		let form_data = new FormData();
		form_data.append('_token', app.config.token);
		form_data.append('command', app.settings.update.o.command);

		$.ajax({
			url: app.url + 'admin/settings/update/handle',
			type: 'POST',
			data: form_data,
			dataType: 'json',
			beforeSend: function (xhr) {
				app.loading.btn(ctl);
			},
			success: function (response) {

				if (response) {
					app.settings.update.timeline.success(response.message);
				} else {
					app.settings.update.timeline.warning(response.message);
				}

				if (response.next_command) {
					app.settings.update.o.command = response.next_command;

					lib.sleep(500).then(function () {
						app.settings.update.handle();
					});
				}
			},
			error: function (response) {
				app.settings.update.timeline.response(response);
			},
			complete: function () {
				app.loading.btn(ctl);
			},
			processData: false,
			contentType: false,
		});
	}











	check(ctl) {
		app.settings.update.timeline.info('Checking for update');

		$.ajax({
			url: app.url + 'admin/settings/update/check',
			type: 'GET',
			dataType: 'json',
			beforeSend: function (xhr) {
				app.loading.btn(ctl);
			},
			success: function (response) {
				if (response) {
					// app.alert.succ(response.message);
					app.settings.update.timeline.info(response.message);
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
		});
	}

	download(ctl) {
		app.settings.update.timeline.info('Updating application');

		$.ajax({
			url: app.url + 'admin/settings/update/download',
			type: 'GET',
			dataType: 'json',
			beforeSend: function (xhr) {
				app.loading.btn(ctl);
			},
			success: function (response) {
				if (response) {
					// app.alert.succ(response.message);
					app.settings.update.timeline.info(response.message);
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
		});
	}
}

class clsApp_Settings_Update_Timeline {
	e = {
		// Elements

		container: '#settings_update_timeline_container',
		template: {
			success: '#tpl_settings_update_timeline_success',
			info: '#tpl_settings_update_timeline_info',
			warning: '#tpl_settings_update_timeline_warning',
			error: '#tpl_settings_update_timeline_error',
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

	success(title) {
		let html = $(app.settings.update.timeline.e.template.success).html()
			.replace('__TITLE__', title);
		$(app.settings.update.timeline.e.container).append(html);
	}

	info(title) {
		let html = $(app.settings.update.timeline.e.template.info).html()
			.replace('__TITLE__', title);
		$(app.settings.update.timeline.e.container).append(html);
	}

	warning(title) {
		let html = $(app.settings.update.timeline.e.template.warning).html()
			.replace('__TITLE__', title);
		$(app.settings.update.timeline.e.container).append(html);
	}

	error(title) {
		let html = $(app.settings.update.timeline.e.template.error).html()
			.replace('__TITLE__', title);
		$(app.settings.update.timeline.e.container).append(html);
	}

	clear() {
		$(app.settings.update.timeline.e.container).html('');
	}

	response(response) {
		if (response.message && typeof response.message === 'string') {
			app.settings.update.timeline.error(response.message);
		}
		else if (response.responseJSON && typeof response.responseJSON === 'object' && response.responseJSON.message && typeof response.responseJSON.message === 'string') {
			app.settings.update.timeline.error(response.responseJSON.message);
		}
		else if (response.statusText && typeof response.statusText === 'string') {
			app.settings.update.timeline.error(response.statusText);
		}
	}
}
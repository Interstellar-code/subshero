// Instantiate this class
$(document).ready(function () {
	app.register('settings.misc', new clsApp_Settings_Misc);
});


class clsApp_Settings_Misc {
	e = {
		// Elements

		recaptcha: {
			form: '#settings_misc_recaptcha_update_form',
		},
		cdn: {
			form: '#settings_misc_cdn_update_form',
		},
		xeno: {
			form: '#settings_misc_xeno_update_form',
		},
		cron: {
			form: '#settings_misc_cron_update_form',
		},
		gravitec: {
			form: '#settings_misc_gravitec_update_form',
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

	recaptcha_update(ctl) {
		app.global.create({
			form: app.settings.misc.e.edit.form,
			slug: 'admin/settings/misc/recaptcha/update',
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

	xeno_update(ctl) {
		app.global.create({
			form: app.settings.misc.e.xeno.form,
			slug: 'admin/settings/misc/xeno/update',
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

	cdn_update(ctl) {
		app.global.create({
			form: app.settings.misc.e.cdn.form,
			slug: 'admin/settings/misc/cdn/update',
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

	cron_update(ctl) {
		app.global.create({
			form: app.settings.misc.e.cron.form,
			slug: 'admin/settings/misc/cron/update',
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

	gravitec_update(ctl) {
		app.global.create({
			form: app.settings.misc.e.gravitec.form,
			slug: 'admin/settings/misc/gravitec/update',
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

	toggle_update(ctl, key) {
		swal({
			title: "Are you sure?",
			// text: "Once deleted, you will not be able to recover this!",
			icon: "warning",
			buttons: true,
			dangerMode: true,
		}).then((willDelete) => {
			if (willDelete) {
				var checkbox = $(ctl).find('input[type=checkbox]')
				if (!checkbox.length) {
					return false;
				}

				let form_data = new FormData();
				form_data.append('_token', app.config.token);
				form_data.append('key', key);
				form_data.append('value', checkbox.prop('checked') ? 0 : 1);

				$.ajax({
					url: app.url + 'admin/settings/misc/toggle/update',
					type: 'POST',
					data: form_data,
					dataType: 'json',
					beforeSend: function (xhr) {
						app.loading.btn(ctl);
					},
					success: function (response) {
						if (response.status) {
							app.alert.succ(response.message);

							// Toggle checkbox
							let container = $(ctl);
							var checkbox = container.find('input[type=checkbox]')
							if (!checkbox.length) {
								return false;
							}
							if (checkbox.get(0).checked) {
								checkbox.get(0).checked = false;
								container.addClass('off');
							} else {
								checkbox.get(0).checked = true;
								container.removeClass('off');
							}
							$(checkbox.get(0)).change();
						} else {
							app.alert.validation(response.message);
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
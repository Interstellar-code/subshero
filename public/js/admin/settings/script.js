// Instantiate this class
$(document).ready(function () {
	app.register('settings.script', new clsApp_Settings_Script);
});


class clsApp_Settings_Script {
	e = {
		// Elements

		edit: {
			form: '#settings_script_update_form',
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

	update(ctl) {
		app.global.create({
			form: app.settings.script.e.edit.form,
			slug: 'admin/settings/script/update',
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
}
// Instantiate this class
$(document).ready(function () {
	app.register('settings.recovery', new clsApp_Settings_Recovery);
	app.register('settings.recovery.backup', new clsApp_Settings_Recovery_Backup);
	app.register('settings.recovery.restore', new clsApp_Settings_Recovery_Restore);
	app.register('settings.recovery.reset', new clsApp_Settings_Recovery_Reset);
	app.register('settings.recovery.timeline', new clsApp_Settings_Recovery_Timeline);
});


class clsApp_Settings_Recovery {
}

class clsApp_Settings_Recovery_Backup {
	e = {
		// Elements

		button: '#settings_recovery_backup_btn',
		modal: '#modal_settings_recovery_backup',
		progress_bar: '#modal_settings_recovery_backup .modal-footer .progress-bar',
		progress_bar_container: '#modal_settings_recovery_backup .modal-footer .progress-bar-animated-alt',
		container: '#settings_recovery_backup_timeline_container',
	};
	d = {
		// Default values

		delay: 10,
		color: {
			info: '#ff6f00',
			success: '#1b5e20',
			warning: '#d92550',
			error: '#b71c1c',
			black: '#343a40',

			subscription: '#fdd835',
			folder: '#00bcd4',
			tag: '#4527a0',
			payment_method: '#ff5722',
			contact: '#2196f3',
			alert_profile: '#3e2723',
			api_token: 'yellow',
			webhook: 'green',
			// team_account: 'blue',
		},
	};
	o = {
		// Objects

		command: 'check_existence',
		last_command: '',
		last_message: '',
		event_id: '',
		delay: 10,
		working: false,
		percentage: 0,
		total: 50,
		done: 0,
	};
	c = {
		// Configuration

		token: '',
		commands: {},
	};

	constructor() {
	}

	init() {
	}

	start(ctl) {
		swal({
			title: lang('Are you sure?'),
			text: lang('This action will delete all your existing backup files and take a new backup.'),
			icon: 'warning',
			buttons: true,
			dangerMode: true,
		}).then((willDelete) => {
			if (willDelete) {

				// Change mode to backup
				app.settings.recovery.timeline.o.selected = app.settings.recovery.backup;

				app.settings.recovery.backup.o.working = true;
				window.onbeforeunload = function () {
					if (app.settings.recovery.backup.o.working) {
						return lang('Your account backup in progress. Do you want to stop now?');
					}
				};

				$(app.settings.recovery.backup.e.modal).modal({
					backdrop: 'static',
					keyboard: false,
				});

				lib.sleep(500).then(function () {
					app.settings.recovery.backup.handle();
				});
			}
		});
	}

	request(ctl) {

		if (app.settings.recovery.backup.o.command != app.settings.recovery.backup.o.last_command) {
			let message = app.settings.recovery.backup.c.commands[app.settings.recovery.backup.o.command].message.request;
			if (message && message != app.settings.recovery.backup.o.last_message) {
				app.settings.recovery.backup.o.last_message = message;
				app.settings.recovery.timeline.info(message);
			}
		}

		let form_data = new FormData();
		form_data.append('_token', app.config.token);
		form_data.append('token', app.settings.recovery.backup.c.token);
		form_data.append('command', app.settings.recovery.backup.o.command);
		form_data.append('event_id', app.settings.recovery.backup.o.event_id);

		$.ajax({
			url: app.url + 'settings/recovery/backup',
			type: 'POST',
			data: form_data,
			dataType: 'json',
			beforeSend: function (xhr) {
				app.loading.btn(app.settings.recovery.backup.e.button);
			},
			success: function (response) {
				app.settings.recovery.backup.handle(response);
			},
			error: function (response) {
				app.settings.recovery.timeline.response(response);
				app.settings.recovery.timeline.info(lang('Please click on the OK button to reload this page, then try again.'));
				app.settings.recovery.backup.enable_ui();
			},
			complete: function () {
				app.loading.btn(app.settings.recovery.backup.e.button);
			},
			processData: false,
			contentType: false,
		});
	}


	handle(response) {

		if (response) {

			// Check status
			if (response.status) {

				// if (response.message) {
				// 	app.settings.recovery.timeline.success(response.message);
				// }


				switch (response.command) {
					case 'delete_backup':
					case 'backup_subscription':
					case 'backup_folder':
					case 'backup_tag':
					case 'backup_payment_method':
					case 'backup_contact':
					case 'backup_alert_profile':
					case 'backup_api_token':
					case 'backup_webhook':
					case 'backup_settings':
					case 'backup_file_all':
						if (response.message) {
							app.settings.recovery.timeline.function(response.command, response.message);
						}
						app.settings.recovery.backup.progress();
						break;

					case 'check_existence':
						if (response.message) {
							app.settings.recovery.timeline.function(response.command, response.message);
						}
						// Set event data
						app.settings.recovery.backup.o.event_id = response.event_id;
						app.settings.recovery.backup.progress();
						break;

					case 'get_account_info':
						app.settings.recovery.backup.o.total = response.data.subscription_count +
							response.data.folder_count +
							response.data.tag_count +
							response.data.payment_method_count +
							response.data.contact_count +
							response.data.alert_profile_count +
							response.data.api_token_count +
							response.data.webhook_count;

						// response.data.team_account_count;

						// app.settings.recovery.backup.o.percentage = 0;
						// app.settings.recovery.backup.o.done = 0;

						// Check if empty
						if (app.settings.recovery.backup.o.total < 20) {
							// = Count + (6 * 2)
							app.settings.recovery.backup.o.total = 20;
						} else {
							app.settings.recovery.backup.o.total += 6;
						}

						if (response.message) {
							app.settings.recovery.timeline.success(response.message);
						}

						app.settings.recovery.timeline.function(response.command, lang('Total Subscriptions: ') + response.data.subscription_count);
						app.settings.recovery.timeline.function(response.command, lang('Total Folders: ') + response.data.folder_count);
						app.settings.recovery.timeline.function(response.command, lang('Total Tags: ') + response.data.tag_count);
						app.settings.recovery.timeline.function(response.command, lang('Total Payment Methods: ') + response.data.payment_method_count);
						app.settings.recovery.timeline.function(response.command, lang('Total Contacts: ') + response.data.contact_count);
						app.settings.recovery.timeline.function(response.command, lang('Total Alert Profiles: ') + response.data.alert_profile_count);
						app.settings.recovery.timeline.function(response.command, lang('Total API Tokens: ') + response.data.api_token_count);
						app.settings.recovery.timeline.function(response.command, lang('Total Webhooks: ') + response.data.webhook_count);
						// app.settings.recovery.timeline.function(response.command, lang('Total Team Accounts: ') + response.data.team_account_count);

						app.settings.recovery.backup.progress();
						break;

					case 'backup_success':
						app.settings.recovery.backup.progress(100);

						if (response.message) {
							app.settings.recovery.timeline.success(response.message);
						}

						// app.settings.recovery.timeline.info(lang('You will be now redirected to the login page.'));
						app.settings.recovery.timeline.info(lang('Please click on the OK button to proceed.'));

						lib.sleep(100).then(function () {
							app.settings.recovery.backup.enable_ui();
						});
						break;
				}

			} else {
				app.settings.recovery.timeline.warning(response.message);
				app.settings.recovery.timeline.info(lang('Please click on the OK button to reload this page, then try again.'));
				app.settings.recovery.backup.enable_ui();
			}

			// app.settings.recovery.backup.execute(response);


			// Check next command and execute it
			if (response.next_command) {

				app.settings.recovery.backup.o.command = response.next_command;
				app.settings.recovery.backup.o.last_command = response.last_command;

				lib.sleep(app.settings.recovery.backup.o.delay).then(function () {
					app.settings.recovery.backup.request();
				});
			}

		} else {

			app.settings.recovery.backup.request();
		}

	}

	progress(value = null) {
		app.settings.recovery.backup.o.done++;
		app.settings.recovery.backup.o.percentage = parseInt(app.settings.recovery.backup.o.done / app.settings.recovery.backup.o.total * 100);

		// Check extra values
		if (app.settings.recovery.backup.o.percentage > 99) {
			app.settings.recovery.backup.o.percentage = 99;
		}

		if (value) {
			app.settings.recovery.backup.o.percentage = value;
		}

		let progress_bar = $(app.settings.recovery.backup.e.progress_bar);

		if (progress_bar.length) {
			progress_bar.attr('aria-valuenow', app.settings.recovery.backup.o.percentage);
			progress_bar.css('width', app.settings.recovery.backup.o.percentage + '%');
			progress_bar.text(app.settings.recovery.backup.o.percentage + '%');
		}
	}

	execute(response) {

		// Check next command and execute it
		if (response.next_command) {

			lib.sleep(app.settings.recovery.backup.o.delay).then(function () {
				app.settings.recovery.backup.o.delay -= app.settings.recovery.backup.d.delay;
				app.settings.recovery.backup.handle();
			});

			app.settings.recovery.backup.o.delay += app.settings.recovery.backup.d.delay;
		}
	}

	enable_ui() {
		// Enable modal buttons
		app.settings.recovery.backup.o.working = false;
		$(app.settings.recovery.backup.e.modal).find('.modal-footer button').attr('disabled', false).show();
		$(app.settings.recovery.backup.e.modal).find('.modal-footer').show();
		$(app.settings.recovery.backup.e.progress_bar_container).hide();
	}

	finish(ctl) {
		$(ctl).attr('disabled', true);
		window.location.href = window.location.href;
	}

	delete(ctl) {
		swal({
			title: "Are you sure?",
			text: "Are you sure you want to delete this backup?",
			icon: "warning",
			buttons: true,
			dangerMode: true,
		}).then((willDelete) => {
			if (willDelete) {
				let form_data = new FormData();
				form_data.append('_token', app.config.token);

				$.ajax({
					url: app.url + 'settings/recovery/backup/delete',
					type: 'POST',
					data: form_data,
					dataType: 'json',
					beforeSend: function (xhr) {
						app.loading.btn(ctl);
					},
					success: function (response) {
						if (response.status) {
							app.page.switch('settings/recovery');
							app.alert.succ(response.message);
							app.load.tooltip();
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


class clsApp_Settings_Recovery_Restore {
	e = {
		// Elements

		button: '#settings_recovery_restore_btn',
		modal: '#modal_settings_recovery_restore',
		progress_bar: '#modal_settings_recovery_restore .modal-footer .progress-bar',
		progress_bar_container: '#modal_settings_recovery_restore .modal-footer .progress-bar-animated-alt',
		container: '#settings_recovery_restore_timeline_container',
	};
	d = {
		// Default values

		delay: 10,
		color: {
			info: '#ff6f00',
			success: '#1b5e20',
			warning: '#d92550',
			error: '#b71c1c',
			black: '#343a40',

			subscription: '#fdd835',
			folder: '#00bcd4',
			tag: '#4527a0',
			payment_method: '#ff5722',
			contact: '#2196f3',
			alert_profile: '#3e2723',
			api_token: 'yellow',
			webhook: 'green',
			// team_account: 'blue',
		},
	};
	o = {
		// Objects

		command: 'check_existence',
		last_command: '',
		last_message: '',
		event_id: '',
		delay: 10,
		working: false,
		percentage: 0,
		total: 50,
		done: 0,
	};
	c = {
		// Configuration

		token: '',
		commands: {},
	};

	constructor() {
	}

	init() {
	}

	start(ctl) {
		swal({
			title: lang('Are you sure?'),
			text: lang('This action will delete all data from your account and restore your account data from this backup.'),
			icon: 'warning',
			buttons: true,
			dangerMode: true,
		}).then((willDelete) => {
			if (willDelete) {

				// Change mode to restore
				app.settings.recovery.timeline.o.selected = app.settings.recovery.restore;

				app.settings.recovery.restore.o.working = true;
				window.onbeforeunload = function () {
					if (app.settings.recovery.restore.o.working) {
						return lang('Your account restore in progress. Do you want to stop now?');
					}
				};

				$(app.settings.recovery.restore.e.modal).modal({
					backdrop: 'static',
					keyboard: false,
				});

				lib.sleep(500).then(function () {
					app.settings.recovery.restore.handle();
				});
			}
		});
	}

	request(ctl) {
		if (app.settings.recovery.restore.o.command != app.settings.recovery.restore.o.last_command) {
			let message = app.settings.recovery.restore.c.commands[app.settings.recovery.restore.o.command].message.request;
			if (message && message != app.settings.recovery.restore.o.last_message) {
				app.settings.recovery.restore.o.last_message = message;
				app.settings.recovery.timeline.info(message);
			}
		}

		let form_data = new FormData();
		form_data.append('_token', app.config.token);
		form_data.append('command', app.settings.recovery.restore.o.command);
		form_data.append('token', app.settings.recovery.restore.c.token);
		form_data.append('event_id', app.settings.recovery.restore.o.event_id);

		$.ajax({
			url: app.url + 'settings/recovery/restore',
			type: 'POST',
			data: form_data,
			dataType: 'json',
			beforeSend: function (xhr) {
				app.loading.btn(app.settings.recovery.restore.e.button);
			},
			success: function (response) {
				app.settings.recovery.restore.handle(response);
			},
			error: function (response) {
				app.settings.recovery.timeline.response(response);
				app.settings.recovery.timeline.info(lang('Please click on the OK button to reload this page, then try again.'));
				app.settings.recovery.restore.enable_ui();
			},
			complete: function () {
				app.loading.btn(app.settings.recovery.restore.e.button);
			},
			processData: false,
			contentType: false,
		});
	}

	handle(response) {

		if (response) {

			// Check status
			if (response.status) {

				// if (response.message) {
				// 	app.settings.recovery.timeline.success(response.message);
				// }

				switch (response.command) {
					// case 'restore_validate':
					case 'delete_subscription':
					case 'delete_folder':
					case 'delete_tag':
					case 'delete_payment_method':
					case 'delete_contact':
					case 'delete_alert_profile':
					case 'delete_api_token':
					case 'delete_webhook':
					case 'restore_file_all':
					case 'restore_subscription':
					case 'restore_folder':
					case 'restore_tag':
					case 'restore_payment_method':
					case 'restore_contact':
					case 'restore_alert_profile':
					case 'restore_api_token':
					case 'restore_webhook':
					case 'restore_settings':
						if (response.message) {
							app.settings.recovery.timeline.function(response.command, response.message);
						}
						app.settings.recovery.restore.progress();
						break;

					case 'check_existence':
						if (response.message) {
							app.settings.recovery.timeline.function(response.command, response.message);
						}

						// Set event data
						app.settings.recovery.restore.o.event_id = response.event_id;
						app.settings.recovery.timeline.info(lang('We will now reset your account first.'));
						app.settings.recovery.restore.progress();
						break;

					case 'get_account_info':
						app.settings.recovery.restore.o.total = response.data.subscription_count +
							response.data.folder_count +
							response.data.tag_count +
							response.data.payment_method_count +
							response.data.contact_count +
							response.data.alert_profile_count +
							response.data.api_token_count +
							response.data.webhook_count;

						// app.settings.recovery.restore.o.percentage = 0;
						// app.settings.recovery.restore.o.done = 0;

						// Check if empty
						if (app.settings.recovery.restore.o.total < 12) {
							// = Count + (6 * 2)
							app.settings.recovery.restore.o.total = 12;
						// } else {
						// 	app.settings.recovery.restore.o.total += 5;
						}

						if (response.message) {
							app.settings.recovery.timeline.success(response.message);
						}

						app.settings.recovery.timeline.function(response.command, lang('Total Subscriptions: ') + response.data.subscription_count);
						app.settings.recovery.timeline.function(response.command, lang('Total Folders: ') + response.data.folder_count);
						app.settings.recovery.timeline.function(response.command, lang('Total Tags: ') + response.data.tag_count);
						app.settings.recovery.timeline.function(response.command, lang('Total Payment Methods: ') + response.data.payment_method_count);
						app.settings.recovery.timeline.function(response.command, lang('Total Contacts: ') + response.data.contact_count);
						app.settings.recovery.timeline.function(response.command, lang('Total Alert Profiles: ') + response.data.alert_profile_count);
						app.settings.recovery.timeline.function(response.command, lang('Total API Tokens: ') + response.data.api_token_count);
						app.settings.recovery.timeline.function(response.command, lang('Total Webhooks: ') + response.data.webhook_count);
						// app.settings.recovery.timeline.function(response.command, lang('Total Team Accounts: ') + response.data.team_account_count);

						app.settings.recovery.timeline.info(lang('Resetting your account...'));
						break;

					case 'restore_success':

						if (response.message) {
							app.settings.recovery.timeline.success(response.message);
						}

						app.settings.recovery.timeline.info(lang('You will be now redirected to the login page.'));
						app.settings.recovery.timeline.info(lang('Please click on the OK button to proceed.'));

						lib.sleep(100).then(function () {
							app.settings.recovery.restore.enable_ui();
						});

						app.settings.recovery.restore.progress();
						break;


					case 'get_backup_info':
						app.settings.recovery.restore.o.total += response.data.subscription.count +
							response.data.folder.count +
							response.data.tag.count +
							response.data.payment_method.count +
							response.data.contact.count +
							response.data.alert_profile.count +
							response.data.api_token.count +
							response.data.webhook.count;

						// app.settings.recovery.restore.o.percentage = 0;
						// app.settings.recovery.restore.o.done = 0;

						// Check if empty
						if (app.settings.recovery.restore.o.total < 12) {
							// = Count + (6 * 2)
							app.settings.recovery.restore.o.total = 12;
						// } else {
						// 	app.settings.recovery.restore.o.total += 5;
						}

						if (response.message) {
							app.settings.recovery.timeline.success(response.message);
						}

						app.settings.recovery.timeline.function(response.command, lang('Total Subscriptions: ') + response.data.subscription.count);
						app.settings.recovery.timeline.function(response.command, lang('Total Folders: ') + response.data.folder.count);
						app.settings.recovery.timeline.function(response.command, lang('Total Tags: ') + response.data.tag.count);
						app.settings.recovery.timeline.function(response.command, lang('Total Payment Methods: ') + response.data.payment_method.count);
						app.settings.recovery.timeline.function(response.command, lang('Total Contacts: ') + response.data.contact.count);
						app.settings.recovery.timeline.function(response.command, lang('Total Alert Profiles: ') + response.data.alert_profile.count);
						app.settings.recovery.timeline.function(response.command, lang('Total API Tokens: ') + response.data.api_token.count);
						app.settings.recovery.timeline.function(response.command, lang('Total Webhooks: ') + response.data.webhook.count);
						break;
				}

			} else {
				app.settings.recovery.timeline.warning(response.message);
				app.settings.recovery.timeline.info(lang('Please click on the OK button to reload this page, then try again.'));
				app.settings.recovery.restore.enable_ui();
			}

			// app.settings.recovery.restore.execute(response);


			// Check next command and execute it
			if (response.next_command) {

				app.settings.recovery.restore.o.command = response.next_command;

				lib.sleep(app.settings.recovery.restore.o.delay).then(function () {
					app.settings.recovery.restore.request();
				});
			}

		} else {

			app.settings.recovery.restore.request();
		}

	}

	progress(value = null) {
		app.settings.recovery.restore.o.done++;
		app.settings.recovery.restore.o.percentage = parseInt(app.settings.recovery.restore.o.done / app.settings.recovery.restore.o.total * 100);

		// Check extra values
		if (app.settings.recovery.restore.o.percentage > 99) {
			app.settings.recovery.restore.o.percentage = 99;
		}

		if (value) {
			app.settings.recovery.restore.o.percentage = value;
		}

		let progress_bar = $(app.settings.recovery.restore.e.progress_bar);

		if (progress_bar.length) {
			progress_bar.attr('aria-valuenow', app.settings.recovery.restore.o.percentage);
			progress_bar.css('width', app.settings.recovery.restore.o.percentage + '%');
			progress_bar.text(app.settings.recovery.restore.o.percentage + '%');
		}
	}

	execute(response) {

		// Check next command and execute it
		if (response.next_command) {

			lib.sleep(app.settings.recovery.restore.o.delay).then(function () {
				app.settings.recovery.restore.o.delay -= app.settings.recovery.restore.d.delay;
				app.settings.recovery.restore.handle();
			});

			app.settings.recovery.restore.o.delay += app.settings.recovery.restore.d.delay;
		}
	}

	enable_ui() {
		// Enable modal buttons
		app.settings.recovery.restore.o.working = false;
		$(app.settings.recovery.restore.e.modal).find('.modal-footer button').attr('disabled', false).show();
		$(app.settings.recovery.restore.e.modal).find('.modal-footer').show();
		$(app.settings.recovery.restore.e.progress_bar_container).hide();
	}

	finish(ctl) {
		$(ctl).attr('disabled', true);
		window.location.href = window.location.href;
	}
}


class clsApp_Settings_Recovery_Reset {
	e = {
		// Elements

		modal: '#modal_settings_recovery_reset',
		progress_bar: '#modal_settings_recovery_reset .modal-footer .progress-bar',
		progress_bar_container: '#modal_settings_recovery_reset .modal-footer .progress-bar-animated-alt',
		container: '#settings_recovery_reset_timeline_container',
	};
	d = {
		// Default values

		delay: 10,
		color: {
			info: '#ff6f00',
			success: '#1b5e20',
			warning: '#d92550',
			error: '#b71c1c',
			black: '#343a40',

			subscription: '#fdd835',
			folder: '#00bcd4',
			tag: '#4527a0',
			payment_method: '#ff5722',
			contact: '#2196f3',
			alert_profile: '#3e2723',
			api_token: 'yellow',
			webhook: 'green',
			team_account: 'blue',
		},
	};
	o = {
		// Objects

		command: 'get_account_info',
		last_command: '',
		last_message: '',
		event_id: '',
		delay: 10,
		working: false,
		percentage: 0,
		total: 0,
		done: 0,
	};
	c = {
		// Configuration

		all_command: {},
	};

	constructor() {
	}

	init() {
	}

	start(ctl) {
		swal({
			title: "Are you sure?",
			text: lang("This action will delete all your Subscriptions, Folders, Tags, Payment methods, Contacts, Alert profiles, API tokens, Webhooks and it will reset your account."),
			icon: "warning",
			buttons: true,
			dangerMode: true,
		}).then((willDelete) => {
			if (willDelete) {

				// Change mode to reset
				app.settings.recovery.timeline.o.selected = app.settings.recovery.reset;

				app.settings.recovery.reset.o.working = true;
				window.onbeforeunload = function () {
					if (app.settings.recovery.reset.o.working) {
						return lang('Your account reset in progress. Do you want to stop now?');
					}
				};

				$(app.settings.recovery.reset.e.modal).modal({
					backdrop: 'static',
					keyboard: false,
				});

				lib.sleep(500).then(function () {
					app.settings.recovery.reset.handle();
				});
			}
		});
	}

	request(ctl) {
		let message = app.settings.recovery.reset.c.commands[app.settings.recovery.reset.o.command].message.request;
		if (message) {
			app.settings.recovery.timeline.info(message, false);
		}

		let form_data = new FormData();
		form_data.append('_token', app.config.token);
		form_data.append('command', app.settings.recovery.reset.o.command);
		form_data.append('event_id', app.settings.recovery.reset.o.event_id);

		$.ajax({
			url: app.url + 'settings/recovery/reset',
			type: 'POST',
			data: form_data,
			dataType: 'json',
			beforeSend: function (xhr) {
				app.loading.btn(ctl);
			},
			success: function (response) {
				app.settings.recovery.reset.handle(response);
			},
			error: function (response) {
				app.settings.recovery.timeline.response(response);
			},
			complete: function () {
				app.loading.btn(ctl);
			},
			processData: false,
			contentType: false,
		});
	}


	handle(response) {

		if (response) {

			// Check status
			if (response.status) {

				switch (response.command) {

					case 'get_account_info':
						app.settings.recovery.reset.o.total = response.data.subscription_count +
							response.data.folder_count +
							response.data.tag_count +
							response.data.payment_method_count +
							response.data.contact_count +
							response.data.alert_profile_count +
							response.data.api_token_count +
							response.data.webhook_count;

						app.settings.recovery.reset.o.percentage = 0;
						app.settings.recovery.reset.o.done = 0;

						// Check if empty
						if (app.settings.recovery.reset.o.total < 5) {
							app.settings.recovery.reset.o.total = 5;
						}

						if (response.message) {
							app.settings.recovery.timeline.success(response.message, false);
						}

						app.settings.recovery.timeline.function(response.command, lang('Total Subscriptions: ') + response.data.subscription_count);
						app.settings.recovery.timeline.function(response.command, lang('Total Folders: ') + response.data.folder_count);
						app.settings.recovery.timeline.function(response.command, lang('Total Tags: ') + response.data.tag_count);
						app.settings.recovery.timeline.function(response.command, lang('Total Payment Methods: ') + response.data.payment_method_count);
						app.settings.recovery.timeline.function(response.command, lang('Total Contacts: ') + response.data.contact_count);
						app.settings.recovery.timeline.function(response.command, lang('Total Alert Profiles: ') + response.data.alert_profile_count);
						app.settings.recovery.timeline.function(response.command, lang('Total API Tokens: ') + response.data.api_token_count);
						app.settings.recovery.timeline.function(response.command, lang('Total Webhooks: ') + response.data.webhook_count);

						// Set event data
						app.settings.recovery.reset.o.event_id = response.event_id;
						break;

					case 'delete_subscription':
					case 'delete_folder':
					case 'delete_tag':
					case 'delete_payment_method':
					case 'delete_contact':
					case 'delete_alert_profile':
					case 'delete_api_token':
					case 'delete_webhook':
					case 'delete_file_all':
						if (response.message) {
							app.settings.recovery.timeline.function(response.command, response.message);
						}

						app.settings.recovery.reset.progress();
						break;

					case 'reset_success':
						app.settings.recovery.reset.progress(100);

						if (response.message) {
							app.settings.recovery.timeline.success(response.message, false);
						}

						app.settings.recovery.timeline.info(lang('You will be now redirected to the login page.'), false);
						app.settings.recovery.timeline.info(lang('Please click on the OK button to proceed.'), false);


						lib.sleep(100).then(function () {
							app.settings.recovery.reset.enable_ui();
						});
						break;
				}

			} else {
				app.settings.recovery.timeline.warning(response.message);
				app.settings.recovery.timeline.info(lang('Please click on the OK button to reload this page, then try again.'));
				app.settings.recovery.reset.enable_ui();
			}

			// app.settings.recovery.reset.execute(response);


			// Check next command and execute it
			if (response.next_command) {

				app.settings.recovery.reset.o.command = response.next_command;

				lib.sleep(app.settings.recovery.reset.o.delay).then(function () {
					app.settings.recovery.reset.request();
				});
			}

		} else {
			app.settings.recovery.reset.request();
		}
	}

	progress(value = null) {
		app.settings.recovery.reset.o.done++;
		app.settings.recovery.reset.o.percentage = parseInt(app.settings.recovery.reset.o.done / app.settings.recovery.reset.o.total * 100);

		// Check extra values
		if (app.settings.recovery.reset.o.percentage > 99) {
			app.settings.recovery.reset.o.percentage = 99;
		}

		if (value) {
			app.settings.recovery.reset.o.percentage = value;
		}

		let progress_bar = $(app.settings.recovery.reset.e.progress_bar);

		if (progress_bar.length) {
			progress_bar.attr('aria-valuenow', app.settings.recovery.reset.o.percentage);
			progress_bar.css('width', app.settings.recovery.reset.o.percentage + '%');
			progress_bar.text(app.settings.recovery.reset.o.percentage + '%');
		}
	}

	execute(response) {

		// Check next command and execute it
		if (response.next_command) {

			lib.sleep(app.settings.recovery.reset.o.delay).then(function () {
				app.settings.recovery.reset.o.delay -= app.settings.recovery.reset.d.delay;
				app.settings.recovery.reset.handle();
			});

			app.settings.recovery.reset.o.delay += app.settings.recovery.reset.d.delay;
		}
	}

	enable_ui() {
		// Enable modal buttons
		app.settings.recovery.reset.o.working = false;
		$(app.settings.recovery.reset.e.modal).find('.modal-footer button').attr('disabled', false).show();
		$(app.settings.recovery.reset.e.modal).find('.modal-footer').show();
		$(app.settings.recovery.reset.e.progress_bar_container).hide();
	}

	finish(ctl) {
		$(ctl).attr('disabled', true);
		window.location.href = window.location.href;
	}
}


class clsApp_Settings_Recovery_Timeline {
	e = {
		// Elements

		template: {
			custom: '#tpl_settings_recovery_timeline_custom',
			success: '#tpl_settings_recovery_timeline_success',
			info: '#tpl_settings_recovery_timeline_info',
			warning: '#tpl_settings_recovery_timeline_warning',
			error: '#tpl_settings_recovery_timeline_error',
		},
	};
	d = {
		// Default values

	};
	o = {
		// Objects

		scroll_count: 0,
		selected: {},
	};
	c = {
		// Configuration

	};

	constructor() {
	}

	init() {
	}

	scroll(duration = 0) {
		// Scroll to end of content area

		if (app.settings.recovery.timeline.o.scroll_count < 6) {
			app.settings.recovery.timeline.o.scroll_count++;
			return;
		}

		let parent = $(app.settings.recovery.timeline.o.selected.e.container).parent();

		if (parent.length) {
			$(parent).animate({ scrollTop: parent[0].scrollHeight - parent[0].clientHeight }, duration)
		}
	}

	custom(title, color, delay = 0) {

		// lib.sleep(delay).then(function () {

		let html = $(app.settings.recovery.timeline.e.template.custom).html()
			.replace('__TITLE__', title)
			.replace('__COLOR__', color);
		$(app.settings.recovery.timeline.o.selected.e.container).append(html);

		// Auto scroll content
		app.settings.recovery.timeline.scroll(500);

		// });
	}

	clear() {
		$(app.settings.recovery.timeline.o.selected.e.container).html('');
	}

	response(response) {
		if (response.message && typeof response.message === 'string') {
			app.settings.recovery.timeline.error(response.message);
		}
		else if (response.responseJSON && typeof response.responseJSON === 'object' && response.responseJSON.message && typeof response.responseJSON.message === 'string') {
			app.settings.recovery.timeline.error(response.responseJSON.message);
		}
		else if (response.statusText && typeof response.statusText === 'string') {
			app.settings.recovery.timeline.error(response.statusText);
		}

		// Auto scroll content
		app.settings.recovery.timeline.scroll(500);
	}

	success(title, execute_next_command = false) {
		app.settings.recovery.timeline.execute(title, app.settings.recovery.timeline.o.selected.d.color.success, execute_next_command);
	}

	info(title, execute_next_command = false) {
		app.settings.recovery.timeline.execute(title, app.settings.recovery.timeline.o.selected.d.color.info, execute_next_command);
	}

	warning(title, execute_next_command = false) {
		app.settings.recovery.timeline.execute(title, app.settings.recovery.timeline.o.selected.d.color.warning, execute_next_command);
	}

	error(title, execute_next_command = false) {
		app.settings.recovery.timeline.execute(title, app.settings.recovery.timeline.o.selected.d.color.error, execute_next_command);
	}

	black(title, execute_next_command = false) {
		app.settings.recovery.timeline.execute(title, app.settings.recovery.timeline.o.selected.d.color.black, execute_next_command);
	}

	execute(title, color, execute_next_command, container) {

		lib.sleep(app.settings.recovery.timeline.o.selected.o.delay).then(function () {
			app.settings.recovery.timeline.o.selected.o.delay -= app.settings.recovery.timeline.o.selected.d.delay;

			let html = $(app.settings.recovery.timeline.e.template.custom).html()
				.replace('__TITLE__', title)
				.replace('__COLOR__', color);

			if (container) {
				$(container).append(html);
			} else {
				$(app.settings.recovery.timeline.o.selected.e.container).append(html);
			}

			// Auto scroll content
			// app.settings.recovery.timeline.scroll(app.settings.recovery.timeline.o.selected.o.delay);
			app.settings.recovery.timeline.scroll();

			if (execute_next_command) {
				app.settings.recovery.timeline.o.selected.handle();
			}

		});

		app.settings.recovery.timeline.o.selected.o.delay += app.settings.recovery.timeline.o.selected.d.delay;
	}

	function(type, title) {
		if (app.settings.recovery.timeline.o.selected.c.commands.hasOwnProperty(type)) {
			app.settings.recovery.timeline.execute(title, app.settings.recovery.timeline.o.selected.c.commands[type].color.default);
		}
	}
}

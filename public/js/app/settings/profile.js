// Instantiate this class
$(document).ready(function () {
	app.register('settings.profile.reset', new clsApp_Settings_Profile_Reset);
	app.register('settings.profile.timeline', new clsApp_Settings_Profile_Timeline);
});

class clsApp_Settings_Profile_Reset {
	e = {
		// Elements

		modal: '#modal_settings_profile_reset',
		progress_bar: '#modal_settings_profile_reset .modal-footer .progress-bar',
		progress_bar_container: '#modal_settings_profile_reset .modal-footer .progress-bar-animated-alt',
		container: '#settings_profile_reset_timeline_container',
	};
	d = {
		// Default values

		delay: 10,
		color: {
			default: '#00bcd4',
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
				app.settings.profile.timeline.o.selected = app.settings.profile.reset;

				app.settings.profile.reset.o.working = true;
				window.onbeforeunload = function () {
					if (app.settings.profile.reset.o.working) {
						return lang('Your account reset in progress. Do you want to stop now?');
					}
				};

				$(app.settings.profile.reset.e.modal).modal({
					backdrop: 'static',
					keyboard: false,
				});

				lib.sleep(500).then(function () {
					app.settings.profile.reset.handle();
				});
			}
		});
	}

	request(ctl) {
		let message = app.settings.profile.reset.c.commands[app.settings.profile.reset.o.command].message.request;
		if (message) {
			app.settings.profile.timeline.info(message, false);
		}

		let form_data = new FormData();
		form_data.append('_token', app.config.token);
		form_data.append('command', app.settings.profile.reset.o.command);
		form_data.append('event_id', app.settings.profile.reset.o.event_id);

		$.ajax({
			url: app.url + 'settings/profile/reset',
			type: 'POST',
			data: form_data,
			dataType: 'json',
			beforeSend: function (xhr) {
				app.loading.btn(ctl);
			},
			success: function (response) {
				app.settings.profile.reset.handle(response);
			},
			error: function (response) {
				app.settings.profile.timeline.response(response);
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
						app.settings.profile.reset.o.total = response.data.subscription_count +
							response.data.folder_count +
							response.data.tag_count +
							response.data.payment_method_count +
							response.data.contact_count +
							response.data.alert_profile_count +
							response.data.api_token_count +
							response.data.webhook_count;

						app.settings.profile.reset.o.percentage = 0;
						app.settings.profile.reset.o.done = 0;

						// Check if empty
						if (app.settings.profile.reset.o.total < 5) {
							app.settings.profile.reset.o.total = 5;
						}

						if (response.message) {
							app.settings.profile.timeline.success(response.message, false);
						}

						app.settings.profile.timeline.function(response.command, lang('Total Subscriptions: ') + response.data.subscription_count);
						app.settings.profile.timeline.function(response.command, lang('Total Folders: ') + response.data.folder_count);
						app.settings.profile.timeline.function(response.command, lang('Total Tags: ') + response.data.tag_count);
						app.settings.profile.timeline.function(response.command, lang('Total Payment Methods: ') + response.data.payment_method_count);
						app.settings.profile.timeline.function(response.command, lang('Total Contacts: ') + response.data.contact_count);
						app.settings.profile.timeline.function(response.command, lang('Total Alert Profiles: ') + response.data.alert_profile_count);
						app.settings.profile.timeline.function(response.command, lang('Total API Tokens: ') + response.data.api_token_count);
						app.settings.profile.timeline.function(response.command, lang('Total Webhooks: ') + response.data.webhook_count);

						// Set event data
						app.settings.profile.reset.o.event_id = response.event_id;
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
							app.settings.profile.timeline.function(response.command, response.message);
						}

						app.settings.profile.reset.progress();
						break;

					case 'reset_success':
						app.settings.profile.reset.progress(100);

						if (response.message) {
							app.settings.profile.timeline.success(response.message, false);
						}

						app.settings.profile.timeline.info(lang('Please click on the OK button to proceed.'), false);


						lib.sleep(100).then(function () {
							app.settings.profile.reset.enable_ui();
						});
						break;
				}

			} else {
				app.settings.profile.timeline.warning(response.message);
				app.settings.profile.timeline.info(lang('Please click on the OK button to reload this page, then try again.'));
				app.settings.profile.reset.enable_ui();
			}

			// app.settings.profile.reset.execute(response);


			// Check next command and execute it
			if (response.next_command) {

				app.settings.profile.reset.o.command = response.next_command;

				lib.sleep(app.settings.profile.reset.o.delay).then(function () {
					app.settings.profile.reset.request();
				});
			}

		} else {
			app.settings.profile.reset.request();
		}
	}

	progress(value = null) {
		app.settings.profile.reset.o.done++;
		app.settings.profile.reset.o.percentage = parseInt(app.settings.profile.reset.o.done / app.settings.profile.reset.o.total * 100);

		// Check extra values
		if (app.settings.profile.reset.o.percentage > 99) {
			app.settings.profile.reset.o.percentage = 99;
		}

		if (value) {
			app.settings.profile.reset.o.percentage = value;
		}

		let progress_bar = $(app.settings.profile.reset.e.progress_bar);

		if (progress_bar.length) {
			progress_bar.attr('aria-valuenow', app.settings.profile.reset.o.percentage);
			progress_bar.css('width', app.settings.profile.reset.o.percentage + '%');
			progress_bar.text(app.settings.profile.reset.o.percentage + '%');
		}
	}

	execute(response) {

		// Check next command and execute it
		if (response.next_command) {

			lib.sleep(app.settings.profile.reset.o.delay).then(function () {
				app.settings.profile.reset.o.delay -= app.settings.profile.reset.d.delay;
				app.settings.profile.reset.handle();
			});

			app.settings.profile.reset.o.delay += app.settings.profile.reset.d.delay;
		}
	}

	enable_ui() {
		// Enable modal buttons
		app.settings.profile.reset.o.working = false;
		$(app.settings.profile.reset.e.modal).find('.modal-footer button').attr('disabled', false).show();
		$(app.settings.profile.reset.e.modal).find('.modal-footer').show();
		$(app.settings.profile.reset.e.progress_bar_container).hide();
	}

	finish(ctl) {
		$(ctl).attr('disabled', true);
		window.location.href = window.location.href;
	}

    delete_account(ctl) {
		$(ctl).attr('disabled', true);
        $(app.settings.profile.reset.e.modal).modal('hide');
        app.settings.profile.reset.o.command = 'get_account_info';
        $(app.settings.profile.timeline.o.selected.e.container).html('');
        app.settings.profile.timeline.default(lang('Loading please wait...'), false);

		swal({
			title: "Are you sure?",
			text: lang("Once deleted you won't be able to recover your account. Are you sure you want to delete your account?"),
			icon: "warning",
			buttons: true,
			dangerMode: true,
		}).then((willDelete) => {
			if (willDelete) {
                let form_data = new FormData();
                form_data.append('_token', app.config.token);

                $.ajax({
                    url: app.url + 'settings/profile/reset/delete_account',
                    type: 'POST',
                    data: form_data,
                    dataType: 'json',
                    beforeSend: function (xhr) {
                        app.loading.btn(ctl);
                    },
                    success: function (response) {
                        if (response.status) {
                            app.alert.succ(response.message);
                            app.load.tooltip();
                            window.location.href = window.location.href;
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


class clsApp_Settings_Profile_Timeline {
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

		if (app.settings.profile.timeline.o.scroll_count < 6) {
			app.settings.profile.timeline.o.scroll_count++;
			return;
		}

		let parent = $(app.settings.profile.timeline.o.selected.e.container).parent();

		if (parent.length) {
			$(parent).animate({ scrollTop: parent[0].scrollHeight - parent[0].clientHeight }, duration)
		}
	}

	custom(title, color, delay = 0) {

		// lib.sleep(delay).then(function () {

		let html = $(app.settings.profile.timeline.e.template.custom).html()
			.replace('__TITLE__', title)
			.replace('__COLOR__', color);
		$(app.settings.profile.timeline.o.selected.e.container).append(html);

		// Auto scroll content
		app.settings.profile.timeline.scroll(500);

		// });
	}

	clear() {
		$(app.settings.profile.timeline.o.selected.e.container).html('');
	}

	response(response) {
		if (response.message && typeof response.message === 'string') {
			app.settings.profile.timeline.error(response.message);
		}
		else if (response.responseJSON && typeof response.responseJSON === 'object' && response.responseJSON.message && typeof response.responseJSON.message === 'string') {
			app.settings.profile.timeline.error(response.responseJSON.message);
		}
		else if (response.statusText && typeof response.statusText === 'string') {
			app.settings.profile.timeline.error(response.statusText);
		}

		// Auto scroll content
		app.settings.profile.timeline.scroll(500);
	}

	success(title, execute_next_command = false) {
		app.settings.profile.timeline.execute(title, app.settings.profile.timeline.o.selected.d.color.success, execute_next_command);
	}

	info(title, execute_next_command = false) {
		app.settings.profile.timeline.execute(title, app.settings.profile.timeline.o.selected.d.color.info, execute_next_command);
	}

	warning(title, execute_next_command = false) {
		app.settings.profile.timeline.execute(title, app.settings.profile.timeline.o.selected.d.color.warning, execute_next_command);
	}

	error(title, execute_next_command = false) {
		app.settings.profile.timeline.execute(title, app.settings.profile.timeline.o.selected.d.color.error, execute_next_command);
	}

	black(title, execute_next_command = false) {
		app.settings.profile.timeline.execute(title, app.settings.profile.timeline.o.selected.d.color.black, execute_next_command);
	}

	default(title, execute_next_command = false) {
		app.settings.profile.timeline.execute(title, app.settings.profile.timeline.o.selected.d.color.default, execute_next_command);
	}

	execute(title, color, execute_next_command, container) {

		lib.sleep(app.settings.profile.timeline.o.selected.o.delay).then(function () {
			app.settings.profile.timeline.o.selected.o.delay -= app.settings.profile.timeline.o.selected.d.delay;

			let html = $(app.settings.profile.timeline.e.template.custom).html()
				.replace('__TITLE__', title)
				.replace('__COLOR__', color);

			if (container) {
				$(container).append(html);
			} else {
				$(app.settings.profile.timeline.o.selected.e.container).append(html);
			}

			// Auto scroll content
			// app.settings.profile.timeline.scroll(app.settings.profile.timeline.o.selected.o.delay);
			app.settings.profile.timeline.scroll();

			if (execute_next_command) {
				app.settings.profile.timeline.o.selected.handle();
			}

		});

		app.settings.profile.timeline.o.selected.o.delay += app.settings.profile.timeline.o.selected.d.delay;
	}

	function(type, title) {
		if (app.settings.profile.timeline.o.selected.c.commands.hasOwnProperty(type)) {
			app.settings.profile.timeline.execute(title, app.settings.profile.timeline.o.selected.c.commands[type].color.default);
		}
	}
}

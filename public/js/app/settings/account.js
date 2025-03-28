// Instantiate this class
$(document).ready(function () {
	app.register('settings.account', new clsApp_Settings_Account);
	app.register('settings.account.timeline', new clsApp_Settings_Account_Timeline);
});


class clsApp_Settings_Account {
	e = {
		// Elements

		reset: {
			modal: '#modal_settings_account_reset',
			progress_bar: '#modal_settings_account_reset .modal-footer .progress-bar',
			progress_bar_container: '#modal_settings_account_reset .modal-footer .progress-bar-animated-alt',
		},
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
		delay: 10,
		resetting: false,
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

	reset(ctl) {
		swal({
			title: "Are you sure?",
			text: lang("This action will delete all your Subscriptions, Folders, Tags, Payment methods, Contacts, Alert profiles, API tokens, Webhooks, Team accounts and it will reset your account."),
			icon: "warning",
			buttons: true,
			dangerMode: true,
		}).then((willDelete) => {
			if (willDelete) {

				app.settings.account.o.resetting = true;
				window.onbeforeunload = function () {
					if (app.settings.account.o.resetting) {
						return lang('Your account reset in progress. Do you want to stop now?');
					}
				};

				$(app.settings.account.e.reset.modal).modal({
					backdrop: 'static',
					keyboard: false,
				});

				lib.sleep(500).then(function () {
					app.settings.account.handle();
				});
			}
		});
	}

	request(ctl) {
		let message = app.settings.account.c.all_command[app.settings.account.o.command].message.request;
		if (message) {
			app.settings.account.timeline.info(message, false);
		}

		let form_data = new FormData();
		form_data.append('_token', app.config.token);
		form_data.append('command', app.settings.account.o.command);

		$.ajax({
			url: app.url + 'settings/account/reset',
			type: 'POST',
			data: form_data,
			dataType: 'json',
			beforeSend: function (xhr) {
				app.loading.btn(ctl);
			},
			success: function (response) {
				app.settings.account.handle(response);
			},
			error: function (response) {
				app.settings.account.timeline.response(response);
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

				// if (response.message) {
				// 	app.settings.account.timeline.success(response.message);
				// }


				switch (response.command) {

					case 'get_account_info':

						app.settings.account.o.total = response.data.subscription_count +
							response.data.folder_count +
							response.data.tag_count +
							response.data.payment_method_count +
							response.data.contact_count +
							response.data.alert_profile_count +
							response.data.api_token_count +
							response.data.webhook_count +
							response.data.team_account_count;

						app.settings.account.o.percentage = 0;
						app.settings.account.o.done = 0;

						// Check if empty
						if (app.settings.account.o.total < 5) {
							app.settings.account.o.total = 5;
						}

						if (response.message) {
							app.settings.account.timeline.success(response.message, false);
						}

						app.settings.account.timeline.subscription(lang('Total Subscriptions: ') + response.data.subscription_count, false);
						app.settings.account.timeline.folder(lang('Total Folders: ') + response.data.folder_count, false);
						app.settings.account.timeline.tag(lang('Total Tags: ') + response.data.tag_count, false);
						app.settings.account.timeline.payment_method(lang('Total Payment Methods: ') + response.data.payment_method_count, false);
						app.settings.account.timeline.contact(lang('Total Contacts: ') + response.data.contact_count, false);
						app.settings.account.timeline.alert_profile(lang('Total Alert Profiles: ') + response.data.alert_profile_count, false);
						app.settings.account.timeline.api_token(lang('Total API Tokens: ') + response.data.api_token_count, false);
						app.settings.account.timeline.webhook(lang('Total Webhooks: ') + response.data.webhook_count, false);
						app.settings.account.timeline.team_account(lang('Total Team Accounts: ') + response.data.team_account_count, false);


						break;

					case 'delete_subscription':

						if (response.message) {
							app.settings.account.timeline.subscription(response.message, false);
						}

						app.settings.account.progress();

						break;

					case 'delete_folder':

						if (response.message) {
							app.settings.account.timeline.folder(response.message, false);
						}

						app.settings.account.progress();

						break;

					case 'delete_tag':

						if (response.message) {
							app.settings.account.timeline.tag(response.message, false);
						}

						app.settings.account.progress();

						break;

					case 'delete_payment_method':

						if (response.message) {
							app.settings.account.timeline.payment_method(response.message, false);
						}

						app.settings.account.progress();

						break;

					case 'delete_contact':

						if (response.message) {
							app.settings.account.timeline.contact(response.message, false);
						}

						app.settings.account.progress();

						break;

					case 'delete_alert_profile':

						if (response.message) {
							app.settings.account.timeline.alert_profile(response.message, false);
						}

						app.settings.account.progress();

						break;

					case 'delete_api_token':

						if (response.message) {
							app.settings.account.timeline.api_token(response.message, false);
						}

						app.settings.account.progress();

						break;

					case 'delete_webhook':

						if (response.message) {
							app.settings.account.timeline.webhook(response.message, false);
						}

						app.settings.account.progress();

						break;

					case 'delete_team_account':

						if (response.message) {
							app.settings.account.timeline.team_account(response.message, false);
						}

						app.settings.account.progress();

						break;

					case 'delete_file_all':
						break;

					case 'reset_success':

						app.settings.account.progress(100);

						if (response.message) {
							app.settings.account.timeline.success(response.message, false);
						}

						app.settings.account.timeline.info(lang('You will be now redirected to the login page.'), false);
						app.settings.account.timeline.info(lang('Please click on the OK button to proceed.'), false);


						lib.sleep(100).then(function () {
							app.settings.account.o.resetting = false;
							$(app.settings.account.e.reset.modal).find('.modal-footer button').attr('disabled', false).show();
							$(app.settings.account.e.reset.modal).find('.modal-footer').show();
							$(app.settings.account.e.reset.progress_bar_container).hide();
						});

						break;
				}

			} else {
				app.settings.account.timeline.warning(response.message);
			}

			// app.settings.account.execute(response);


			// Check next command and execute it
			if (response.next_command) {

				app.settings.account.o.command = response.next_command;

				lib.sleep(app.settings.account.o.delay).then(function () {
					app.settings.account.request();
				});
			}

		} else {

			app.settings.account.request();
		}

	}

	progress(value = null) {
		app.settings.account.o.done++;
		app.settings.account.o.percentage = parseInt(app.settings.account.o.done / app.settings.account.o.total * 100);

		// Check extra values
		if (app.settings.account.o.percentage > 99) {
			app.settings.account.o.percentage = 99;
		}

		if (value) {
			app.settings.account.o.percentage = value;
		}

		let progress_bar = $(app.settings.account.e.reset.progress_bar);

		if (progress_bar.length) {
			progress_bar.attr('aria-valuenow', app.settings.account.o.percentage);
			progress_bar.css('width', app.settings.account.o.percentage + '%');
			progress_bar.text(app.settings.account.o.percentage + '%');
		}
	}

	execute(response) {

		// Check next command and execute it
		if (response.next_command) {

			lib.sleep(app.settings.account.o.delay).then(function () {
				app.settings.account.o.delay -= app.settings.account.d.delay;
				app.settings.account.handle();
			});

			app.settings.account.o.delay += app.settings.account.d.delay;
		}
	}

	finish(ctl) {
		$(ctl).attr('disabled', true);
		window.location.href = app.url;
	}
}

class clsApp_Settings_Account_Timeline {
	e = {
		// Elements

		container: '#settings_account_reset_timeline_container',
		template: {
			custom: '#tpl_settings_account_reset_timeline_custom',
			success: '#tpl_settings_account_reset_timeline_success',
			info: '#tpl_settings_account_reset_timeline_info',
			warning: '#tpl_settings_account_reset_timeline_warning',
			error: '#tpl_settings_account_reset_timeline_error',
		},
	};
	d = {
		// Default values

	};
	o = {
		// Objects

		scroll_count: 0,
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

		if (app.settings.account.timeline.o.scroll_count < 6) {
			app.settings.account.timeline.o.scroll_count++;
			return;
		}

		let parent = $(app.settings.account.timeline.e.container).parent();

		if (parent.length) {
			$(parent).animate({ scrollTop: parent[0].scrollHeight - parent[0].clientHeight }, duration)
		}
	}

	custom(title, color, delay = 0) {

		// lib.sleep(delay).then(function () {

		let html = $(app.settings.account.timeline.e.template.custom).html()
			.replace('__TITLE__', title)
			.replace('__COLOR__', color);
		$(app.settings.account.timeline.e.container).append(html);

		// Auto scroll content
		app.settings.account.timeline.scroll(500);

		// });
	}

	// success(title) {
	// 	let html = $(app.settings.account.timeline.e.template.custom).html()
	// 		.replace('__TITLE__', title)
	// 		.replace('__COLOR__', app.settings.account.d.color.success);
	// 	$(app.settings.account.timeline.e.container).append(html);

	// 	// Auto scroll content
	// 	app.settings.account.timeline.scroll(500);
	// }

	// info(title) {
	// 	let html = $(app.settings.account.timeline.e.template.custom).html()
	// 		.replace('__TITLE__', title)
	// 		.replace('__COLOR__', app.settings.account.d.color.success);
	// 	$(app.settings.account.timeline.e.container).append(html);

	// 	// Auto scroll content
	// 	app.settings.account.timeline.scroll(500);
	// }

	// warning(title) {
	// 	let html = $(app.settings.account.timeline.e.template.custom).html()
	// 		.replace('__TITLE__', title)
	// 		.replace('__COLOR__', app.settings.account.d.color.error);
	// 	$(app.settings.account.timeline.e.container).append(html);

	// 	// Auto scroll content
	// 	app.settings.account.timeline.scroll(500);
	// }

	// error(title) {
	// 	let html = $(app.settings.account.timeline.e.template.custom).html()
	// 		.replace('__TITLE__', title)
	// 		.replace('__COLOR__', app.settings.account.d.color.error);
	// 	$(app.settings.account.timeline.e.container).append(html);

	// 	// Auto scroll content
	// 	app.settings.account.timeline.scroll(500);
	// }

	clear() {
		$(app.settings.account.timeline.e.container).html('');
	}

	response(response) {
		if (response.message && typeof response.message === 'string') {
			app.settings.account.timeline.error(response.message);
		}
		else if (response.responseJSON && typeof response.responseJSON === 'object' && response.responseJSON.message && typeof response.responseJSON.message === 'string') {
			app.settings.account.timeline.error(response.responseJSON.message);
		}
		else if (response.statusText && typeof response.statusText === 'string') {
			app.settings.account.timeline.error(response.statusText);
		}

		// Auto scroll content
		app.settings.account.timeline.scroll(500);
	}



	success(title, execute_next_command = false) {
		app.settings.account.timeline.execute(title, app.settings.account.d.color.success, execute_next_command);
	}

	info(title, execute_next_command = false) {
		app.settings.account.timeline.execute(title, app.settings.account.d.color.info, execute_next_command);
	}

	warning(title, execute_next_command = false) {
		app.settings.account.timeline.execute(title, app.settings.account.d.color.warning, execute_next_command);
	}

	error(title, execute_next_command = false) {
		app.settings.account.timeline.execute(title, app.settings.account.d.color.error, execute_next_command);
	}

	black(title, execute_next_command = false) {
		app.settings.account.timeline.execute(title, app.settings.account.d.color.black, execute_next_command);
	}

	subscription(title, execute_next_command = false) {
		app.settings.account.timeline.execute(title, app.settings.account.d.color.subscription, execute_next_command);
	}

	folder(title, execute_next_command = false) {
		app.settings.account.timeline.execute(title, app.settings.account.d.color.folder, execute_next_command);
	}

	tag(title, execute_next_command = false) {
		app.settings.account.timeline.execute(title, app.settings.account.d.color.tag, execute_next_command);
	}

	payment_method(title, execute_next_command = false) {
		app.settings.account.timeline.execute(title, app.settings.account.d.color.payment_method, execute_next_command);
	}

	contact(title, execute_next_command = false) {
		app.settings.account.timeline.execute(title, app.settings.account.d.color.contact, execute_next_command);
	}

	alert_profile(title, execute_next_command = false) {
		app.settings.account.timeline.execute(title, app.settings.account.d.color.alert_profile, execute_next_command);
	}

	api_token(title, execute_next_command = false) {
		app.settings.account.timeline.execute(title, app.settings.account.d.color.api_token, execute_next_command);
	}

	webhook(title, execute_next_command = false) {
		app.settings.account.timeline.execute(title, app.settings.account.d.color.webhook, execute_next_command);
	}

	team_account(title, execute_next_command = false) {
		app.settings.account.timeline.execute(title, app.settings.account.d.color.team_account, execute_next_command);
	}



	execute(title, color, execute_next_command) {

		lib.sleep(app.settings.account.o.delay).then(function () {
			app.settings.account.o.delay -= app.settings.account.d.delay;

			let html = $(app.settings.account.timeline.e.template.custom).html()
				.replace('__TITLE__', title)
				.replace('__COLOR__', color);
			$(app.settings.account.timeline.e.container).append(html);

			// Auto scroll content
			// app.settings.account.timeline.scroll(app.settings.account.o.delay);
			app.settings.account.timeline.scroll();

			if (execute_next_command) {
				app.settings.account.handle();
			}

		});

		app.settings.account.o.delay += app.settings.account.d.delay;
	}
}

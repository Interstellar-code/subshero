// Instantiate this class
$(document).ready(function () {
	app.register('subscription.pdf', new clsApp_Subscription_PDF);
});


class clsApp_Subscription_PDF {
	e = {
		// Elements

		import: {
			form: '#frm_subscription_pdf_import',
			modal: '#modal_subscription_pdf_import',
			draggable: '#subscription_pdf_import_draggable',
			step_2: '#modal_subscription_pdf_import .modal-content .step_2',
			step_2_container: '#modal_subscription_pdf_import .modal-content .step_2 .container',
			step_3: '#modal_subscription_pdf_import .modal-content .step_3',
			step_3_container: '#modal_subscription_pdf_import .modal-content .step_3_container',
			file_input: '#modal_subscription_pdf_import .modal-content .step_1 input[type=file]',
		},
	};
	d = {
		// Default values

	};
	o = {
		// Objects

		import: {
			processing: false,
			modal: null,
		},
	};
	c = {
		// Configuration

	};
	f = {
		// Callback function

		import: {
			draggable: function (event) {
				event.preventDefault();
				event.stopPropagation();

				if (event.type == 'dragenter') {
					$(app.subscription.pdf.e.import.draggable).addClass('dragging');
				} else if (event.type == 'dragleave') {
					$(app.subscription.pdf.e.import.draggable).removeClass('dragging');
				} else if (event.type == 'drop') {
					$(app.subscription.pdf.e.import.draggable).removeClass('dragging');

					let file_input = $(app.subscription.pdf.e.import.file_input)[0];
					file_input.files = event.dataTransfer.files;
					app.subscription.pdf.import(file_input);
				}

				return false;
			},
		},
	};
	t = {
		// Template

	};

	constructor() {
	}

	init() {
		$(document).ready(function () {
			app.subscription.pdf.o.modal = $(app.subscription.pdf.e.import.modal);
			app.subscription.pdf.o.modal.on('hidden.bs.modal', function (event) {

				// Reset the modal
				$(app.subscription.pdf.e.import.step_2_container).hide();
				$(app.subscription.pdf.e.import.step_2).show();
				$(app.subscription.pdf.e.import.step_3).hide();

				// Reset file input
				$(app.subscription.pdf.e.import.file_input).val('');
			});


			// Drag and drop handler
			let drop_area = $(app.subscription.pdf.e.import.draggable)[0];
			drop_area.addEventListener('dragenter', app.subscription.pdf.f.import.draggable, false)
			drop_area.addEventListener('dragleave', app.subscription.pdf.f.import.draggable, false)
			drop_area.addEventListener('dragover', app.subscription.pdf.f.import.draggable, false)
			drop_area.addEventListener('drop', app.subscription.pdf.f.import.draggable, false)
		});
	}

	import(file_input) {
		// Check if file is selected
		if (file_input.files.length == 0) {
			return;
		}

		// Check if file is import is already processing
		if (app.subscription.pdf.o.import.processing) {
			app.alert.warn(lang('Please wait for the current process to finish.'));
			return;
		}

		let form_data = new FormData();
		form_data.append('_token', app.config.token);
		form_data.append('file', file_input.files[0]);

		$.ajax({
			url: app.url + 'subscription/pdf/import',
			type: 'POST',
			dataType: 'json',
			data: form_data,
			beforeSend: function (xhr) {
				$(app.subscription.pdf.e.import.step_2).show();
				$(app.subscription.pdf.e.import.step_2_container).show();
				$(app.subscription.pdf.e.import.step_3).hide();
				// app.loading.btn(ctl);

				app.subscription.pdf.o.import.processing = true;
			},
			success: function (response) {
				if (response.status) {
					$(app.subscription.pdf.e.import.step_3_container).html(response.content);
					$(app.subscription.pdf.e.import.step_2).hide();
					$(app.subscription.pdf.e.import.step_3).show();
					app.load.tooltip();
				} else {
					app.alert.validation(response);
				}
			},
			error: function (response) {
				app.alert.response(response);
			},
			complete: function () {
				app.subscription.pdf.o.import.processing = false;

				// app.loading.btn(ctl);
				$(app.subscription.pdf.e.import.step_2_container).hide();

				// Reset file input
				$(file_input).val('');
			},
			processData: false,
			contentType: false,
		});
	}

	save(ctl) {
		app.global.create({
			form: app.subscription.pdf.e.import.form,
			slug: 'subscription/pdf/save',
			btn: ctl,
			success: function (response) {
				if (response.status) {
					app.alert.succ(response.message);
					app.subscription.load_page();
				} else {
					app.alert.validation(response.message);
				}
			},
		});
	}

	open_file_dialog(ctl) {
		if (!app.subscription.pdf.o.import.processing) {
			$(ctl).parent().find('input[type=file]').click();
		}
	}

	type_check(el) {
		let type_el = $(el);
		let type = type_el.find(':selected').val();

		// Find all elements
		let billing_frequency = $(el).closest('tr').find('select[name^="billing_frequency"]');
		let billing_cycle = $(el).closest('tr').find('select[name^="billing_cycle"]');
		let recurring = $(el).closest('tr').find('input[name^="recurring"]');
		recurring.val(0);

		// Check if this is lifetime
		if (type == 3) {
			billing_frequency.attr('disabled', true);
			billing_cycle.attr('disabled', true);

			billing_frequency.val(billing_frequency.find('option:first').val());
			billing_cycle.val(billing_cycle.find('option:first').val());
		}

		// For other types
		else {
			billing_frequency.attr('disabled', false);
			billing_cycle.attr('disabled', false);

			// Subscription
			if (type == 1) {
				recurring.val(1);
			}
		}

		app.subscription.create_recurring_check(recurring[0]);
	}
}


$(document).ready(function () {
	app.register('customer', new clsAdmin_Customer);
});

class clsAdmin_Customer {

	e = {
		// Elements

		index: {
			table: '#tbl_customer',
		},
		add: {
			form: '#customer_add_form',
			img: '#customer_add_image_file',
			modal: '#customer_add_modal',
			modal_body: '#customer_add_modal .modal-body',
		},
		edit: {
			form: '#customer_edit_form',
			img: '#customer_edit_image_file',
			modal: '#customer_edit_modal',
			modal_body: '#customer_edit_modal .modal-body',
		},
	};
	d = {
		// Default values

	};
	o = {
		// Objects

		add: {
			img: null,
		},
		edit: {
			img: null,
		},
	};
	c = {
		// Configuration

	};

	constructor() {
	}

	init() {
	}

	edit(ctl) {
		let id = lib.get_id(ctl);
		if (!id) {
			return false;
		}

		let form_data = new FormData();
		form_data.append('_token', app.config.token);
		form_data.append('id', id);

		$.ajax({
			url: app.url + 'admin/customer/edit/' + id,
			type: 'POST',
			data: form_data,
			beforeSend: function (xhr) {
				app.loading.btn(ctl);
			},
			success: function (response) {
				if (response) {
					$(app.customer.e.edit.modal).find('.modal-body').html(response);
					$(app.customer.e.edit.modal).modal();
					app.load.tooltip();
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

	update(ctl, id) {
		app.global.create({
			form: app.customer.e.edit.form,
			slug: 'admin/customer/update/' + id,
			btn: ctl,
			success: function (response) {
				if (response.status) {
					app.alert.succ(response.message);
					$(app.customer.e.edit.form)[0].reset();
					app.page.switch('admin/customer');
				} else {
					app.alert.validation(response.message);
				}
			},
		});
	}

}

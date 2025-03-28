// Instantiate this class
$(document).ready(function () {
	app.register('subscription.adapt', new clsApp_Subscription_Adapt);
});


class clsApp_Subscription_Adapt {
	e = {
		// Elements

		edit: {
			form: '#frm_subscription_adapt_edit',
			modal: '#modal_subscription_adapt_edit',
			modal_content: '#modal_subscription_adapt_edit .modal-content',
		},
	};
	d = {
		// Default values

	};
	o = {
		// Objects

		edit: {
			image: null,
		},
	};
	c = {
		// Configuration

	};
	f = {
		// Callback function

	};
	t = {
		// Template

	};

	constructor() {
	}

	init() {
	}

	submit(ctl) {
		let id = lib.get_id(ctl);
		if (!id) {
			return false;
		}

		// let product_name = $(ctl).data('product_name');
		let product_name = $(ctl).closest('tr').data('product_name');
		if (!product_name) {
			product_name = '"App"';
		}

		swal({
			title: 'Are you sure?',
			text: `Submitted ${product_name} is to be notified to Subshero Admin. \nOnce reviewed and added to database, you will find a notification in your dashboard to adapt the details.`,
			icon: 'info',
			buttons: true,
			dangerMode: true,
		}).then((willDelete) => {
			if (willDelete) {
				let form_data = new FormData();
				form_data.append('_token', app.config.token);
				form_data.append('id', id);

				$.ajax({
					url: app.url + 'subscription/adapt/submit/' + id,
					type: 'POST',
					data: form_data,
					dataType: 'json',
					beforeSend: function (xhr) {
						app.loading.btn(ctl);
					},
					success: function (response) {
						if (response.status) {
							$('#tbl_subscription').DataTable().ajax.reload(null, false);
							app.alert.succ(response.message);
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

	edit(ctl) {
		let id = lib.get_id(ctl);
		if (!id) {
			return false;
		}

		$.ajax({
			url: app.url + 'subscription/adapt/edit/' + id,
			type: 'GET',
			beforeSend: function (xhr) {
				app.loading.btn(ctl);
			},
			success: function (response) {
				if (response) {
					$(app.subscription.adapt.e.edit.modal_content).html(response);
					$(app.subscription.adapt.e.edit.modal).modal();
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

	accept(ctl, id) {
		app.global.create({
			form: app.subscription.adapt.e.edit.form,
			slug: 'subscription/adapt/accept/' + id,
			image: (app.subscription.adapt.o.edit.image.getFile() ? app.subscription.adapt.o.edit.image.getFile().file : null),
			btn: ctl,
			success: function (response) {
				if (response.status) {
					app.alert.succ(response.message);
					$(app.subscription.adapt.e.edit.modal).modal('hide');
					$(app.subscription.adapt.e.edit.form)[0].reset();
					app.page.switch('subscription');
				} else {
					app.alert.validation(response.message);
				}
			},
		});
	}
}

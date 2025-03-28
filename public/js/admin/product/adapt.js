// Instantiate this class
$(document).ready(function () {
	app.register('product.adapt', new clsAdmin_Product_Adapt);
});

class clsAdmin_Product_Adapt {
	e = {
		// Elements

		index: {
			table: '#tpl_admin_product_adapt_table_btn',
			tpl_btn: '#tpl_admin_product_adapt_table_btn',
		},
		edit: {
			form: '#product_adapt_edit_form',
			img: '#product_adapt_edit_image_file',
			img_fav: '#product_adapt_edit_image_favicon_file',
			modal: '#product_adapt_edit_modal',
			modal_body: '#product_adapt_edit_modal .modal-body',
		},
	};
	d = {
		// Default values

	};
	o = {
		// Objects

		edit: {
			img: null,
			img_fav: null,
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
			url: app.url + 'admin/product/adapt/edit/' + id,
			type: 'POST',
			data: form_data,
			beforeSend: function (xhr) {
				app.loading.btn(ctl);
			},
			success: function (response) {
				if (response) {
					$(app.product.adapt.e.edit.modal_body).html(response);
					$(app.product.adapt.e.edit.modal).modal();
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
			form: app.product.adapt.e.edit.form,
			slug: 'admin/product/adapt/update/' + id,
			image: (app.product.adapt.o.edit.img.getFile() ? app.product.adapt.o.edit.img.getFile().file : null),
			image_favicon: (app.product.adapt.o.edit.img_fav.getFile() ? app.product.adapt.o.edit.img_fav.getFile().file : null),
			btn: ctl,
			success: function (response) {
				if (response.status) {
					app.alert.succ(response.message);
					$(app.product.adapt.e.edit.modal).modal('hide');
					$(app.product.adapt.e.edit.form)[0].reset();
					app.product.adapt.o.edit.img.removeFile();
					app.product.adapt.o.edit.img_fav.removeFile();
					app.product.adapt.init();
					// app.page.switch('admin/product');
					$("#tbl_product_adapt").DataTable().ajax.reload(null, false);

					$('img.favicon').attr('src', app.config.favicon_url);

					// lib.img.reset($(image), 200);
					// app.page.switch('main');
				} else {
					app.alert.validation(response.message);
				}
			},
		});
	}

	delete(ctl) {
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
				form_data.append('id', id);

				$.ajax({
					url: app.url + 'admin/product/adapt/delete/' + id,
					type: 'POST',
					data: form_data,
					dataType: 'json',
					beforeSend: function (xhr) {
						app.loading.btn(ctl);
					},
					success: function (response) {
						if (response) {
							// app.page.switch('admin/product');
							$("#tbl_product_adapt").DataTable().ajax.reload(null, false);
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

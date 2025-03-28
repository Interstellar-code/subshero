
$(document).ready(function () {
	app.register('product', new clsAdmin_Product);
	app.register('product.import', new clsAdmin_Product_Import);
});

class clsAdmin_Product {

	e = {
		// Elements

		index: {
			table: '#tpl_admin_product_table_btn',
			tpl_btn: '#tpl_admin_product_table_btn',
		},
		add: {
			form: '#product_add_form',
			img: '#product_add_image_file',
			img_fav: '#product_add_image_favicon_file',
			modal: '#product_add_modal',
			modal_body: '#product_add_modal .modal-body',
		},
		edit: {
			form: '#product_edit_form',
			img: '#product_edit_image_file',
			img_fav: '#product_edit_image_favicon_file',
			modal: '#product_edit_modal',
			modal_body: '#product_edit_modal .modal-body',
		},
	};
	d = {
		// Default values

	};
	o = {
		// Objects

		add: {
			img: null,
			img_fav: null,
		},
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

		if (app.product.o.add.img) {
			app.product.o.add.img.destroy();
			app.product.o.add.img.destroy = null;
		}
		if (app.product.o.add.img_fav) {
			app.product.o.add.img_fav.destroy();
			app.product.o.add.img_fav.destroy = null;
		}
		lib.sleep(100).then(function () {
			app.product.o.add.img = lib.img.filepond(app.product.e.add.img);
			app.product.o.add.img_fav = lib.img.filepond(app.product.e.add.img_fav, {
				labelIdle: `Drag & Drop your picture or <span class="filepond--label-action">Browse</span>`,
				imagePreviewHeight: 170,
				imageCropAspectRatio: '1:1',
				imageResizeTargetWidth: 200,
				imageResizeTargetHeight: 200,
				stylePanelLayout: 'compact circle',
				styleLoadIndicatorPosition: 'center bottom',
				styleButtonRemoveItemPosition: 'center bottom',
			});
		});

		// Load product table
		// $('#tbl_product').DataTable({
		// 	// ... skipped ...
		// 	// dom: 'l<"toolbar">frtip',
		// 	initComplete: function () {
		// 		$('#tbl_product_filter').prepend(`
		// 			<a href="{{ route('admin/product/import') }}" class="btn-shadow btn btn-wide btn-primary mr-3">@lang('Import')</a>
		// 			<button type="button" class="btn-shadow btn btn-wide btn-primary mr-3" data-toggle="modal" data-target="#product_add_modal">@lang('Add')</button>
		// 		`);
		// 	},
		// });


	}

	create(ctl) {
		app.global.create({
			form: app.product.e.add.form,
			slug: 'admin/product/create',
			image: (app.product.o.add.img.getFile() ? app.product.o.add.img.getFile().file : null),
			image_favicon: (app.product.o.add.img_fav.getFile() ? app.product.o.add.img_fav.getFile().file : null),
			btn: ctl,
			success: function (response) {
				if (response.status) {
					app.alert.succ(response.message);
					$(app.product.e.add.form)[0].reset();
					app.product.o.add.img.removeFile();
					app.product.o.add.img_fav.removeFile();
					app.product.init();
					// app.page.switch('admin/product');
					$("#tbl_product").DataTable().ajax.reload(null, false);

					$('img.favicon').attr('src', app.config.favicon_url);

					// lib.img.reset($(image), 200);
					// app.page.switch('main');
				} else {
					app.alert.validation(response.message);
				}
			},
		});
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
			url: app.url + 'admin/product/edit/' + id,
			type: 'POST',
			data: form_data,
			beforeSend: function (xhr) {
				app.loading.btn(ctl);
			},
			success: function (response) {
				if (response) {
					$(app.product.e.edit.modal_body).html(response);
					$(app.product.e.edit.modal).modal();
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
			form: app.product.e.edit.form,
			slug: 'admin/product/update/' + id,
			image: (app.product.o.edit.img.getFile() ? app.product.o.edit.img.getFile().file : null),
			image_favicon: (app.product.o.edit.img_fav.getFile() ? app.product.o.edit.img_fav.getFile().file : null),
			btn: ctl,
			success: function (response) {
				if (response.status) {
					app.alert.succ(response.message);
					$(app.product.e.edit.modal).modal('hide');
					$(app.product.e.edit.form)[0].reset();
					app.product.o.edit.img.removeFile();
					app.product.o.edit.img_fav.removeFile();
					app.product.init();
					// app.page.switch('admin/product');
					$("#tbl_product").DataTable().ajax.reload(null, false);

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
					url: app.url + 'admin/product/delete/' + id,
					type: 'POST',
					data: form_data,
					dataType: 'json',
					beforeSend: function (xhr) {
						app.loading.btn(ctl);
					},
					success: function (response) {
						if (response) {
							// app.page.switch('admin/product');
							$("#tbl_product").DataTable().ajax.reload(null, false);
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


class clsAdmin_Product_Import {

	e = {
		// Elements

		step1: {
			form: '#frm_product_import_load',
		},
		step2: {
			container: '#product_import_form_container',
		},
	};
	d = {
		// Default values

	};
	o = {
		// Objects

		add: {
			img: null,
			img_fav: null,
		},
		edit: {
			img: null,
			img_fav: null,
		},

		validate_form_all: [],
		save_form_all: [],
		validating: false,
		saving: false,

		step1: {
		},
		step2: {
			btn: null,
		},
		step3: {
			btn: null,
		},
	};
	c = {
		// Configuration

	};

	constructor() {
	}

	init() {
	}

	load(ctl) {
		let form_data = new FormData($(app.product.import.e.step1.form).get(0));
		// form_data.append('_token', app.config.token);

		$('#product_import_table_container').html('');

		$.ajax({
			url: app.url + 'admin/product/import/load',
			type: 'POST',
			data: form_data,
			beforeSend: function (xhr) {
				app.loading.btn(ctl);
			},
			success: function (response) {
				if (response) {
					$('#product_import_table_container').html(response);
					$('#settings_import_smartwizard').smartWizard("next");
					// $('.form-wizard-content.tab-content').css('height', null);
				} else {
					// app.alert.warn(response.message);
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

	validate(ctl) {
		let validate_form_all = $(app.product.import.e.step2.container).find('form');
		app.product.import.o.step1.btn = ctl;

		if (validate_form_all.length > 0) {
			app.product.import.o.validate_form_all = [];
			app.product.import.o.validating = true;

			validate_form_all.each(function (index, element) {
				app.product.import.o.validate_form_all.push(element);
			});

			app.product.import.o.validate_form_all = app.product.import.o.validate_form_all.reverse();
			app.product.import.o.save_form_all = [];
			app.product.import._validate_next();

			// app.product.import.o.validate_form_all = validate_form_all;
		}
	}

	_validate_next() {
		if (!app.product.import.o.validate_form_all.length) {

			if (app.product.import.o.validating) {
				app.product.import.o.validating = false;
				app.product.import.o.saving = true;
				app.product.import.o.save_form_all = app.product.import.o.save_form_all.reverse();

				$('#btn_settings_import_step3').attr('disabled', false);
				$('#settings_import_smartwizard').smartWizard("next");
			}

			return false;
		}

		let form = app.product.import.o.validate_form_all.pop();
		let form_data = new FormData(form);

		$.ajax({
			url: app.url + 'admin/product/import/validate',
			type: 'POST',
			data: form_data,
			dataType: 'json',
			beforeSend: function (xhr) {
				app.loading.btn(app.product.import.o.step1.btn);
			},
			success: function (response) {
				if (response.status) {
					app.product.import.o.save_form_all.push(form);
					$(form.id).closest('tr').removeClass('td_error');
					app.product.import._validate_next();
				} else {
					$(form.id).closest('tr').addClass('td_error');
					app.alert.validation(response.message);
				}
			},
			error: function (response) {
				// app.alert.response(response);
			},
			complete: function () {
				app.loading.btn(app.product.import.o.step1.btn);
			},
			processData: false,
			contentType: false,
		});

	}

	save(ctl) {
		if (!app.product.import.o.save_form_all.length) {

			if (app.product.import.o.saving) {
				app.product.import.o.saving = false;
				$('#btn_settings_import_step3').attr('disabled', true);
				app.alert.succ('Success');

				setTimeout(function () {
					window.location.href = app.url + 'admin/product/import';
				}, 1000);
			}

			return false;
		}

		let form = app.product.import.o.save_form_all.pop();
		let form_data = new FormData(form);

		$.ajax({
			url: app.url + 'admin/product/import/save',
			type: 'POST',
			data: form_data,
			dataType: 'json',
			beforeSend: function (xhr) {
				$('#btn_settings_import_step3').attr('disabled', true);
				app.loading.btn(ctl);
			},
			success: function (response) {
				if (response.status) {
					app.product.import.save(ctl);
				} else {
					app.product.import.o.saving = false;
					app.product.import.o.save_form_all = [];
					app.alert.validation(response.message);
					$('#btn_settings_import_step3').attr('disabled', false);
				}
			},
			error: function (response) {
				// app.alert.response(response);
			},
			complete: function () {
				app.loading.btn(ctl);
			},
			processData: false,
			contentType: false,
		});

	}

	export(el) {
		$.ajax({
			url: app.url + 'admin/product/import/export',
			type: 'GET',
			dataType: 'json',
			beforeSend: function (xhr) {
				app.loading.btn(el);
			},
			success: function (response) {
				if (response.status) {
					lib.do.download('products.csv', Papa.unparse(response.data));
					app.alert.succ(response.message);
				} else {
					app.alert.warn(response.message);
				}
			},
			error: function (response) {
				app.alert.response(response);
			},
			complete: function () {
				app.loading.btn(el);
			},
			processData: false,
			contentType: false,
		});
	}
}

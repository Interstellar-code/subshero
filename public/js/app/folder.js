
$(document).ready(function () {
	app.register('folder', new clsApp_Folder);
});


class clsApp_Folder {
	_form_add = '#frm_folder_add';
	_form_quick_add = '#frm_main_quick_add';
	_form_edit = '#frm_folder_edit';
	image = '#folder_add_image';
	color_add;
	color_edit;
	e = {
		// Elements

		add: {
			color_picker: '#folder_add_color_picker',
			color_input: '#folder_add_color_input',
		},
		edit: {
			color_picker: '#folder_edit_color_picker',
			color_input: '#folder_edit_color_input',
		},
		container: '#folder_container',
	};
	d = {
		// Default values

		color: '#6770d2',
	}
	o = {
		// Objects

		all: [],
	};
	c = {
		// Configuration

		color_picker: {
			// el: this.e.add.color_picker,
			theme: 'classic', // or 'monolith', or 'nano'

			swatches: [
				'rgba(244, 67, 54, 1)',
				'rgba(233, 30, 99, 0.95)',
				'rgba(156, 39, 176, 0.9)',
				'rgba(103, 58, 183, 0.85)',
				'rgba(63, 81, 181, 0.8)',
				'rgba(33, 150, 243, 0.75)',
				'rgba(3, 169, 244, 0.7)',
				'rgba(0, 188, 212, 0.7)',
				'rgba(0, 150, 136, 0.75)',
				'rgba(76, 175, 80, 0.8)',
				'rgba(139, 195, 74, 0.85)',
				'rgba(205, 220, 57, 0.9)',
				'rgba(255, 235, 59, 0.95)',
				'rgba(255, 193, 7, 1)'
			],

			components: {

				// Main components
				preview: true,
				opacity: true,
				hue: true,

				// Input / output Options
				interaction: {
					hex: true,
					rgba: true,
					// hsla: true,
					// hsva: true,
					// cmyk: true,
					input: true,
					clear: true,
					save: true
				}
			},
			default: this.d.color,
			closeWithKey: 'Escape',
			comparison: false,
		},
		add: {
			color_picker: null,
		},
		edit: {
			color_picker: null,
		}
	};


	constructor() {
		// Setup color picker configuration
		this.c.add.color_picker = Object.assign({}, this.c.color_picker);
		this.c.add.color_picker.el = this.e.add.color_picker;

		this.c.edit.color_picker = Object.assign({}, this.c.color_picker);
		this.c.edit.color_picker.el = this.e.edit.color_picker;

	}

	init() {
		$(document).ready(function () {

			// Check if element found and loaded
			if ($(app.folder.e.add.color_picker).length) {
				app.folder.color_add = Pickr.create(app.folder.c.add.color_picker);
				app.folder.color_add.on('save', (color, instance) => {
					$(app.folder.e.add.color_input).val(color ? color.toHEXA().toString() : '');
					app.folder.color_add.hide();
				}).on('change', function (color, instance) {
					$(app.folder.e.add.color_input).val(color ? color.toHEXA().toString() : '');
				});
			}

			// Check if element found and loaded
			if ($(app.folder.e.edit.color_picker).length) {
				app.folder.color_edit = Pickr.create(app.folder.c.edit.color_picker);
				app.folder.color_edit.on('save', (color, instance) => {
					$(app.folder.e.edit.color_input).val(color ? color.toHEXA().toString() : '');
					app.folder.color_edit.hide();
				}).on('change', function (color, instance) {
					$(app.folder.e.edit.color_input).val(color ? color.toHEXA().toString() : '');
				});
			}
		});
	}

	create(ctl) {
		app.global.create({
			form: app.folder._form_add,
			slug: 'folder/create',
			btn: ctl,
			success: function (response) {
				if (response.status) {
					app.alert.succ(response.message);
					// lib.sub.modal_folder_add_done();
					$(app.folder._form_add)[0].reset();
					// app.folder.img_add.removeFile();
					// app.folder.init();
					// app.page.switch('folder');

					// lib.img.reset($(image), 200);
					// app.page.switch('main');

					$('#modal_folder_add').modal('hide');
					app.folder.refresh();
				} else {
					app.alert.validation(response.message);
				}
			},
		});
	}

	edit(ctl, id) {
		let form_data = new FormData();
		form_data.append('_token', app.config.token);
		form_data.append('id', id);

		$.ajax({
			url: app.url + 'folder/edit/' + id,
			type: 'POST',
			data: form_data,
			dataType: 'json',
			beforeSend: function (xhr) {
				app.loading.btn(ctl);
			},
			success: function (response) {
				if (response) {
					$('#folder_edit_id').val(response.id);
					$('#folder_edit_color_input').val(response.color);
					$('#folder_edit_name').val(response.name);

					// Color picker
					if (response.color) {
						app.folder.color_edit.setColor(response.color);
					} else {
						app.folder.color_edit.setColor(app.folder.d.color);
					}

					// Default checkbox
					if (response.is_default) {
						$('#folder_edit_is_default').prop('checked', true);
					} else {
						$('#folder_edit_is_default').prop('checked', false);
					}

					$('#folder_edit_price_type').val(response.price_type);

					$('#modal_folder_edit').modal();
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
			form: app.folder._form_edit,
			slug: 'folder/update/' + id,
			btn: ctl,
			success: function (response) {
				if (response.status) {
					app.alert.succ(response.message);
					$('#modal_folder_edit').modal('hide');
					$(app.folder._form_edit)[0].reset();
					app.folder.refresh();
				} else {
					app.alert.validation(response.message);
				}
			},
		});
	}

	delete(ctl, id) {
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
					url: app.url + 'folder/delete/' + id,
					type: 'POST',
					data: form_data,
					dataType: 'json',
					beforeSend: function (xhr) {
						app.loading.btn(ctl);
					},
					success: function (response) {
						if (response.status) {
							app.folder.refresh();
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

	refresh() {
		let form_data = new FormData();
		form_data.append('_token', app.config.token);

		$.ajax({
			url: app.url + 'folder/refresh',
			type: 'POST',
			data: form_data,
			beforeSend: function (xhr) {
				app.loading.block(app.folder.e.container);
			},
			success: function (response) {
				if (response) {
					$(app.folder.e.container).html(response);
				}
			},
			error: function (response) {
				app.alert.response(response);
			},
			complete: function () {
				app.loading.unblock(app.folder.e.container, true);
			},
			processData: false,
			contentType: false,
		});
	}

	sort_disable() {
		let slug = app.page.get_slug();

		app.calendar.o.filter.folder_id = null;
		$('#folder_container .dropdown-item.active').removeClass('active');

		// Clear folder selection
		let form_data = new FormData();
		form_data.append('_token', app.config.token);

		$.ajax({
			url: app.url + 'folder/session/clear',
			type: 'POST',
			data: form_data,
			success: function (response) {

				if (slug == 'calendar') {
					app.calendar.o.instance.render();
				} else {
					app.page.switch('subscription');
				}

			},
			error: function (response) {
			},
			complete: function () {
			},
			processData: false,
			contentType: false,
		});

	}

}

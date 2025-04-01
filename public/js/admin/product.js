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
		logoSearch: {
			currentProductId: null,
			isEdit: false,
			createBoth: true
		}
	};
	c = {
		// Configuration
		logoSearchEndpoint: 'admin/product/search-logo',
		logoDownloadEndpoint: 'admin/product/download-logo',
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

		// Initialize logo search functionality
		this.initLogoSearch();
	}

	/**
	 * Create a new product and handle logo download if selected
	 * @param {HTMLElement} ctl - The control element that triggered the action
	 */
	create(ctl) {
		// Check if a logo was selected
		const selectedLogo = $('#product_add_form').data('selected-logo');
		
		app.global.create({
			form: app.product.e.add.form,
			slug: 'admin/product/create',
			image: (app.product.o.add.img.getFile() ? app.product.o.add.img.getFile().file : null),
			image_favicon: (app.product.o.add.img_fav.getFile() ? app.product.o.add.img_fav.getFile().file : null),
			btn: ctl,
			success: function (response) {
				if (response.status) {
					app.alert.succ(response.message);
					
					// If a logo was selected, download it for the newly created product
					if (selectedLogo) {
						const productId = response.data.id;
						if (productId) {
							app.product.downloadLogo(
								selectedLogo.url, 
								selectedLogo.type, 
								productId, 
								selectedLogo.create_both
							);
							// Clear the selected logo data
							$('#product_add_form').removeData('selected-logo');
						}
					}
					
					$(app.product.e.add.form)[0].reset();
					app.product.o.add.img.removeFile();
					app.product.o.add.img_fav.removeFile();
					app.product.init();
					$("#tbl_product").DataTable().ajax.reload(null, false);
					$('img.favicon').attr('src', app.config.favicon_url);
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
					
					// Add search icon to product name field in edit form
					setTimeout(function() {
						const productNameField = $('#product_edit_product_name');
						if (productNameField.length && productNameField.parent('.input-group').length === 0) {
							productNameField.wrap('<div class="input-group"></div>');
							productNameField.after(`
								<div class="input-group-append">
									<span class="input-group-text search-logo-icon" id="product_edit_search_logo" style="cursor: pointer;">
										<i class="fa fa-search"></i>
									</span>
								</div>
							`);
							
							// Show search icon if product name has value
							if (productNameField.val().trim().length > 0) {
								$('#product_edit_search_logo').show();
							} else {
								$('#product_edit_search_logo').hide();
							}
							
							// Add input handler for edit form
							productNameField.on('input', function() {
								const productName = $(this).val().trim();
								if (productName.length > 0) {
									$('#product_edit_search_logo').show();
								} else {
									$('#product_edit_search_logo').hide();
								}
							});
						}
					}, 500);
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
					$("#tbl_product").DataTable().ajax.reload(null, false);
					$('img.favicon').attr('src', app.config.favicon_url);
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

	/**
	 * Initialize logo search functionality
	 */
	initLogoSearch() {
		// Show/hide search icon based on product name input for add form
		$('#product_add_product_name').on('input', function() {
			const productName = $(this).val().trim();
			if (productName.length > 0) {
				$('#product_add_search_logo').show();
			} else {
				$('#product_add_search_logo').hide();
			}
		});

		// Handle click on search icon for add form
		$('#product_add_search_logo').on('click', function() {
			const productName = $('#product_add_product_name').val().trim();
			if (productName.length > 0) {
				app.product.o.logoSearch.isEdit = false;
				app.product.o.logoSearch.currentProductId = null;
				app.product.searchLogo(productName);
			}
		});

		// Handle click on search icon for edit form (will be added dynamically)
		$(document).on('click', '#product_edit_search_logo', function() {
			const productName = $('#product_edit_product_name').val().trim();
			const productId = $('#product_edit_id').val();
			if (productName.length > 0) {
				app.product.o.logoSearch.isEdit = true;
				app.product.o.logoSearch.currentProductId = productId;
				app.product.searchLogo(productName);
			}
		});

		// Handle click on logo search result
		$(document).on('click', '.logo-search-result', function() {
			const logoUrl = $(this).data('url');
			const logoType = $(this).data('type');
			const productId = app.product.o.logoSearch.currentProductId;
			
			// If we're in edit mode, we need a product ID
			if (app.product.o.logoSearch.isEdit && !productId) {
				app.alert.warn('Product ID not found. Please save the product first.');
				return;
			}
			
			// If we're in add mode, show a message that the logo will be downloaded after saving
			if (!app.product.o.logoSearch.isEdit) {
				// Store the selected logo info in a data attribute on the form
				$('#product_add_form').data('selected-logo', {
					url: logoUrl,
					type: logoType,
					create_both: true // Create both logo and favicon from the same image
				});
				
				app.alert.succ('Logo selected! It will be downloaded when you save the product.');
				$('#logo_search_modal').modal('hide');
				return;
			}
			
			// If we're in edit mode, download the logo immediately
			app.product.downloadLogo(logoUrl, logoType, productId, true);
		});
		
		// Add option to create both logo and favicon versions
		$(document).on('change', '#create_both_versions', function() {
			app.product.o.logoSearch.createBoth = $(this).is(':checked');
		});
	}

	/**
	 * Search for logos based on product name
	 * @param {string} productName 
	 */
	searchLogo(productName) {
		// Show the search modal
		$('#logo_search_modal').modal('show');
		
		// Clear previous results and show loading
		$('#logo_search_results').empty();
		$('#logo_search_no_results').hide();
		$('#logo_search_loading').show();
		
		// Add the option to create both versions if not already present
		if ($('#create_both_versions_container').length === 0) {
			$('#logo_search_modal .modal-footer').prepend(`
				<div id="create_both_versions_container" class="mr-auto">
					<div class="custom-control custom-checkbox">
						<input type="checkbox" class="custom-control-input" id="create_both_versions" checked>
						<label class="custom-control-label" for="create_both_versions">Create both logo and favicon</label>
					</div>
				</div>
			`);
		}
		
		// Make API request to search for logos
		$.ajax({
			url: app.url + this.c.logoSearchEndpoint,
			type: 'POST',
			data: {
				_token: app.config.token,
				product_name: productName
			},
			dataType: 'json',
			success: function(response) {
				$('#logo_search_loading').hide();
				
				if (response.status && response.data && response.data.length > 0) {
					// Group results by source type
					const groupedResults = {
						'Web Search': [],
						'Logo API': [],
						'Other': []
					};
					
					// Group the results
					response.data.forEach(function(logo) {
						if (logo.source.includes('Web Search')) {
							groupedResults['Web Search'].push(logo);
						} else if (logo.source.includes('Logo API')) {
							groupedResults['Logo API'].push(logo);
						} else {
							groupedResults['Other'].push(logo);
						}
					});
					
					// Display search results by group
					for (const [groupName, logos] of Object.entries(groupedResults)) {
						if (logos.length > 0) {
							// Add group header
							$('#logo_search_results').append(`
								<div class="col-12 mt-3 mb-2">
									<h5>${groupName} Results</h5>
									<hr>
								</div>
							`);
							
							// Add logos in this group
							logos.forEach(function(logo) {
								const logoHtml = `
									<div class="col-md-3 mb-3">
										<div class="card logo-search-result" data-url="${logo.url}" data-type="${logo.type}">
											<div class="card-body text-center">
												<img src="${logo.url}" alt="${logo.product_name}" class="img-fluid mb-2" 
													style="max-height: 100px; max-width: 100%; height: auto;" 
													onerror="this.onerror=null; this.src=''; this.alt='Image not available'; this.parentNode.classList.add('logo-load-error');">
												<p class="mb-0">${logo.source}</p>
											</div>
										</div>
									</div>
								`;
								$('#logo_search_results').append(logoHtml);
							});
						}
					}
				} else {
					// Show no results message
					$('#logo_search_no_results').show();
				}
			},
			error: function(response) {
				$('#logo_search_loading').hide();
				$('#logo_search_no_results').show();
				app.alert.response(response);
			}
		});
	}

	/**
	 * Download a logo for a product
	 * @param {string} logoUrl 
	 * @param {string} logoType 
	 * @param {number} productId 
	 * @param {boolean} createBoth - Whether to create both logo and favicon versions
	 */
	downloadLogo(logoUrl, logoType, productId, createBoth = false) {
		// Show loading
		app.loading.show();
		
		// Make API request to download the logo
		$.ajax({
			url: app.url + this.c.logoDownloadEndpoint,
			type: 'POST',
			data: {
				_token: app.config.token,
				logo_url: logoUrl,
				type: logoType,
				product_id: productId,
				create_both: createBoth || $('#create_both_versions').is(':checked')
			},
			dataType: 'json',
			success: function(response) {
				app.loading.hide();
				
				if (response.status) {
					app.alert.succ(response.message || 'Logo downloaded successfully!');
					$('#logo_search_modal').modal('hide');
					
					// Refresh the product images if needed
					if (app.product.o.logoSearch.isEdit) {
						// Reload the edit form to show the new logo
						$("#tbl_product").DataTable().ajax.reload(null, false);
					}
				} else {
					app.alert.warn(response.message || 'Failed to download logo.');
				}
			},
			error: function(response) {
				app.loading.hide();
				app.alert.response(response);
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

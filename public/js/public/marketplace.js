// Instantiate this class
$(document).ready(function () {
	app.register('marketplace', new clsApp_Marketplace);
});


class clsApp_Marketplace {
	e = {
		// Elements

		add: {
			form: '#frm_settings_marketplace_add',
			name: '#settings_marketplace_add_name',
			modal: '#modal_settings_marketplace_add',
		},
		checkout: {
			form: '#marketplace_checkout_form',
		},
	};
	d = {
		// Default values

	};
	o = {
		// Objects

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

	cart_item_create(ctl) {
		let form = $(ctl).closest('form.marketplace_item_details');

		if (form.length == 0) {
			return;
		}

		app.global.create({
			form: form,
			slug: 'cart/item',
			btn: ctl,
			success: function (response) {
				if (response.status) {
					app.page.switch('market/checkout');
				} else {
					app.alert.validation(response.message);
				}
			},
		});
	}

	checkout_save(ctl) {
		app.global.create({
			form: app.marketplace.e.checkout.form,
			slug: 'market/checkout',
            type: 'POST',
			btn: ctl,
			success: function (response) {
				if (response.status) {
					window.location.href = response.redirect;
				} else {
					app.alert.validation(response.message);
				}
			},
		});
	}
}

// Instantiate this class
$(document).ready(function () {
	app.register('subscription.marketplace', new clsApp_Subscription_Marketplace);
});


class clsApp_Subscription_Marketplace {
	e = {
		// Elements


		add: {
			form: '#frm_subscription_marketplace_add',
			modal: '#modal_subscription_marketplace_add',
			modal_content: '#modal_subscription_marketplace_add .modal-content',
		},
		edit: {
			form: '#frm_subscription_marketplace_edit',
			modal: '#modal_subscription_marketplace_edit',
			modal_content: '#modal_subscription_marketplace_edit .modal-content',
		},
	};
	d = {
		// Default values

	};
	o = {
		// Objects

		add: {
			image: null,
		},
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

    create(ctl) {
        app.global.create({
            form: app.subscription.marketplace.e.add.form,
            slug: 'subscription/marketplace/create',
            image: (app.subscription.marketplace.o.add.image.getFile() ? app.subscription.marketplace.o.add.image.getFile().file : null),
            btn: ctl,
            success: function (response) {
                if (response.status) {
                    app.alert.succ(response.message);
                    $(app.subscription.marketplace.e.add.form)[0].reset();
                    app.subscription.marketplace.o.add.image.removeFile();
                    // app.subscription.init();
                    // // app.page.switch('subscription');
                    // app.subscription.load_page();

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

		$.ajax({
			url: app.url + 'subscription/marketplace/edit/' + id,
			type: 'GET',
			beforeSend: function (xhr) {
				app.loading.btn(ctl);
			},
			success: function (response) {
				if (response) {
					$(app.subscription.marketplace.e.edit.modal_content).html(response);
					$(app.subscription.marketplace.e.edit.modal).modal();
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
			form: app.subscription.marketplace.e.edit.form,
            slug: 'subscription/marketplace/' + id,
            image: (app.subscription.marketplace.o.edit.image.getFile() ? app.subscription.marketplace.o.edit.image.getFile().file : null),
            btn: ctl,
            success: function (response) {
                if (response.status) {
					app.alert.succ(response.message);
					$(app.subscription.marketplace.e.edit.modal).modal('hide');
					$(app.subscription.marketplace.e.edit.form)[0].reset();
					app.page.switch('subscription');
                } else {
                    app.alert.validation(response.message);
                }
            },
        });
    }

	sell(ctl) {
		app.global.create({
			form: app.subscription.marketplace.e.edit.form,
			slug: 'subscription/marketplace/sell',
			btn: ctl,
			success: function (response) {
				if (response.status) {
					app.alert.succ(response.message);
					$(app.subscription.marketplace.e.edit.modal).modal('hide');
					$(app.subscription.marketplace.e.edit.form)[0].reset();
					app.page.switch('subscription');
				} else {
					app.alert.validation(response.message);
				}
			},
		});
	}
}

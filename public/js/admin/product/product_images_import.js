$(document).ready(function () {
	app.register('product.images', new clsAdmin_Product_images);
});

class clsAdmin_Product_images {
    import(ctl) {
        const form_data = new FormData(ctl.closest('form'));
        $.ajax({
            url: app.url + "admin/product/images/import",
            type: 'POST',
            data: form_data,
            success: function (response) {
                if(response.status) {
                    app.alert.succ(response.message);
                } else {
                    app.alert.warn(response.message);
                }
            },
            error: function (response) {
                app.alert.response(response);
            },
            processData: false,
            contentType: false
        })
    }
}

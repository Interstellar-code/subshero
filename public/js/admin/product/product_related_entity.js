$(document).ready(function () {
    app.register('product.related', {});
    app.register('product.related.entity', new clsAdmin_Product_related_entity);
});

class clsAdmin_Product_related_entity {

    e = {
        // Elements

        index: {
            table: '#tpl_admin_product_related_entity_table_btn',
            tpl_btn: '#tpl_admin_product_related_entity_table_btn',
        },
        add: {
            form: '#product_related_entity_add_form',
            modal: '#product_related_entity_add_modal',
            modal_body: '#product_related_entity_add_modal .modal-body',
        },
        edit: {
            form: '#product_related_entity_edit_form',
            modal: '#product_related_entity_edit_modal',
            modal_body: '#product_related_entity_edit_modal .modal-body',
        },
    };

    constructor() {
    }

    getEntityName() {
        let productRelatedEntity = window.location.href.match('product/(type|category|platform)');
        if (productRelatedEntity) {
            productRelatedEntity = productRelatedEntity[1];
        } else {
            productRelatedEntity = '';
        }
        return productRelatedEntity;
    }

    create(ctl) {
        const productRelatedEntity = app.product.related.entity.getEntityName();
        app.global.create({
            form: app.product.related.entity.e.add.form,
            slug: `admin/product/${productRelatedEntity}/create`,
            btn: ctl,
            success: function (response) {
                if (response.status) {
                    app.alert.succ(response.message);
                    $(app.product.related.entity.e.add.form)[0].reset();
                    $("#tbl_product_related_entity").DataTable().ajax.reload(null, false);
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
        const productRelatedEntity = app.product.related.entity.getEntityName();
        $.ajax({
            url: `${app.url}admin/product/${productRelatedEntity}/edit/${id}`,
            type: 'POST',
            data: form_data,
            beforeSend: function (xhr) {
                app.loading.btn(ctl);
            },
            success: function (response) {
                if (response) {
                    console.log(app.product.related.entity.e.edit.modal_body)
                    $(app.product.related.entity.e.edit.modal_body).html(response);
                    $(app.product.related.entity.e.edit.modal).modal();
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
        const productRelatedEntity = app.product.related.entity.getEntityName();
        app.global.create({
            form: app.product.related.entity.e.edit.form,
            slug: `admin/product/${productRelatedEntity}/update/${id}`,
            btn: ctl,
            success: function (response) {
                if (response.status) {
                    app.alert.succ(response.message);
                    $(app.product.related.entity.e.edit.modal).modal('hide');
                    $(app.product.related.entity.e.edit.form)[0].reset();
                    $("#tbl_product_related_entity").DataTable().ajax.reload(null, false);
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
                const productRelatedEntity = app.product.related.entity.getEntityName();
                $.ajax({
                    url: `${app.url}admin/product/${productRelatedEntity}/delete/${id}`,
                    type: 'POST',
                    data: form_data,
                    dataType: 'json',
                    beforeSend: function (xhr) {
                        app.loading.btn(ctl);
                    },
                    success: function (response) {
                        if (response) {
                            $("#tbl_product_related_entity").DataTable().ajax.reload(null, false);
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

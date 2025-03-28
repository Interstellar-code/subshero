/*!
 * Custom JavaScript
 * Version 0.1
 * Requires jQuery v3.5.1 or later
 */

// Execute code in strict mode
"use strict";

function add_success() {
    $('#frm_add').hide();
    $('#msg_success').show();

}

class clsLibrary {
    constructor() {
        this.def('lib', this, false);
        new clsApp();
        this.def('sub', new clsLibrary_Sub(), false, this);
        this.def('img', new clsLibrary_Image(), false, this);
        this.def('do', new Application_Doable(), false, this);
        this.def('validate', new clsLibrary_Validate(), false, this);
        this.def('convert', new clsLibrary_Convert(), false, this);
    }

    /**
     * Define Variable
     * @param  {[string]} varName New variable name
     * @param  {[object]} varValue Variable value
     * @param  {[boolean]} writable Read-only variable or not
     * @param  {[object]} parent Parent of the new variable
     * @return {[void]} Nothing returns
     */
    def(varName, varValue, writable = true, parent = window) {
        // Define variable with advanced options (writable, parent object)

        if (typeof parent !== 'object') {
            return false;
        } else if (parent.hasOwnProperty(varName)) {
            return false;
        } else {
            Object.defineProperties(parent,
                {
                    [varName]: {
                        value: varValue,
                        writable: writable
                    }
                });
            return true;
        }
    }

    /**
     * Check string is valid JSON data type
     * @param  {[string]} str JSON string
     * @return {[boolean]} Status of JSON data type
     */
    is_json(str) {
        try {
            JSON.parse(str);
        } catch (e) {
            return false;
        }
        return true;
    }

    /**
     * Sleep thread for specific milliseconds in asynchronous
     * @param  {[number]} milliseconds Milliseconds in number
     * @return {[void]} Nothing returns
     */
    sleep(milliseconds) {
        return new Promise(resolve => setTimeout(resolve, milliseconds))
    }

    get_id(input) {
        // Retrieve the actual Identity from the argument passed
        if (!input) return false;

        // Check if this is a HTML element
        if (typeof input == 'object') {
            let tr_id = $(input).closest('tr').data('id');
            if (typeof tr_id === 'undefined') {
                return $(input).data('id');
            } else {
                return tr_id;
            }
        } else {
            return input;
        }
    }

    Reset_SelectBox(element) {
        var selectBox = $(element);
        selectBox.find('option').not(':first').remove();
        selectBox.val(selectBox.find('option:first').val());
    }

    register(key, instance) {
        if (typeof lib[key] === 'undefined') {
            this.def(key, instance, false, this);

            if (typeof lib.product.init === 'function') {
                lib.product.init();
            }
            return true;
        }
        return false;
    }
}

class clsLibrary_Image {

    // Default URL for image
    _d_img = 'https://via.placeholder.com/';
    _months = [null, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']

    constructor() {
    }

    read(input, img, height = 200, width = 200) {
        if (input && input.files[0]) {
            let reader = new FileReader();
            reader.onload = function () {
                $(img).prop('src', reader.result);
            };
            reader.readAsDataURL(input.files[0]);
            return true;
        } else {
            this.reset(img, height, width);
            return false;
        }
    }

    reset(img, height = 200, width = null) {
        if (!width) {
            width = height;
        }
        $(img).prop('src', this._d_img + height + 'x' + width + '?text=' + height + '%20x%20' + width);
        return true;
    }

    filepond(file_input, filepond_options = app.global.filepond_options) {
        if (FilePond && file_input && $(file_input).length) {
            return FilePond.create($(file_input)[0], filepond_options);
        }
        return false;
    }

    filepond_path_encode(path) {
        if (!path) {
            return path;
        }
        let data = path.split('/');
        let new_path = '';
        if (data.length > 1) {
            data.forEach(function (item) {
                if (new_path == '') {
                    new_path = item;
                } else {
                    new_path += '&' + item;
                }
            });
        } else {
            new_path = path;
        }
        return new_path;
    }
}
class clsLibrary_Sub {
    constructor() {
    }

    modal_subscription_add() {
        $('#add_msg_success').hide();
        //$(app.subscription.e.add.status_toggle).show();
        $('#frm_subscription_add').show();
    }

    modal_subscription_add_done() {
        $('#frm_subscription_add').hide();
        $(app.subscription.e.add.status_toggle).hide();
        $('#add_msg_success').show();
    }

    modal_subscription_quick_add() {
        $('#quick_add_msg_success').hide();
        $('#frm_main_quick_add').show();
        $(app.subscription.e.add_quick.status_toggle).show();
    }

    modal_subscription_quick_add_done() {
        $('#frm_main_quick_add').hide();
        $(app.subscription.e.add_quick.status_toggle).hide();
        $('#quick_add_msg_success').show();
    }

    modal_subscription_invoice_parse(e) {
        e.preventDefault();
        console.log('modal_subscription_invoice_parse');
        $('#invoice_parse_msg_success').hide();
        $('#frm_main_invoice_parse').show();
        $(app.subscription.e.invoice_parse.status_toggle).show();
    }

}

class clsApp {
    e = {
        // Elements

    };
    d = {
        // Default values

    };
    o = {
        // Objects
        csrf_refresh_timer_id: 0,
    };
    c = {
        // Configuration
        instance: {
            app: this,
            alert: new clsApp_Alert(),
            load: new clsApp_Load(),
            global: new clsApp_Global(),
            config: new clsApp_Config(),
            page: new clsApp_Page(),
            loading: new clsApp_Loading(),
            search: new clsApp_Search(),
        },
    };

    constructor() {

        // for (const [key, val] of Object.entries(this.c.instance)) {
        //     lib.def(key, val, false);
        // }

        lib.def('app', this, false);
        lib.def('alert', new clsApp_Alert(), false, this);
        lib.def('load', new clsApp_Load(), false, this);
        lib.def('global', new clsApp_Global(), false, this);
        lib.def('config', new clsApp_Config(), false, this);
        lib.def('page', new clsApp_Page(), false, this);
        lib.def('loading', new clsApp_Loading(), false, this);
        // lib.def('subscription', new clsApp_Subscription(), false, this);
        // lib.def('folder', new clsApp_Folder(), false, this);
        // lib.def('settings', new clsApp_Settings(), false, this);
        lib.def('search', new clsApp_Search(), false, this);
        lib.def('ui', new clsApp_UI(), false, this);


        // Load dynamic data in object
        if (typeof _app_data === 'object') {
            for (let key in _app_data) {
                let val = _app_data[key];
                lib.def(key, _app_data[key], false, this);
            }
            // delete window._app_data;
            _app_data = undefined;
        }

        // Init all classes
        // app.folder.init();
        app.load.init();

    }

    init() {
        $(window).on('popstate', function () {
            app.page.handle(window.location.href);
        });

        // Datepicker options
        $.fn.datepicker.setDefaults({
            autoHide: true,
        });
    }

    register(key, instance) {
        // Dynamically create instance and register it to the application

        let all_key = key.split('.');
        let counter = 0;
        let previous_obj = undefined;
        let new_obj = undefined;
        let status = false;

        // Loop through all items
        all_key.every(function (item) {

            // Load first object from the app
            if (counter == 0) {
                previous_obj = app;
                new_obj = app[item];
            }

            // Load all other objects from the previous object
            else {
                previous_obj = previous_obj[all_key[counter - 1]];
                new_obj = previous_obj[item];
            }

            // Check if new object is not defined in previous object
            if (typeof new_obj === 'undefined') {

                // Create instance only for the last item
                if (counter == all_key.length - 1 && typeof previous_obj === 'object') {
                    lib.def(item, instance, false, previous_obj);

                    // By default call the init method of the new object
                    if (typeof previous_obj[item].init === 'function') {
                        previous_obj[item].init();
                    }

                    // Return status to the parent function
                    status = true;
                    return false;
                }

                // Return status to the parent function
                else {
                    status = false;
                    return false;
                }
            }

            counter++;
            return true;
        });

        return status;
    }

    asset(path) {
        const url = new URL(path, app.cdn_url);
        return url.href;
    }
}

class clsApp_Load {

    constructor() {
    }

    init() {
        // Refresh CSRF token every 1 hour
        // if (!app.o.csrf_refresh_timer_id) {
        //     app.load.csrf();
        //     app.o.csrf_refresh_timer_id = setInterval(function () {
        //         app.load.csrf();
        //     }, 3600000);
        // }

        $(document).ready(function () {
            app.load.modal();
            app.load.tooltip();
        });

    }

    tooltip() {
        $('.tooltip.fade.bs-tooltip-top.show').remove();
        $('[data-toggle*="tooltip"]').tooltip({
            boundary: 'window',
        });
    }

    modal() {
        $('[data-toggle*="modal"]').off('click').click(function () {
            let el = $(this);
            if (el.data('target')) {
                $(el.data('target')).modal();
            }
        });
    }

    csrf() {
        $.ajax({
            url: app.url + 'csrf/get',
            type: 'GET',
            success: function (response) {
                if (response) {
                    app.config.token = response;
                    $('input[name=_token]').val(response);
                } else {
                    app.alert.warn(response.message);
                }
            },
            error: function (response) {
                app.alert.response(response);
            },
        });
    }
}

class clsApp_Config {
    token = $('meta[name="csrf-token"]').attr('content');
    favicon_url = null;

    constructor() {
    }
}

class clsApp_Global {
    _form_add = '#frm_subscription_add';
    _form_edit = '#frm_main_edit';
    _image = '#image_preview';
    filepond_options = {
        // labelIdle: `Drag & Drop your picture or <span class="filepond--label-action">Browse</span>`,
        labelIdle: '',
        imagePreviewHeight: 92,
        imagePreviewWidth: 246,
        imageCropAspectRatio: '2.6:1',
        imageResizeTargetWidth: 246,
        imageResizeTargetHeight: 92,
        stylePanelLayout: 'compact',
        styleLoadIndicatorPosition: 'center bottom',
        styleButtonRemoveItemPosition: 'center bottom',
    };
    e = {
        // Elements

        profile_img: '.profile_img',
    };
    d = {
        // Default values

    }
    o = {
        // Objects

    };
    c = {
        // Configuration

    };

    constructor() {
        // Make the filepond_options property non-writable
        // let filepond_options = Object.assign({}, this.filepond_options);
        // delete this.filepond_options;
        // lib.def('filepond_options', filepond_options, false, this);
    }

    create(data) {
        var jv = $(data.form).validate({
            submitHandler: function (form) {
                let form_data = new FormData($(form)[0]);
                // form_data.append('_token', app.config.token);

                form_data.append('image', '');
                if (data.image && data.image instanceof File) {
                    form_data.append('image', data.image);
                }
                form_data.append('image_favicon', '');
                if (data.image_favicon && data.image_favicon instanceof File) {
                    form_data.append('image_favicon', data.image_favicon);
                }
                $.ajax({
                    // url: app.url + data.slug,
                    url: $(form).attr('action'),
                    type: 'POST',
                    dataType: 'json',
                    data: form_data,
                    beforeSend: function (xhr) {
                        app.loading.btn(data.btn);
                        // app.loading.block(form);
                    },
                    success: function ($_data, $_textStatus, $_jqXHR) {
                        app.load.tooltip();

                        if ($_data.status) {
                            $(data.form).closest('div.modal').modal('hide');
                        }

                        if (typeof data.success === 'function') {
                            data.success($_data, $_textStatus, $_jqXHR);
                        }
                    },
                    error: function (response) {
                        app.alert.response(response);
                    },
                    complete: function () {
                        app.loading.btn(data.btn);
                        // app.loading.unblock(form, 500);

                        // $(data.form).closest('div.modal').modal('hide');
                        // delete data.form;
                        // delete data.slug;
                        // delete data.image;
                        // delete data.btn;
                        // delete data.success;

                        jv.destroy();
                    },
                    processData: false,
                    contentType: false,
                });
            },
            errorElement: 'em',
            errorPlacement: function (error, element) {
                // Add the `help-block` class to the error element
                error.addClass('invalid-feedback');

                if (element.prop('type') === 'checkbox') {
                    error.insertAfter(element.parent('label'));
                } else if (element.parent().hasClass('input-group')) {
                    error.appendTo(element.parent());
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
                $(element).parents('.col-sm-5').addClass('has-error').removeClass('has-success');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
                $(element).parents('.col-sm-5').addClass('has-success').removeClass('has-error');
            },

            // Fix duplicate error messages display
            onfocusout: false,
        });
    }

    load_profile_img(img_uri) {

    }

}

class clsApp_Global_Response {

    constructor() {
    }

    handle() {

    }

}

class clsApp_Search {
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
                });
            }

            // Check if element found and loaded
            if ($(app.folder.e.edit.color_picker).length) {
                app.folder.color_edit = Pickr.create(app.folder.c.edit.color_picker);
                app.folder.color_edit.on('save', (color, instance) => {
                    $(app.folder.e.edit.color_input).val(color ? color.toHEXA().toString() : '');
                    app.folder.color_edit.hide();
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
                    if (response.color) {
                        app.folder.color_edit.setColor(response.color);
                    } else {
                        app.folder.color_edit.setColor(app.folder.d.color);
                    }
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
                        if (response) {
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

}

class clsApp_Alert {
    _options = {
        closeButton: false,
        debug: false,
        newestOnTop: false,
        progressBar: true,
        positionClass: 'toast-top-right',
        preventDuplicates: false,
        onclick: null,
        showDuration: 300,
        hideDuration: 1000,
        timeOut: 6000,
        extendedTimeOut: 1000,
        showEasing: 'swing',
        hideEasing: 'linear',
        showMethod: 'fadeIn',
        hideMethod: 'fadeOut',
    };

    constructor() {
        toastr.options = this._options;
    }

    show() {

    }

    clear() {
        toastr.clear();
    }

    remove() {
        toastr.remove();
    }

    hide() {
        toastr.clear();
    }

    succ(msg, title, obj) {
        toastr.success(msg, title, obj);
    }

    info(msg, title, obj) {
        toastr.info(msg, title, obj);
    }

    warn(msg, title, obj) {
        toastr.warning(msg, title, obj);
    }

    err(msg, title, obj) {
        toastr.error(msg, title, obj);
    }

    validation(msg_arr, multi_level = true) {
        // Laravel validation error messages display
        app.alert.remove();
        var timeout = 5000;

        if (typeof msg_arr === 'string') {
            app.alert.warn(msg_arr, null, { timeOut: timeout, });
            timeout += 5000;
        } else {
            for (let index in msg_arr) {
                if (typeof msg_arr[index] === 'string') {
                    app.alert.warn(msg_arr[index], null, { timeOut: timeout, });
                    timeout += 5000;
                } else if (multi_level) {
                    msg_arr[index].forEach(item => {
                        app.alert.warn(item, null, { timeOut: timeout, });
                        timeout += 5000;
                    });
                }
            };
        }
    }

    response(response) {
        if (response.message && typeof response.message === 'string') {
            app.alert.err(response.message);
        }
        else if (response.responseJSON && typeof response.responseJSON === 'object' && response.responseJSON.message && typeof response.responseJSON.message === 'string') {
            app.alert.err(response.responseJSON.message);
        }
        else if (response.statusText && typeof response.statusText === 'string') {
            app.alert.err(response.statusText);
        }
    }



    validation_v2(response, form) {
        if (
            typeof response !== 'object'
            // typeof response.errors !== 'object' ||
            // typeof response.message !== 'string'
        ) {
            return false;
        }

        if (typeof response.responseJSON === 'object') {

            if (typeof response.responseJSON.errors === 'object') {
                for (const [key, value] of Object.entries(response.responseJSON.errors)) {
                    let input = $(form).find('[name="' + key + '"]');

                    if (input.length) {
                        input.addClass('is-invalid');
                        if (input.siblings('.invalid-feedback').length === 0) {
                            input.after('<div class="invalid-feedback">' + value + '</div>');
                        } else {
                            input.siblings('.invalid-feedback').html(value);
                        }
                    }
                }
            }

            if (typeof response.responseJSON.message === 'string' && response.responseJSON.message !== '') {
                app.alert.error(response.responseJSON.message);
            }
        } else {
            app.alert.error(response.responseText);
        }
    }
}


class clsApp_Page {
    _title = '#page_title';
    _container = '#page_container';

    constructor() {
        // Set event listeners
        // $('.menu_toggle').click(this.switch);
    }

    switch(slug = null, push = true, post_method = false) {
        console.log(slug);

        // let item = $(event.target || event.srcElement);
        // Here "this" variable storing this class when calling this function directly
        // let menu = AP_Menu[$(this).data('slug')];

        // $(app.page._container).html(null);

        event && event.preventDefault();
        if (!slug) {
            slug = '/';
        }
        app.loading.show();

        $.ajax({
            type: post_method ? 'POST' : 'GET',
            url: app.url + slug,
            success: function (response) {
                // $(app.page._title).text(menu.title);
                // $('title').text(menu.title + ' - ' + AP_Title);
                $('#menu_container a.menu_toggle').removeClass('active');
                $('#menu_container a.menu_toggle[data-slug="' + slug + '"]').addClass('active');
                $(app.page._container).html(response);
                // window.history.pushState({
                //     // 'html': response,
                //     'html': $('body').html(),
                //     'pageTitle': menu.title,
                // }, '', menu.url);

                // The URL should be different than Ajax request
                // window.history.pushState({}, menu.title, menu.url + (menu.url.indexOf('?') >= 0 ? '&' : '?') + 'reload=true');
                // window.history.pushState({}, app.title, app.url + slug + ((app.url + slug).indexOf('?') >= 0 ? '&' : '?'));

                // Check if need to change url in the location bar
                if (push) {
                    window.history.pushState({}, app.title, app.url + slug);
                }

                // $('body').append($('.modal[role="dialog"]'));
                $('html, body').animate({ scrollTop: 0 }, 'fast');

                // Hide navigation on mobile devices
                $('.app-container.app-theme-white').removeClass('header-mobile-open');
                $('.hamburger.hamburger--elastic.mobile-toggle-nav').removeClass('is-active');

                app.load.modal();
            },
            error: function (status) {
                app.alert.err(status.statusText);
            },
            complete: function () {
                app.loading.hide(500);
                app.page.after_load();
                if (slug == 'subscription/mass-update') {
                    $('.left_side_folders_related').hide();
                    $('.left_side_types_related').show();
                } else {
                    if (slug != 'report') {
                        $('.left_side_folders_related').show();
                    }
                    $('.left_side_types_related').hide();
                }
            },
            cache: false,
        });
    }

    load(html, url = null, title = null, icon = null) {
        if (icon) {
            $(app.page._icon).attr('class', icon);
        }
        if (title) {
            $(app.page._title).text(title);
        }
        $(app.page._container).html(html);
        // window.history.pushState({
        //     'html': html,
        //     'pageTitle': title,
        // }, '', url);

        // The URL should be different than Ajax request
        window.history.pushState({}, title, url + '?reload=true');
        // $('body').append($('.modal[role="dialog"]'));
        $('html, body').animate({ scrollTop: 0 }, 'fast');
    }

    handle(url) {
        this.switch(this.get_slug(url), false);
    }

    get_slug(url = null) {
        if (url == null || typeof url !== 'string') {
            url = window.location.href;
        }
        return url.replace(app.url, '');
    }

    change(el) {
        el = $(el);

        if (!el.length) {
            return false;
        }

        let slug = this.get_slug(el.attr('href'));

        event && event.preventDefault();
        app.loading.show();

        $.ajax({
            type: 'GET',
            url: el.attr('href'),
            success: function (response) {
                $('#menu_container a.menu_toggle').removeClass('active');
                $('#menu_container a.menu_toggle[data-slug="' + slug + '"]').addClass('active');
                $(app.page._container).html(response);

                // Check if need to change url in the location bar
                window.history.pushState({}, app.title, el.attr('href'));

                $('html, body').animate({ scrollTop: 0 }, 'fast');
            },
            error: function (status) {
                app.alert.err(status.statusText);
            },
            complete: function () {
                app.loading.hide(500);
                app.page.after_load();
            },
            cache: false,
        });
    }

    after_load() {
        $('.dropdown.show [aria-expanded="true"]').attr('aria-expanded', false);
        $('.dropdown.show').removeClass('show');
        $('.dropdown-menu.show').removeClass('show');
        app.load.tooltip();
        app.page.set_product_related_entity();
    }

    set_product_related_entity() {
        if ($('#product-related-entity-name')) {
            const productRelatedEntityName = $('#product-related-entity-name').text();
            if ($('.product-related-entity-name')) {
                $('.product-related-entity-name').text(productRelatedEntityName);
            }
        }
        if ($('#product-related-entity')) {
            const productRelatedEntity = $('#product-related-entity').text();
            if ($('.product-related-entity-input')) {
                $('.product-related-entity-input').val(productRelatedEntity);
            }
        }
    }

    hit(url_or_el = null) {
        event && event.preventDefault();

        let anchor = undefined;
        let url = '';



        if (typeof url_or_el === 'string') {
            url = url_or_el;
        } else if (typeof url_or_el === 'object') {
            anchor = $(url_or_el);
        }

        if (!url) {
            if (anchor && anchor.attr('href')) {
                url = anchor.attr('href');
            } else {
                url = '/';
            }
        }

        $.ajax({
            type: 'GET',
            url: url,
            beforeSend: function (xhr) {
                app.loading.show();
                // app.loading.btn(ctl);
            },
            success: function (response) {
                $(app.page._container).html(response);
                window.history.pushState({}, app.title, url);
                $('html, body').animate({ scrollTop: 0 }, 'fast');
            },
            error: function (status) {
                app.alert.err(status.statusText);
            },
            complete: function () {
                app.loading.hide(500);
                app.page.after_load();
            },
            cache: false,
        });
    }
}

class clsApp_Loading {
    _default = '#loading_default';
    _css = {
        width: 'unset',
        top: '50%',
        left: '50%',
        'background-color': 'transparent',
        border: 'none',
    };

    constructor() {
    }

    show() {
        $.blockUI({
            message: $(app.loading._default).html(),
            css: app.loading._css,
        });
    }

    hide(waitTime) {
        if (waitTime) {
            lib.sleep(500).then(function () {
                $.unblockUI();
            });
        } else {
            $.unblockUI();
        }
    }

    block(ctl) {
        try {
            $(ctl).block({
                message: $(app.loading._default).html(),
                css: app.loading._css,
            });
        }
        catch (err) {
            console.log(err);
        }
    }

    unblock(ctl, waitTime) {
        if (waitTime) {
            lib.sleep(500).then(function () {
                $(ctl).unblock();
            });
        } else {
            $(ctl).unblock();
        }
    }

    btn(button) {
        // Toggle loading style of HTML button

        if (!button) return false;
        button = $(button);

        try {
            // Check if this is a loading button
            if (button.data('is-loading-btn')) {

                // Check if any icons added
                if (button.data('icon-class')) {
                    let iconClass = button.data('icon-class');
                    button.data('icon-class', null);

                    button.find('i').removeClass('fa-spinner fa-pulse fa-fw').addClass(iconClass);
                }
                button.data('is-loading-btn', null);
                button.prop('disabled', false);
            }
            else {
                button.prop('disabled', true);

                // Save the existing icon class
                let classes = button.find('i').attr('class');

                // Check if any icons are there to remove
                if (classes) {
                    classes = classes.split(' ');
                    let iconClass = classes[classes.length - 1];
                    button.data('icon-class', iconClass);

                    button.find('i').removeClass(iconClass).addClass('fa-spinner fa-pulse fa-fw');
                }

                button.data('is-loading-btn', true);
            }
        }
        catch (err) {
            return false;
        }
    }
}


class Application_Doable {

    constructor() {
    }

    select2_favicon_load(select) {
        // Get all result from opened select2 items
        let select2 = $('ul.select2-results__options');

        // Load favicon template
        let tpl_favicon_html = $('#tpl_select2_favicon').html();
        let tpl_plus_icon_html = $('#tpl_select2_plus_icon').html();

        // Loop through select2 result items
        if (select2.length > 0) {
            select2.children('li').each(function (item) {
                item = $(this);
                let text = item.data('text');
                if (!text) {
                    text = item.text();
                    item.data('text', text);
                }

                let data_id = 0;
                let data_url = '';
                let data_favicon = '';
                let data_type = '';
                let favicon_url = '';
                let plus_icon_flag = false;
                let output_type = '';
                let output_type_class = ' d-none';

                // Get selected item id or value
                let data_id_all = item.data('select2-id');
                if (data_id_all && data_id_all.length > 0) {
                    data_id_all = data_id_all.split('-');
                    if (data_id_all.length > 0) {
                        data_id = data_id_all[data_id_all.length - 1];
                    }

                    // Check if this is a new item
                    if (isNaN(data_id)) {
                        plus_icon_flag = true;
                    } else {
                        // Find HTML select option by value
                        let option = $(select).find('option[value="' + data_id + '"]');
                        if (option.length > 0) {
                            data_url = option.data('url');
                            data_favicon = option.data('favicon');
                            data_type = option.data('pricing_type');
                        }

                        // Set favicon url
                        if (data_favicon) {
                            favicon_url = data_favicon;
                        }

                        // Get favicon url
                        else if (data_url) {
                            if (data_url.substr(data_url.length - 1) == '/') {
                                favicon_url = data_url + 'favicon.ico';
                            } else {
                                favicon_url = data_url + '/favicon.ico';
                            }
                        }

                        // Check pricing_type
                        if (data_type) {

                            // Subscription
                            if (data_type == 1) {
                                output_type = 'Sub';
                                output_type_class = 'warning'
                            }

                            // Lifetime
                            else if (data_type == 3) {
                                output_type = 'LTD';
                                output_type_class = 'info'
                            }
                        }

                        // Set item html
                        item.html(tpl_favicon_html
                            .replace('__ITEM_NAME__', text)
                            .replace('__ITEM_IMG_SRC__', favicon_url)
                            .replace('d-none', '')
                            .replace('__ITEM_TYPE__', output_type)
                            .replace('__ITEM_TYPE_CLASS__', output_type_class)
                        );
                    }
                }

                // Check if this is a new item
                else if (typeof data_id_all === 'number') {
                    plus_icon_flag = true;

                }

                // Check if this is a new item
                if (plus_icon_flag) {
                    // Set item html
                    item.html(tpl_plus_icon_html
                        .replace('__ITEM_NAME__', text)
                    );
                }

            });

            select2.find('img').on('error', function (e) {
                e.target.src = app.url + 'assets/images/favicon.ico';
                e.target.style.display = '';
                // e.target.src='';
            });

            select2.find('img').on('load', function (e) {
                // e.target.src = app.url + 'assets/images/favicon.ico';
                e.target.style.display = '';
            });
        }
    }

    decode_html(html) {
        let txt = document.createElement('textarea');
        txt.innerHTML = html;
        let output = txt.value;
        txt.remove();
        return output;
    }

    download(filename, content, mime_type = 'text/plain', charset = 'utf-8') {
        var element = document.createElement('a');
        element.setAttribute('href', 'data:' + mime_type + ';charset=' + charset + ',' + encodeURIComponent(content));
        element.setAttribute('download', filename);

        element.style.display = 'none';
        document.body.appendChild(element);

        element.click();

        document.body.removeChild(element);
    }

    download_file(url, filename, btn) {
        if (!url) {
            throw new Error('Resource URL not provided! You need to provide one');
        }
        $.ajax({
            url: url,
            type: 'GET',
            beforeSend: function (xhr) {
                btn && app.loading.btn(btn);
                app.load.tooltip();
            },
            xhrFields: {
                // Get blob object in response
                responseType: 'blob',
            },
            success: function (blob) {
                const blobURL = URL.createObjectURL(blob);

                // Create a link to the file
                const element = document.createElement('a');
                element.href = blobURL;
                element.style = 'display: none';

                // Set the file name and download
                if (filename && filename.length) element.download = filename;
                document.body.appendChild(element);
                element.click();

                // Dispose of the element and release the blobURL
                document.body.removeChild(element);
                URL.revokeObjectURL(blobURL);
            },
            error: function (response) {
                app.alert.response(response);
            },
            complete: function () {
                btn && app.loading.btn(btn);
            },
            processData: false,
            contentType: false,
        });
    }

    billing_toggle_switch(el) {
        el = $(el);

        if (el.length) {
            let billing_type = $(el).is(':checked');
            let title = 0;

            if (billing_type) {
                title = lang('Calculate by date');
            } else {
                title = lang('Calculate by days');
            }

            $('.tooltip .tooltip-inner').text(title);
            // el.parent().attr('title', title);
            el.parent().attr('data-original-title', title);

            app.load.tooltip();
        }
    }

    copy(text_or_element) {
        let text = '';
        if (typeof text_or_element === 'string') {
            text = text_or_element;
        } else {
            text = $(text_or_element).data('clipboard-text');
        }

        if (!navigator.clipboard) {
            return lib.do._fallbackCopyTextToClipboard(text);
        }
        navigator.clipboard.writeText(text).then(function () {
            app.ui.copied();
            return true;
            // console.log('Async: Copying to clipboard was successful!');
        }, function (err) {
            console.error('Async: Could not copy text: ', err);
            return false;
        });
    }

    _fallbackCopyTextToClipboard(text) {
        let status = false;
        var textArea = document.createElement('textarea');
        textArea.value = text;

        // Avoid scrolling to bottom
        textArea.style.top = '0';
        textArea.style.left = '0';
        textArea.style.position = 'fixed';

        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();

        try {
            status = document.execCommand('copy');
            app.ui.copied();
            // console.log('Fallback: Copying text command was ' + status ? 'successful' : 'unsuccessful');
        } catch (err) {
            console.error('Fallback: Oops, unable to copy', err);
            status = false;
        }

        document.body.removeChild(textArea);
        return status;
    }

}

class clsLibrary_Validate {

    constructor() {
    }

    url(url) {
        if (typeof url !== 'string') {
            return false;
        }

        try {
            url = new URL(url);
        } catch (_) {
            return false;
        }

        return url.protocol === 'http:' || url.protocol === 'https:';
    }
}

class clsLibrary_Convert {

    constructor() {
    }

    base_url(url) {
        if (!lib.validate.url(url)) {
            return url;
        }

        let path_arr = url.split('/');

        if (typeof path_arr[0] !== 'undefined' && typeof path_arr[2] !== 'undefined') {
            return path_arr[0] + '//' + path_arr[2];
        } else {
            return url;
        }
    }
}


class clsApp_UI {

    constructor() {
    }

    btn_expand(container_selector) {
        let container = $(container_selector);
        let buttons = container.find('.btn_toggle');

        // Make sure all click events are unbinded
        buttons.unbind('click');

        // Handle click event
        buttons.click(function () {
            let container = $(this).closest('.btn_expand_container');

            // Hide all other containers
            $('.btn_expand_container').not(container).removeClass('show');
            container.toggleClass('show');
        });
    }

    btn_toggle() {
        let container = $('.toggle[data-toggle="toggle"]');

        if (!container.length) {
            return false;
        }

        // Check each container for toggle buttons
        container.each(function () {
            let container = $(this);

            // Handle display for the first time
            let checkbox = container.find('input[type=checkbox]')
            if (!checkbox.length) {
                return false;
            }

            // Display depending on the checkbox state
            if (checkbox.get(0).checked) {
                container.removeClass('off');
            } else {
                container.addClass('off');
            }

            // Make sure all click events are unbinded
            container.unbind('click');

            // Handle click event
            container.click(function () {
                let container = $(this);

                let checkbox = container.find('input[type=checkbox]')
                if (!checkbox.length) {
                    return false;
                }

                // Toggle
                if (checkbox.get(0).checked) {
                    checkbox.get(0).checked = false;
                    container.addClass('off');
                } else {
                    checkbox.get(0).checked = true;
                    container.removeClass('off');
                }
                $(checkbox.get(0)).change();
            });
        });
    }

    modal_favicon(url_or_el, img_selector) {
        let url;

        // Validate input url
        if (lib.validate.url(url_or_el)) {
            url = url_or_el;

            if (img_selector) {
                img_selector = $(img_selector);
            } else {
                return false;
            }
        }
        else {
            url_or_el = $(url_or_el);
            if (url_or_el.length == 1) {
                url = url_or_el.val();
                if (!lib.validate.url(url)) {
                    return false;
                }

                if (!img_selector) {
                    img_selector = url_or_el.closest('.modal-content').find('.modal-header img.favicon');
                }

            } else {
                return false;
            }
        }

        if (!img_selector.length) {
            return false;
        }

        let favicon_url = lib.convert.base_url(url) + '/favicon.ico';
        img_selector.attr('src', favicon_url);

        img_selector.on('load', function (e) {
            e.target.style.display = '';
        }).on('error', function (e) {
            let default_favicon_url = $('link[rel="icon"]').attr('href');
            if (default_favicon_url) {
                e.target.src = default_favicon_url;
            } else {
                e.target.style.display = 'none';
            }
        });
    }

    copied(el) {
        $('.tooltip .tooltip-inner').text('Copied');
    }

    btn_ripple(event) {
        let button = $('button.btn_ripple');

        if (!button.length) {
            return false;
        }

        // Check each button for toggle buttons
        button.each(function () {
            let button = $(this);

            // Make sure all click events are unbinded
            button.unbind('click');

            // Handle click event
            button.click(function (event) {
                const button = event.currentTarget;

                const circle = document.createElement('span');
                const diameter = Math.max(button.clientWidth, button.clientHeight);
                const radius = diameter / 2;

                // Calculate and set the position of the ripple
                circle.style.width = circle.style.height = `${diameter}px`;
                circle.style.left = `${event.pageX - button.getBoundingClientRect().left - radius}px`;
                circle.style.top = `${event.clientY - button.getBoundingClientRect().top - radius}px`;
                circle.classList.add('ripple');

                const ripple = button.getElementsByClassName('ripple')[0];

                if (ripple) {
                    ripple.remove();
                }

                button.appendChild(circle);
            });
        });
    }

}


(function () {
    new clsLibrary();
    app.init();

})();

function lang(str) {
    return str;
};

var randomizeArray = function (arg) {
    var array = arg.slice();
    var currentIndex = array.length,
        temporaryValue, randomIndex;

    while (0 !== currentIndex) {

        randomIndex = Math.floor(Math.random() * currentIndex);
        currentIndex -= 1;

        temporaryValue = array[currentIndex];
        array[currentIndex] = array[randomIndex];
        array[randomIndex] = temporaryValue;
    }

    return array;
};

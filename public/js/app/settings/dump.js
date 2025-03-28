// Instantiate this class
$(document).ready(function () {
    app.register('settings.dump', new clsApp_Settings_Dump);
});


class clsApp_Settings_Dump {
    e = {
        // Elements

    };
    d = {
        // Default values

    };
    o = {
        // Objects

        form_data: new FormData(),
    };
    c = {
        // Configuration

    };

    constructor() {
    }

    init() {
    }


    getExtension(filename) {
        var parts = filename.split('.');
        return parts[parts.length - 1];
    }

    read_file(file_input) {
        // console.log(file_input);

        if (file_input.files.length) {

            // Extension check
            if (app.settings.dump.getExtension(file_input.value) != 'csv') {
                $('#btn_settings_import_step1').attr('disabled', true);
                $('#btn_settings_import_step2').attr('disabled', true);
                $('#btn_settings_import_step3').attr('disabled', true);
                $('#settings_import_file').val('');
                app.alert.warn(lang('Invalid CSV file.'));
                return false;
            }

            app.settings.dump.o.form_data = new FormData();
            var reader = new FileReader();
            reader.readAsText(file_input.files[0], "UTF-8");
            reader.onload = function (evt) {
                let data = Papa.parse(evt.target.result, {
                    header: true,
                });

                app.settings.dump.o.form_data.append('_token', app.config.token);
                let count = 0;
                let length = data.data.length;
                data.data.forEach(function (item) {
                    let empty = true;
                    Object.keys(item).forEach(function (key) {
                        let val = item[key];

                        if (key !== '' && val !== '') {
                            empty = false;
                            app.settings.dump.o.form_data.append(key + '_' + count, val);
                        }

                    });
                    // app.settings.dump.o.form_data.append('image', data.image);

                    if (empty) {
                        length--;
                    } else {
                        count++;
                    }

                    if (length == count) {

                        if (count > 1000) {
                            $('#btn_settings_import_step1').attr('disabled', true);
                            $('#btn_settings_import_step2').attr('disabled', true);
                            $('#btn_settings_import_step3').attr('disabled', true);
                            $('#settings_import_file').val('');
                            app.alert.warn(lang('More than 1000 records cannot be imported.'));
                            return false;
                        }

                        // Set form data
                        app.settings.dump.o.form_data.append('count', count);
                        app.settings.dump.o.form_data.append('status', 1);
                        // $('#btn_settings_import_step1').attr('disabled', false);
                        app.settings.dump.validate_data(app.settings.dump.o.form_data);
                    } else {
                        $('#btn_settings_import_step1').attr('disabled', true);
                    }

                });

            }
        } else {
            $('#btn_settings_import_step1').attr('disabled', true);
            $('#btn_settings_import_step2').attr('disabled', true);
            $('#btn_settings_import_step3').attr('disabled', true);
            $('#settings_import_file').val('');
        }
    }

    validate_data(form_data) {
        $.ajax({
            url: app.url + 'settings/import/validate',
            type: 'POST',
            data: form_data,
            dataType: 'json',
            beforeSend: function (xhr) {
                // app.loading.btn(ctl);
                app.loading.btn('#btn_settings_import_step1');
            },
            success: function (response) {
                // console.log(response);
                app.loading.btn('#btn_settings_import_step1');

                if (response.status) {
                    $('#btn_settings_import_step1').attr('disabled', false);
                    $('#btn_settings_import_step2').attr('disabled', false);
                    $('#btn_settings_import_step3').attr('disabled', false);
                    // app.alert.succ('success');
                } else {
                    $('#btn_settings_import_step1').attr('disabled', true);
                    $('#btn_settings_import_step2').attr('disabled', true);
                    $('#btn_settings_import_step3').attr('disabled', true);
                    $('#settings_import_file').val('');
                    app.alert.validation(response.message);
                }
            },
            error: function (response) {
                app.alert.response(response);
                app.loading.btn('#btn_settings_import_step1');
                $('#btn_settings_import_step1').attr('disabled', true);
                $('#btn_settings_import_step2').attr('disabled', true);
                $('#btn_settings_import_step3').attr('disabled', true);
            },
            complete: function () {
                // app.loading.btn(ctl);
            },
            processData: false,
            contentType: false,
        });
    }

    insert_data() {
        if (!app.settings.dump.o.form_data) {
            app.alert.error("Invalid data");
            return false;
        }

        app.settings.dump.o.form_data.append('status', $('#settings_import_status').is(':checked') ? 1 : 0);

        $.ajax({
            url: app.url + 'settings/import/insert',
            type: 'POST',
            data: app.settings.dump.o.form_data,
            dataType: 'json',
            beforeSend: function (xhr) {
                // app.loading.btn(ctl);
                app.loading.btn('#btn_settings_import_step3');
            },
            success: function (response) {
                // console.log(response);

                if (response.status) {
                    app.loading.btn('#btn_settings_import_step3');
                    $('#btn_settings_import_step3').attr('disabled', true);
                    app.alert.succ(response.message);

                    setTimeout(function () {
                        // window.location.href = app.url + 'settings/import';
                        window.location.href = app.url;
                    }, 1000);
                } else {
                    app.alert.validation(response.message);
                }
            },
            error: function (response) {
                app.alert.response(response);
                app.loading.btn('#btn_settings_import_step3');
            },
            complete: function () {
                // app.loading.btn(ctl);
            },
            processData: false,
            contentType: false,
        });
    }


    csvJSON(csv) {

        var lines = csv.split("\n");

        var result = [];

        // NOTE: If your columns contain commas in their values, you'll need
        // to deal with those before doing the next step 
        // (you might convert them to &&& or something, then covert them back later)
        // jsfiddle showing the issue https://jsfiddle.net/
        var headers = lines[0].split(",");

        for (var i = 1; i < lines.length; i++) {

            var obj = {};
            var currentline = lines[i].split(",");

            for (var j = 0; j < headers.length; j++) {
                obj[headers[j]] = currentline[j];
            }

            result.push(obj);

        }

        return result; //JavaScript object
        // return JSON.stringify(result); //JSON
    }



    export_data(el) {
        $.ajax({
            url: app.url + 'settings/import/export',
            type: 'GET',
            dataType: 'json',
            beforeSend: function (xhr) {
                app.loading.btn(el);
            },
            success: function (response) {
                if (response.status) {
                    lib.do.download('subscriptions.csv', Papa.unparse(response.data));
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


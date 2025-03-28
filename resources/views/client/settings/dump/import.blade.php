@extends(request()->ajax() ? 'client/layouts/ajax' : 'client/layouts/settings')

@section('head')
@endsection

@section('content')
    <div class="main-card mb-3">
        <div class="mb-2">
            <button type="button" id="btn_settings_import_export" class="btn-shadow btn-wide btn-pill btn-hover-shine btn btn-primary mr-2" onclick="app.settings.dump.export_data(this);" data-toggle="tooltip" data-placement="left" title="@lang('Export the subs/Lifetime to your local')">
                @lang('Export')&nbsp;
                <i class="fa fa-file-export"></i>
            </button>

            {{-- <button type="button" id="btn_settings_import_download_sample" class="btn-shadow btn-wide btn-pill btn-hover-shine btn btn-primary" onclick="download_sample();" >
                @lang('Download Sample')&nbsp;
                <i class="fa fa-download"></i>
            </button> --}}
            <a href="{{ asset_ver('assets/subscription_sample.csv') }}" class="btn-shadow btn-wide btn-pill btn-hover-shine btn btn-primary mr-2" data-toggle="tooltip" data-placement="right" title="@lang('Download Sample of CSV for reference importing')" target="_blank">
                @lang('Sample')&nbsp;
                <i class="fa fa-download"></i>
            </a>
        </div>

        <div id="settings_import_smartwizard" class="smartwizard">
            <ul class="forms-wizard nav">
                <li class="nav-item">
                    <a href="#step-1" class="nav-link">
                        <em>1</em>
                        <span>@lang('Select file')</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#step-2" class="nav-link">
                        <em>2</em>
                        <span>@lang('Check file format')</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#step-3" class="nav-link">
                        <em>3</em>
                        <span>@lang('Import success')</span>
                    </a>
                </li>
            </ul>

            <div class="form-wizard-content tab-content">
                <div id="step-1" class="tab-pane">
                    <div class="container">
                        <div class="position-relative form-group">
                            <label for="settings_import_file" class="">@lang('Select a file')</label>
                            <input name="file" id="settings_import_file" type="file" class="form-control-file" onchange="app.settings.dump.read_file(this);" accept=".csv" data-toggle="tooltip" data-placement="left" title="@lang('Browser the CSV file')">
                            <br>
                            <small>
                                <button type="button" id="btn_settings_import_step1" class="btn-shadow btn-wide btn-pill btn-hover-shine btn btn-primary" disabled data-toggle="tooltip" data-placement="right" title="@lang('Click on Next after choosing the file')">
                                    @lang('Next')&nbsp;
                                    <i class="fa fa-arrow-right"></i>
                                </button>
                            </small>
                        </div>
                    </div>
                </div>

                <div id="step-2" class="tab-pane">
                    {{-- <div class="table-responsive card p-3">
                        <table id="tbl_settings_import_check" class="align-middle mb-0 table table-borderless table-striped table-hover text-center">
                            <thead>
                                <tr>
                                    <th>@lang('Folder')</th>
                                    <th>@lang('Brand')</th>
                                    <th>@lang('Type')</th>
                                    <th>@lang('Frequency')</th>
                                    <th>@lang('Cycle')</th>
                                    <th>@lang('Price')</th>
                                    <th>@lang('Currency')</th>
                                    <th>@lang('Recurring')</th>
                                    <th>@lang('Description')</th>
                                    <th>@lang('URL')</th>
                                    <th>@lang('Tags')</th>
                                    <th>@lang('Payment Date')</th>
                                    <th>@lang('Notes')</th>
                                    <th>@lang('Support Details')</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                </tr>
                            </tbody>
                        </table>
                    </div> --}}
                    <div class="no-results">
                        <div class="results-title">@lang('Data is in correct format')</div>
                        <div class="mt-3 mb-3"></div>
                        <div class="text-center">
                            <button id="btn_settings_import_step2" class="btn-shadow btn-wide btn-pill btn-hover-shine btn btn-primary" disabled data-toggle="tooltip" data-placement="right" title="@lang('Click on Next after the File Format is correct ')">
                                @lang('Next')&nbsp;
                                <i class="fa fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div id="step-3" class="tab-pane">
                    <div class="no-results">
                        {{-- <div class="swal2-icon swal2-success swal2-animate-success-icon">
                            <div class="swal2-success-circular-line-left" style="background-color: rgb(255, 255, 255);"></div>
                            <span class="swal2-success-line-tip"></span>
                            <span class="swal2-success-line-long"></span>
                            <div class="swal2-success-ring"></div>
                            <div class="swal2-success-fix" style="background-color: rgb(255, 255, 255);"></div>
                            <div class="swal2-success-circular-line-right" style="background-color: rgb(255, 255, 255);"></div>
                        </div> --}}
                        {{-- <div class="results-subtitle mt-4">Finished!</div> --}}
                        <div class="results-title">You arrived at the last step!</div>
                        <div class="mt-3 mb-3"></div>
                        <div class="text-center">
                            <button id="btn_settings_import_step3" class="btn-shadow btn-wide btn btn-success btn-lg" disabled data-toggle="tooltip" data-placement="right" title="@lang('Click on Submit to import')">
                                <i class="fa fa-check"></i>&nbsp;
                                @lang('Submit')
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="divider"></div>
        <div class="clearfix">
            <button type="button" id="reset-btn" class="btn-shadow float-left btn btn-link">Reset</button>
            <button type="button" id="next-btn" class="btn-shadow btn-wide float-right btn-pill btn-hover-shine btn btn-primary">Next</button>
            <button type="button" id="prev-btn" class="btn-shadow float-right btn-wide btn-pill mr-3 btn btn-outline-secondary">Previous</button>
        </div> --}}
    </div>



    {{-- <template id="tpl_settings_import_check_tbl_row">
        <tr>
            <td>__</td>
        </tr>
    </template> --}}

    <script>
        $(document).ready(function() {
            app.ui.btn_toggle();


            $('#settings_import_smartwizard').smartWizard({
                selected: 0,
                transitionEffect: 'fade',

                // Enable click
                anchorSettings: {
                    // anchorClickable: true, // Enable/Disable anchor navigation
                    enableAllAnchors: true, // Activates all anchors clickable all times
                    // markDoneStep: true, // Add done state on navigation
                    // markAllPreviousStepsAsDone: true, // When a step selected by url hash, all previous steps are marked done
                    // removeDoneStepOnNavigateBack: false, // While navigate back done step after active step will be cleared
                    // enableAnchorOnDoneStep: true // Enable/Disable the done steps navigation
                },

                toolbarSettings: {
                    toolbarPosition: 'none',
                }
            });


            $("#settings_import_smartwizard").on("showStep", function(e, anchorObject, stepNumber, stepDirection, stepPosition) {
                if (stepPosition == 'middle') {
                    show_tooltip();
                } else {
                    $('#settings_import_status_container').tooltip('hide');
                }
            });

            // $("#settings_import_smartwizard").on("showStep", function(e, anchorObject, stepNumber, stepDirection, stepPosition) {
            //     $("#prev-btn").removeClass('disabled');
            //     $("#next-btn").removeClass('disabled');
            //     if (stepPosition === 'first') {
            //         $("#prev-btn").addClass('disabled');
            //     } else if (stepPosition === 'last') {
            //         $("#next-btn").addClass('disabled');
            //     } else {
            //         $("#prev-btn").removeClass('disabled');
            //         $("#next-btn").removeClass('disabled');
            //     }
            // });

            // // External Button Events
            // $("#reset-btn").on("click", function() {
            //     // Reset wizard
            //     $('#settings_import_smartwizard').smartWizard("reset");
            //     return true;
            // });

            // $("#prev-btn").on("click", function() {
            //     // Navigate previous
            //     $('#settings_import_smartwizard').smartWizard("prev");
            //     return true;
            // });

            // $("#next-btn").on("click", function() {
            //     // Navigate next
            //     $('#settings_import_smartwizard').smartWizard("next");
            //     return true;
            // });


            $("#btn_settings_import_step1").on("click", function() {
                // Navigate next
                $('#settings_import_smartwizard').smartWizard("next");
                show_tooltip();
                return true;
            });

            $("#btn_settings_import_step2").on("click", function() {
                // Navigate next
                $('#settings_import_smartwizard').smartWizard("next");
                return true;
            });

            $("#btn_settings_import_step3").on("click", function() {
                // Navigate next
                app.settings.dump.insert_data();
            });



            $('#settings_import_smartwizard_btn_step1').click(function() {
                if ($('#settings_import_file')[0].files.length) {
                    $('#settings_import_smartwizard').smartWizard("next");
                }
            });




            $('#settings_import_smartwizard_btn_step2').click(function() {
                if ($('#settings_import_file')[0].files.length) {

                    let data = $('#settings_import_file')[0].files[0];
                    let form_data = new FormData();
                    form_data.append('_token', app.config.token);
                    form_data.append('image', data.image);

                    $.ajax({
                        url: app.url + 'settings/import/validate',
                        type: 'POST',
                        data: form_data,
                        dataType: 'json',
                        beforeSend: function(xhr) {
                            app.loading.btn(ctl);
                        },
                        success: function(response) {
                            if (response) {
                                app.page.switch('settings/contact');
                                app.alert.succ(response.message);
                                app.load.tooltip();
                            } else {
                                app.alert.warn(response.message);
                            }
                        },
                        error: function(response) {
                            app.alert.response(response);
                        },
                        complete: function() {
                            app.loading.btn(ctl);
                        },
                        processData: false,
                        contentType: false,
                    });




                    $('#settings_import_smartwizard').smartWizard("next");
                } else {
                    app.alert.warn("@lang('Select a file')");
                }
            });

        });

        function show_tooltip() {
            if ($('#settings_import_file')[0].files.length) {
                $('#settings_import_status_container').tooltip('show');
            }
        }
    </script>
@endsection

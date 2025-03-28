@extends(request()->ajax() ? 'admin/layouts/ajax' : 'admin/layouts/settings')

@section('head')
@endsection

@section('content')
    <div class="main-card mb-3 row">
        <div class="col-md-9">
            <div class="card hp-100">
                <h5 class="card-header">@lang('SMTP Info')</h5>
                <form action="{{ route('admin/settings/email/smtp/update') }}" id="settings_smtp_update_form" method="POST" class="card-body">
                    @csrf
                    <div class="row">
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="position-relative form-group">
                                <label for="settings_smtp_host" class="">@lang('Host')</label>
                                <input name="smtp_host" id="settings_smtp_host" value="{{ $data->smtp_host }}" maxlength="{{ len()->config->smtp_host }}" type="text" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="position-relative form-group">
                                <label for="settings_smtp_port" class="">@lang('Port')</label>
                                <input name="smtp_port" id="settings_smtp_port" value="{{ $data->smtp_port }}" min="0" max="65535" type="number" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="position-relative form-group">
                                <label for="settings_smtp_username" class="">@lang('Username')</label>
                                <input name="smtp_username" id="settings_smtp_username" value="{{ $data->smtp_username }}" maxlength="{{ len()->config->smtp_username }}" type="text" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="position-relative form-group">
                                <label for="settings_smtp_password" class="">@lang('Password')</label>
                                <input name="smtp_password" id="settings_smtp_password" value="{{ $data->smtp_password }}" maxlength="{{ len()->config->smtp_password }}" type="text" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="position-relative form-group">
                                <label for="settings_smtp_sender_name" class="">@lang('Sender Name')</label>
                                <input name="smtp_sender_name" id="settings_smtp_sender_name" value="{{ $data->smtp_sender_name }}" maxlength="{{ len()->config->smtp_sender_name }}" type="text" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="position-relative form-group">
                                <label for="settings_smtp_sender_email" class="">@lang('Sender Email')</label>
                                <input name="smtp_sender_email" id="settings_smtp_sender_email" value="{{ $data->smtp_sender_email }}" maxlength="{{ len()->config->smtp_sender_email }}" type="email" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="position-relative form-group">
                                <label for="settings_smtp_encryption" class="">@lang('Encryption')</label>
                                <select name="smtp_encryption" id="settings_smtp_encryption" class="form-control">
                                    <option value="" {{ $data->smtp_encryption == '' ? 'selected' : null }}>None</option>
                                    <option value="ssl" {{ $data->smtp_encryption == 'ssl' ? 'selected' : null }}>SSL</option>
                                    <option value="tls" {{ $data->smtp_encryption == 'tls' ? 'selected' : null }}>TLS</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <button onclick="app.settings.smtp.update(this);" type="submit" class="mb-2 mr-2 btn btn-primary btn-lg">
                        <i class="fa fa-save"></i>&nbsp;
                        @lang('Save')
                    </button>
                </form>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card hp-100">
                <h5 class="card-header">@lang('SMTP Test')</h5>
                {{-- <p class="badge badge-info">@lang('Please save SMTP details first')</p> --}}
                <form action="{{ route('admin/settings/email/smtp/test') }}" id="settings_smtp_test_form" method="POST" class="card-body">
                    @csrf
                    <div class="position-relative form-group">
                        <label for="settings_smtp_test_email" class="">@lang('Email')</label>
                        <input name="test_email" id="settings_smtp_test_email" maxlength="255" type="email" class="form-control" required>
                    </div>

                    <button onclick="app.settings.smtp.test(this);" type="submit" class="mb-2 mr-2 btn btn-primary btn-lg">
                        <i class="fa fa-paper-plane"></i>&nbsp;
                        @lang('Send')
                    </button>
                </form>
            </div>
        </div>

    </div>


    <br>

    <div class="main-card mb-3 row">
        <div class="col-md-6">
            <div class="card hp-100">
                <div class="card-header">
                    <h5>@lang('User Email Notifications')</h5>
                    <button type="button" class="btn-shadow btn btn-wide btn-outline-danger mr-3" onclick="app.settings.smtp.delete_all_logs();">@lang('Delete Logs')</button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive card border-0 p-3">
                        <table id="tbl_settings_email_logs" class="align-middle mb-0 table table-borderless table-striped table-hover text-center mb-4">
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card hp-100">
                <h5 class="card-header">@lang('Cron Logs')</h5>
                <div class="card-body p-0">
                    <div class="table-responsive card border-0 p-3">
                        <table id="tbl_settings_admin_notifications" class="align-middle mb-0 table table-borderless table-striped table-hover text-center mb-4">
                            <thead>
                                <tr>
                                    <th class="p-3">@lang('Filename')</th>
                                    <th class="p-3">@lang('Actions')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (lib()->cron->get_all_log_files() ?? [] as $val)
                                    <tr data-id="{{ $val }}">
                                        <td>{{ basename($val) }}</td>
                                        <td>
                                            <button class="mr-2 btn btn-outline-primary btn-sm" onclick="app.settings.cron.log_download(this);">
                                                <i class="fa fa-download"></i>
                                            </button>
                                            <button class="mr-2 btn btn-outline-danger btn-sm" onclick="app.settings.cron.log_delete(this);">
                                                <i class="fa fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <script>
        $(document).ready(function() {
            // app.settings.smtp.o.log.user_table = $('#tbl_settings_user_notifications').DataTable({
            //     // processing: true,
            //     // // serverSide: true,
            //     // dom: 'Bfrtip',
            //     // select: true,
            //     // buttons: [{
            //     //     text: 'Get selected data',
            //     //     action: function() {
            //     //         var count = table.rows({
            //     //             selected: true
            //     //         }).count();

            //     //         events.prepend('<div>' + count + ' row(s) selected</div>');
            //     //     }
            //     // }],
            //     ajax: "{{ route('admin/settings/email/logs/user') }}",

            //     "columnDefs": [{
            //             "targets": [0],
            //             "visible": false,
            //             "searchable": false,
            //         },
            //         {
            //             // Action column template
            //             "targets": [5],
            //             // "visible": false,
            //             "defaultContent": `
        //                 <button class="btn btn-outline-primary btn-sm" onclick="app.settings.smtp.log_view(this);">
        //                     <i class="fa fa-eye"></i>
        //                 </button>
        //             `,
            //         }
            //     ],

            //     "columns": [{
            //             "data": "created_at",
            //         },
            //         {
            //             "data": "subject",
            //         },
            //         {
            //             "data": "to_name",
            //         },
            //         {
            //             "data": "status",
            //             render: function(data, type, row) {
            //                 switch (data) {
            //                     case 0:
            //                         return '<span class="badge badge-info">Pending</span>';
            //                         break;
            //                     case 1:
            //                         return '<span class="badge badge-success">Sent</span>';
            //                         break;
            //                     case 2:
            //                         return '<span class="badge badge-danger">Failed</span>';
            //                         break;
            //                     default:
            //                         return '<span class="badge badge-dark">Unknown</span>';
            //                 }
            //             },
            //         },
            //         {
            //             "data": "created_at",
            //             render: function(data, type, row) {
            //                 if (data) {
            //                     let date = moment(data);
            //                     // return '<time class="timeago" datetime="'+date.toISOString()+'">'+date.format('MMMM Do YYYY, hh:mm:ss a')+'</time>';
            //                     return '<time class="timeago" datetime="' + date.toISOString() + '">' + data + '</time>';
            //                 }
            //             },
            //         },
            //     ],

            //     // Order by datetime descending
            //     "order": [
            //         [0, "desc"]
            //     ],
            //     "drawCallback": function(settings) {
            //         // alert( 'DataTables has redrawn the table' );
            //         $("time.timeago").timeago();
            //     },
            // });

            $('#tbl_settings_admin_notifications').DataTable({});

            // app.settings.smtp.o.log.user_table
            app.settings.smtp.o.log.user_table = $('#tbl_settings_email_logs').DataTable({
                processing: true,
                serverSide: true,
                // stateSave: true,
                ajax: {
                    type: 'POST',
                    url: "{{ route('admin/settings/email/logs/user') }}",
                    data: {
                        _token: app.config.token,
                        timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
                    },
                    beforeSend: function() {
                        // Abort previous ajax request
                        if (app.settings.smtp.o.log.user_table && app.settings.smtp.o.log.user_table.hasOwnProperty('settings')) {
                            app.settings.smtp.o.log.user_table.settings()[0].jqXHR.abort();
                        }
                    }
                },
                order: [
                    [0, 'desc'],
                ],
                aLengthMenu: [
                    [5, 10, 25, 50, 100],
                    [5, 10, 25, 50, 100],
                ],
                iDisplayLength: 10,
                // responsive: true,

                // Customize table row column
                fnCreatedRow: function(nRow, aData, iDataIndex) {
                    $(nRow).attr('data-id', aData.id);
                },
                columns: [{
                        data: 'id',
                        visible: false,
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'to_name',
                        title: lang('Name'),
                    },
                    {
                        data: 'subject',
                        title: lang('Subject'),
                    },
                    {
                        data: 'body',
                        visible: false,
                    },
                    {
                        data: 'column_status',
                        title: lang('Status'),
                    },
                    {
                        data: 'created_at',
                        title: lang('Date'),
                    },
                    {
                        data: 'column_action',
                        title: lang('Actions'),
                        width: '100px',
                        orderable: false,
                        searchable: false,
                    },
                ],
            });







        });
    </script>
@endsection

@extends(request()->ajax() ? 'client/layouts/ajax' : 'client/layouts/settings')

@section('head')
@endsection

@section('content')
    <div class="main-card mb-3 row">

        <div class="col-lg-12 mb-4">
            <div class="card hp-100">
                <div class="card-header">
                    <h5>@lang('Backup and Restore')</h5>
                    <button id="settings_recovery_backup_btn" class="btn btn-primary mt-2 ml-auto" type="button" onclick="app.settings.recovery.backup.start(this);" data-toggle="tooltip" data-placement="right" title="@lang('Take a new backup')">
                        <i class="fa fa-hdd"></i>&nbsp;
                        @lang('Backup')
                    </button>
                    <button onclick="app.settings.recovery.reset.start(this);" type="button" class="btn btn-danger btn_default_style ml-2 mt-2" data-toggle="tooltip" data-placement="right" title="@lang('Click on RESET will delete Subs/Lifetime, tags, payment mode, contacts, folder')">
                        <i class="fa fa-undo"></i>&nbsp;
                        @lang('Reset')
                    </button>
                </div>
                <div class="card-body">
                    @if ($data['recovery_in_progress'])
                        <div>
                            <div class="alert alert-info d-inline-block" role="alert">
                                {{ $data['recovery_progress_message'] }}
                            </div>
                        </div>
                    @elseif (!empty($data['backup']['status']))

                            {{-- All okay --}}
                        @if ($data['backup']['status'] == 'okay')
                            <div>
                                <div class="alert alert-success d-inline-block" role="alert">
                                    @lang('We have found the details of your last backup.')
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-9 col-lg-6 col-xl-4 row">
                                    <div class="col-md-12 my-1">
                                        <p class="font-weight-bold mb-0">
                                            @lang('Backup date:')
                                            <span class="badge badge-primary">
                                                {{ $data['backup']['date'] }}
                                            </span>
                                        </p>
                                    </div>
                                    <div class="col-md-12 my-1">
                                        <p class="font-weight-bold mb-0">
                                            @lang('Total Subscriptions:')
                                            <span class="badge badge-primary">
                                                {{ $data['subscription']['count'] }}
                                            </span>
                                        </p>
                                    </div>
                                    <div class="col-md-12 my-1">
                                        <p class="font-weight-bold mb-0">
                                            @lang('Total Attachments:')
                                            <span class="badge badge-primary">
                                                {{ $data['subscription_attachment']['count'] }}
                                            </span>
                                        </p>
                                    </div>
                                    <div class="col-md-12 my-1">
                                        <p class="font-weight-bold mb-0">
                                            @lang('Total Folders:')
                                            <span class="badge badge-primary">
                                                {{ $data['folder']['count'] }}
                                            </span>
                                        </p>
                                    </div>
                                    <div class="col-md-12 my-1">
                                        <p class="font-weight-bold mb-0">
                                            @lang('Total Payment Methods:')
                                            <span class="badge badge-primary">
                                                {{ $data['payment_method']['count'] }}
                                            </span>
                                        </p>
                                    </div>
                                    <div class="col-md-12 my-1">
                                        <p class="font-weight-bold mb-0">
                                            @lang('Total Contacts:')
                                            <span class="badge badge-primary">
                                                {{ $data['contact']['count'] }}
                                            </span>
                                        </p>
                                    </div>
                                    <div class="col-md-12 my-1">
                                        <p class="font-weight-bold mb-0">
                                            @lang('Total Alert Profiles:')
                                            <span class="badge badge-primary">
                                                {{ $data['alert_profile']['count'] }}
                                            </span>
                                        </p>
                                    </div>
                                    <div class="col-md-12 my-1">
                                        <p class="font-weight-bold mb-0">
                                            @lang('Total Token Keys:')
                                            <span class="badge badge-primary">
                                                {{ $data['api_token']['count'] }}
                                            </span>
                                        </p>
                                    </div>
                                    <div class="col-md-12 my-1">
                                        <p class="font-weight-bold mb-0">
                                            @lang('Total Webhooks:')
                                            <span class="badge badge-primary">
                                                {{ $data['webhook']['count'] }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-4 col-xl-3">
                                    <div class="position-relative form-group">
                                        <label class="">@lang('Profile picture')</label>
                                        <br>
                                        <img class="img-thumbnail" src="{{ img_url($data['avatar']['path']) }}" alt="">
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-primary mt-3" type="button" onclick="app.settings.recovery.restore.start(this);" data-toggle="tooltip" data-placement="bottom" title="@lang('Click here to restore')">
                                <i class="fa fa-undo"></i>&nbsp;
                                @lang('Restore this backup')
                            </button>
                            <button class="btn btn-danger mt-3 ml-2 btn_default_style" type="button" onclick="app.settings.recovery.backup.delete(this);" data-toggle="tooltip" data-placement="right" title="@lang('Delete this backup')">
                                <i class="fa fa-trash"></i>&nbsp;
                                @lang('Delete')
                            </button>

                            {{-- Corrupted files --}}
                        @elseif ($data['backup']['status'] == 'corrupted')
                            <div>
                                <div class="alert alert-danger d-inline-block" role="alert">
                                    @lang('Your last backup files are corrupted, please take a new backup.')
                                </div>
                            </div>

                            {{-- Not found --}}
                        @else
                            <div>
                                <div class="alert alert-warning d-inline-block" role="alert">
                                    @lang('No backup found, please take a backup first.')
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>

    </div>



    <script>
        $(document).ready(function() {
            app.settings.recovery.backup.c.token = '{{ Str::random(32) }}';
            app.settings.recovery.restore.c.token = '{{ Str::random(32) }}';
            app.settings.recovery.reset.c.token = '{{ Str::random(32) }}';

            app.settings.recovery.backup.c.commands = JSON.parse('@json(lib()->settings->recovery->backup_commands)');
            app.settings.recovery.restore.c.commands = JSON.parse('@json(lib()->settings->recovery->restore_commands)');
            app.settings.recovery.reset.c.commands = JSON.parse('@json(lib()->settings->recovery->reset_commands)');
        });
    </script>
@endsection

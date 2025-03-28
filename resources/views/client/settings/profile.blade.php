@extends(request()->ajax() ? 'client/layouts/ajax' : 'client/layouts/settings')

@section('head')
@endsection

@section('content')
    <div class="main-card mb-3">
        <div class="row">
            <div class="col-sm-6" style="padding-right: 5%;">
                <div class="card hp-100">
                    <h5 class="card-header">@lang('Profile Picture')</h5>
                    <div class="card-body">
                        <div class="mx-auto" style="max-width: 200px; max-height: 200px; min-height: 200px;" data-toggle="tooltip" data-placement="left" title="@lang('Add/Replace your Profile picture')">
                            <input type="file" class="filepond" id="main_add_picture_file" name="picture" accept="image/*" data-size="200x200" style="display: none;">
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="col-sm-6" style="padding-left: 5%; position: relative; min-height: 150px;">
                <div class="card hp-100">
                    <h5 class="card-header">@lang('Linked Social Profiles')</h5>
                    <div class="card-body">
                        <div class="row w-75 pt-5" style="position: absolute; bottom: 20px;">
                            <div class="col-md-4">
                                <button class="mb-2 mr-2 btn btn-primary btn-block btn-sm">Unlink</button>
                            </div>
                            <div class="col-md-4">
                                <button class="mb-2 mr-2 btn btn-primary btn-block btn-sm">Unlink</button>
                            </div>
                            <div class="col-md-4">
                                <button class="mb-2 mr-2 btn-transition btn btn-outline-primary btn-block btn-sm">Connect</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
        <!-- <div class="mt-3 mb-3 pt-5 pb-5"></div> -->
        <div class="mt-3 mb-3 pt-3 pb-3"></div>
        <div class="row">
            <div class="col-sm-6" style="padding-right: 5%;">
                <div class="card hp-100">
                    <h5 class="card-header">@lang('Basic Info')</h5>
                    <form action="{{ route('app/settings/profile/update/info') }}" id="frm_settings_profile_info" method="POST" class="card-body">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="settings_profile_first_name" class="">@lang('First Name')</label>
                                    <input name="first_name" id="settings_profile_first_name" value="{{ lib()->user->me->first_name }}" type="text" class="form-control" required data-toggle="tooltip" data-placement="left" title="@lang('Add/Edit your First Name')">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="settings_profile_last_name" class="">@lang('Last Name')</label>
                                    <input name="last_name" id="settings_profile_last_name" value="{{ lib()->user->me->last_name }}" type="text" class="form-control" required data-toggle="tooltip" data-placement="right" title="@lang('Add/Edit your Last Name')">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="settings_profile_email" class="">@lang('Email')</label>
                                    <input name="email" id="settings_profile_email" value="{{ lib()->user->me->email }}" type="email" class="form-control" required data-toggle="tooltip" data-placement="left" title="@lang('Add/Edit your Email ID')" {{ Auth::user()->team_user_id > 0 ? 'readonly' : null }}>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="settings_profile_phone" class="">@lang('Phone Number')</label>
                                    <input name="phone" id="settings_profile_phone" value="{{ lib()->user->me->phone }}" type="text" class="form-control" data-toggle="tooltip" data-placement="right" title="@lang('Add/Edit your Phone Number')">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="settings_profile_company_name" class="">@lang('Company Name')</label>
                                    <input name="company_name" id="settings_profile_company_name" value="{{ lib()->user->me->company_name }}" type="text" class="form-control" data-toggle="tooltip" data-placement="right" title="@lang('Add/Edit your company Name')">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="settings_profile_facebook_username" class="">
                                        <span>@lang('Facebook Username')</span>

                                        {{-- Check if facebook username exists --}}
                                        @if (!empty(lib()->user->me->facebook_username))
                                            <span class="ml-2">
                                                (<a href="{{ url('https://www.facebook.com/' . lib()->user->me->facebook_username) }}" class="text-primary link-primary font-weight-bold" target="_blank">@lang('View')</a>)
                                            </span>
                                        @endif

                                    </label>
                                    <input name="facebook_username" id="settings_profile_facebook_username" value="{{ lib()->user->me->facebook_username }}" type="text" class="form-control" data-toggle="tooltip" data-placement="right" title="@lang('Add/Edit your Facebook Username')">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="settings_profile_country" class="">@lang('Country')</label>
                                    <select name="country" id="settings_profile_country" class="form-control" required data-toggle="tooltip" data-placement="right" title="@lang('Select the Country')">
                                        <option selected="" disabled="" value="" style="display: none;">@lang('Select')</option>
                                        @foreach (lib()->config->country as $val)
                                            <option value="{{ $val['isocode'] }}" {{ lib()->user->me->country == $val['isocode'] ? 'selected' : null }}>{{ $val['shortname'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <button onclick="app.settings.profile.update_info(this);" type="submit" class="mb-2 mr-2 btn btn-primary btn-lg" data-toggle="tooltip" data-placement="right" title="@lang('Save Your Changes')">
                                    <i class="fa fa-save"></i>&nbsp;
                                    @lang('Save')
                                </button>
                            </div>

                            @if (!in_array(Auth::user()->users_plan->plan_id, TEAM_PLAN_ALL_ID))
                                <div class="col-md-6 text-right">
                                    <button onclick="app.settings.profile.reset.start(this);" type="button" class="mb-2 mr-2 btn btn-danger btn-lg" data-toggle="tooltip" data-placement="right" title="@lang('Delete your account')">
                                        <i class="fa fa-trash-alt"></i>&nbsp;
                                        @lang('Delete Account')
                                    </button>
                                </div>
                            @endif

                        </div>
                    </form>
                </div>
            </div>
            <div class="col-sm-6" style="padding-left: 5%;">
                <div class="card hp-100">
                    <h5 class="card-header">@lang('Change Password')</h5>
                    <form action="{{ route('app/settings/profile/update/password') }}" id="frm_settings_profile_password" method="POST" class="card-body">
                        @csrf
                        {{-- <input type="hidden" name="username" value="{{ lib()->user->me->email }}" autocomplete="username"> --}}
                        <input hidden type="text" autocomplete="username" value="{{ lib()->user->me->email }}">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="main_add_password" class="">@lang('Password')</label>
                                    <input name="password" id="main_add_password" type="password" autocomplete="new-password" class="form-control" required data-toggle="tooltip" data-placement="bottom" title="@lang('Enter New Password')">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="main_add_password_confirmation" class="">@lang('Confirm Password')</label>
                                    <input name="password_confirmation" id="main_add_password_confirmation" type="password" autocomplete="new-password" class="form-control" required data-toggle="tooltip" data-placement="bottom" title="@lang('Confrim New Password')">
                                </div>
                            </div>
                        </div>
                        <button type="submit" onclick="app.settings.profile.update_password(this);" class="mb-2 mr-2 btn btn-primary btn-lg" style="position: absolute; bottom: 20px;" data-toggle="tooltip" data-placement="right" title="@lang('Save your Password')">
                            <i class="fa fa-save"></i>&nbsp;
                            @lang('Save')
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <script>
        $(document).ready(function() {
            app.settings.recovery.reset.c.token = '{{ Str::random(32) }}';
            app.settings.recovery.reset.c.commands = JSON.parse('@json(lib()->settings->recovery->reset_commands)');

            app.settings.profile.reset.c.token = '{{ Str::random(32) }}';
            app.settings.profile.reset.c.commands = JSON.parse('@json(lib()->settings->recovery->reset_commands)');

            $('.filepond').filepond({
                labelIdle: `Drag & Drop your picture or <span class="filepond--label-action">Browse</span>`,
                imagePreviewHeight: 170,
                imageCropAspectRatio: '1:1',
                imageResizeTargetWidth: 200,
                imageResizeTargetHeight: 200,
                stylePanelLayout: 'compact circle',
                styleLoadIndicatorPosition: 'center bottom',
                styleProgressIndicatorPosition: 'center bottom',
                styleButtonRemoveItemPosition: 'center bottom',
                styleButtonProcessItemPosition: 'center bottom',
                files: [{
                    source: btoa("{{ lib()->user->me->image }}"),
                    options: {
                        type: 'local'
                    }
                }],
                onprocessfile: function(error, file) {
                    console.log(error, file);
                },
            });

            FilePond.setOptions({
                server: {
                    process: (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {

                        // fieldName is the name of the input field
                        // file is the actual file object to send
                        let form_data = new FormData();
                        form_data.append(fieldName, file, file.name);
                        form_data.append('_token', app.config.token);

                        let request = new XMLHttpRequest();
                        request.open('POST', "{{ route('app/settings/profile/upload') }}");

                        // Should call the progress method to update the progress to 100% before calling load
                        // Setting computable to false switches the loading indicator to infinite mode
                        request.upload.onprogress = (e) => {
                            progress(e.lengthComputable, e.loaded, e.total);
                        };

                        // Should call the load method when done and pass the returned server file id
                        // this server file id is then used later on when reverting or restoring a file
                        // so your server knows which file to return without exposing that info to the client
                        request.onload = function() {
                            if (request.status >= 200 && request.status < 300) {
                                // the load method accepts either a string (id) or an object
                                load(request.responseText);
                            } else {
                                // Can call the error method if something is wrong, should exit after
                                error('oh no');
                            }
                        };

                        request.send(form_data);

                        // Should expose an abort method so the request can be canceled
                        return {
                            abort: () => {
                                // This function is entered if the user has tapped the cancel button
                                request.abort();

                                // Let FilePond know the request has been canceled
                                abort();
                            }
                        };
                    },
                    url: "{{ url('/') }}",
                    load: '/storage/',
                }
            });
        });
    </script>
@endsection

@extends(request()->ajax() ? 'client/layouts/ajax' : 'client/layouts/settings')

@section('head')
@endsection

@section('content')
    <style>
        .plan_limit {
            height: 34px;
        }

        .plan_limit .progress-bar {
            text-align: left;
            padding-left: 50px;
        }

        .plan_limit_text {
            line-height: 34px;
        }
    </style>

    <div class="main-card mb-3">
        <div class="row">
            <div class="col-md-4 col-lg-3" style="padding-right: 5%;">
                <h5>@lang('Your Current Plan')</h5>
                <h4 class="font-weight-bold">{{ lib()->user->plan->description ?? null }}</h4>
                {{-- <br>
                <button class="mb-2 mr-2 btn-transition btn btn-outline-primary btn-block btn-lg" onclick="app.alert.info('This is a free plan.');">
                    <h5 class="p-0 m-0">Free</h5>
                </button> --}}

                @if (lib()->user->plan->is_upgradable && empty(Auth::user()->team_user_id))
                    <br>
                    <a href="https://subshero.com/pricing/" class="mb-2 mr-2 btn-transition btn btn-primary btn-block btn-lg" onclick="app.alert.info('Redirecting you to the plan page...');" data-toggle="tooltip" data-placement="bottom" title="@lang('Click to upgrade to Pro Plan')">
                        <h5 class="p-0 m-0">@lang('Upgrade')</h5>
                    </a>
                @endif

                @if (in_array(lib()->user->plan->id, array_merge([FREE_PLAN_ID], LTD_PLAN_ALL_EXCEPT_TEAM_ID)))
                    <br>
                    <button class="mb-2 mr-2 btn btn-outline-primary btn-block btn-lg" type="button" data-target="#modal_settings_billing_coupon_apply" data-toggle="modal">
                        <i class="fa fa-tag"></i>&nbsp;
                        @lang('Apply Coupon')
                    </button>
                @endif
            </div>
            <div class="col-md-8 col-lg-9" style="padding-left: 5%;">
                <h5>@lang('Plan Limits')</h5>
                <br>
                <div class="row">
                    <div class="col-8">
                        <div class="mb-3 progress plan_limit">
                            <div class="progress-bar" role="progressbar" aria-valuenow="{{ $billing->subscription_percent }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $billing->subscription_percent }}%;">
                                <h6 class="text-dark p-0 m-0">@lang('Subscriptions')</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <h6 class="font-weight-bold p-0 m-0 plan_limit_text">{{ "$billing->subscription_total/$billing->subscription_limit" }}</h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="mb-3 progress plan_limit">
                            <div class="progress-bar" role="progressbar" aria-valuenow="{{ $billing->folder_percent }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $billing->folder_percent }}%;">
                                <h6 class="text-dark p-0 m-0">@lang('Folders')</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <h6 class="font-weight-bold p-0 m-0 plan_limit_text">{{ "$billing->folder_total/$billing->folder_limit" }}</h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="mb-3 progress plan_limit">
                            <div class="progress-bar" role="progressbar" aria-valuenow="{{ $billing->tag_percent }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $billing->tag_percent }}%;">
                                <h6 class="text-dark p-0 m-0">@lang('Tags')</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <h6 class="font-weight-bold p-0 m-0 plan_limit_text">{{ "$billing->tag_total/$billing->tag_limit" }}</h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="mb-3 progress plan_limit">
                            <div class="progress-bar" role="progressbar" aria-valuenow="{{ $billing->payment_method_percent }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $billing->payment_method_percent }}%;">
                                <h6 class="text-dark p-0 m-0">@lang('Payment Methods')</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <h6 class="font-weight-bold p-0 m-0 plan_limit_text">{{ "$billing->payment_method_total/$billing->payment_method_limit" }}</h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="mb-3 progress plan_limit">
                            <div class="progress-bar" role="progressbar" aria-valuenow="{{ $billing->contact_percent }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $billing->contact_percent }}%;">
                                <h6 class="text-dark p-0 m-0">@lang('Contacts')</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <h6 class="font-weight-bold p-0 m-0 plan_limit_text">{{ "$billing->contact_total/$billing->contact_limit" }}</h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="mb-3 progress plan_limit">
                            <div class="progress-bar" role="progressbar" aria-valuenow="{{ $billing->alert_profile_percent }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $billing->alert_profile_percent }}%;">
                                <h6 class="text-dark p-0 m-0">@lang('Alert Profiles')</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <h6 class="font-weight-bold p-0 m-0 plan_limit_text">{{ "$billing->alert_profile_total/$billing->alert_profile_limit" }}</h6>
                    </div>
                </div>

                {{-- Check if user has Pro or Team plan --}}
                @if (in_array(Auth::user()->users_plan->plan_id, array_merge(PRO_PLAN_ALL_ID, TEAM_PLAN_ALL_ID)))
                    <div class="row">
                        <div class="col-8">
                            <div class="mb-3 progress plan_limit">
                                <div class="progress-bar" role="progressbar" aria-valuenow="{{ $billing->webhook_percent }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $billing->webhook_percent }}%;">
                                    <h6 class="text-dark p-0 m-0">@lang('Webhooks')</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <h6 class="font-weight-bold p-0 m-0 plan_limit_text">{{ "$billing->webhook_total/$billing->webhook_limit" }}</h6>
                        </div>
                    </div>
                @endif

                {{-- Check if user has Team plan --}}
                @if (in_array(Auth::user()->users_plan->plan_id, TEAM_PLAN_ALL_ID))
                    <div class="row">
                        <div class="col-8">
                            <div class="mb-3 progress plan_limit">
                                <div class="progress-bar" role="progressbar" aria-valuenow="{{ $billing->team_percent }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $billing->team_percent }}%;">
                                    <h6 class="text-dark p-0 m-0">@lang('Team Members')</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <h6 class="font-weight-bold p-0 m-0 plan_limit_text">{{ "$billing->team_total/$billing->team_limit" }}</h6>
                        </div>
                    </div>
                @endif

                <div class="row">
                    <div class="col-8">
                        <div class="mb-3 progress plan_limit">
                            <div class="progress-bar" role="progressbar" aria-valuenow="{{ $billing->storage_percent }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $billing->storage_percent }}%;">
                                <h6 class="text-dark p-0 m-0">@lang('Storage')</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <h6 class="font-weight-bold p-0 m-0 plan_limit_text">{{ lib()->do->get_filesize($billing->storage_total) . '/' . lib()->do->get_filesize($billing->storage_limit) }}</h6>
                    </div>
                </div>
            </div>
        </div>

        {{-- <br>
        <br>
        <h5>@lang('Privacy & Cookies')</h5> --}}
    </div>
@endsection

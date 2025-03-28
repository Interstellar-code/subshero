@extends(request()->ajax() ? 'admin/layouts/ajax' : 'admin/layouts/settings')

@section('head')
@endsection

@section('content')
    <div class="main-card mb-3">
        <div class="card hp-100">
            <div class="card-header justify-content-between">
                <h5>@lang('Application Update')</h5>
                <div>
                    <button class="mr-2 btn-transition btn btn-outline-info" onclick="app.settings.update.check();">
                        <i class="fa fa-check"></i>&nbsp;
                        @lang('Check for update')
                    </button>
                    <button class="mr-2 btn-transition btn btn-outline-primary" onclick="app.settings.update.handle();">
                        <i class="fa fa-play-circle"></i>&nbsp;
                        @lang('Start update')
                    </button>
                    {{-- <button class="mr-2 btn-transition btn btn-outline-danger">
                        <i class="fa fa-times"></i>&nbsp;
                        @lang('Stop update')
                    </button> --}}
                </div>
            </div>
            <div class="card-body">

                <div id="settings_update_timeline_container" class="vertical-time-simple vertical-without-time vertical-timeline vertical-timeline--animate vertical-timeline--one-column">
                    <div class="vertical-timeline-item dot-info vertical-timeline-element">
                        <div>
                            <span class="vertical-timeline-element-icon bounce-in"></span>
                            <div class="vertical-timeline-element-content bounce-in">
                                <h4 class="timeline-title">@lang('Application version: '){{ $version_number }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="card-footer"></div> --}}
        </div>
    </div>



    <template id="tpl_settings_update_timeline_success">
        <div class="vertical-timeline-item dot-success vertical-timeline-element">
            <div>
                <span class="vertical-timeline-element-icon bounce-in"></span>
                <div class="vertical-timeline-element-content bounce-in">
                    <h4 class="timeline-title">__TITLE__</h4>
                </div>
            </div>
        </div>
    </template>

    <template id="tpl_settings_update_timeline_info">
        <div class="vertical-timeline-item dot-info vertical-timeline-element">
            <div>
                <span class="vertical-timeline-element-icon bounce-in"></span>
                <div class="vertical-timeline-element-content bounce-in">
                    <h4 class="timeline-title">__TITLE__</h4>
                </div>
            </div>
        </div>
    </template>

    <template id="tpl_settings_update_timeline_warning">
        <div class="vertical-timeline-item dot-warning vertical-timeline-element">
            <div>
                <span class="vertical-timeline-element-icon bounce-in"></span>
                <div class="vertical-timeline-element-content bounce-in">
                    <h4 class="timeline-title">__TITLE__</h4>
                </div>
            </div>
        </div>
    </template>

    <template id="tpl_settings_update_timeline_error">
        <div class="vertical-timeline-item dot-danger vertical-timeline-element">
            <div>
                <span class="vertical-timeline-element-icon bounce-in"></span>
                <div class="vertical-timeline-element-content bounce-in">
                    <h4 class="timeline-title">__TITLE__</h4>
                </div>
            </div>
        </div>
    </template>


    <script>
        $(document).ready(function() {
            app.settings.update.c.all_command = JSON.parse('@json($all_command)');
        });
    </script>

@endsection

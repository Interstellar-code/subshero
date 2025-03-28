@push('modal')
    <div id="modal_settings_recovery_backup" class="modal fade recovery_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Backup')</h5>
                    {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button> --}}
                </div>
                <div class="modal-body custom_scrollbar">

                    <div id="settings_recovery_backup_timeline_container" class="vertical-time-simple vertical-without-time vertical-timeline vertical-timeline--animate vertical-timeline--one-column">
                        <div class="vertical-timeline-item dot-info vertical-timeline-element">
                            <div>
                                <span class="vertical-timeline-element-icon bounce-in"></span>
                                <div class="vertical-timeline-element-content bounce-in">
                                    <h4 class="timeline-title">@lang('Loading please wait...')</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <div class="progress-bar-animated-alt progress w-100">
                        <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">0%</div>
                    </div>
                    <button type="button" class="btn btn-primary pull-right" onclick="app.settings.recovery.backup.finish(this);" style="display: none;" disabled>
                        <i class="fa fa-check-circle"></i>&nbsp;
                        @lang('OK')
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="modal_settings_recovery_restore" class="modal fade recovery_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Restore')</h5>
                    {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button> --}}
                </div>
                <div class="modal-body custom_scrollbar">

                    <div id="settings_recovery_restore_timeline_container" class="vertical-time-simple vertical-without-time vertical-timeline vertical-timeline--animate vertical-timeline--one-column">
                        <div class="vertical-timeline-item dot-info vertical-timeline-element">
                            <div>
                                <span class="vertical-timeline-element-icon bounce-in"></span>
                                <div class="vertical-timeline-element-content bounce-in">
                                    <h4 class="timeline-title">@lang('Loading please wait...')</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <div class="progress-bar-animated-alt progress w-100">
                        <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">0%</div>
                    </div>
                    <button type="button" class="btn btn-primary pull-right" onclick="app.settings.recovery.restore.finish(this);" style="display: none;" disabled>
                        <i class="fa fa-check-circle"></i>&nbsp;
                        @lang('OK')
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="modal_settings_recovery_reset" class="modal fade recovery_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Reset')</h5>
                    {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button> --}}
                </div>
                <div class="modal-body custom_scrollbar">

                    <div id="settings_recovery_reset_timeline_container" class="vertical-time-simple vertical-without-time vertical-timeline vertical-timeline--animate vertical-timeline--one-column">
                        <div class="vertical-timeline-item dot-info vertical-timeline-element">
                            <div>
                                <span class="vertical-timeline-element-icon bounce-in"></span>
                                <div class="vertical-timeline-element-content bounce-in">
                                    <h4 class="timeline-title">@lang('Loading please wait...')</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <div class="progress-bar-animated-alt progress w-100">
                        <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">0%</div>
                    </div>
                    <button type="button" class="btn btn-primary pull-right" onclick="app.settings.recovery.reset.finish(this);" style="display: none;" disabled>
                        <i class="fa fa-check-circle"></i>&nbsp;
                        @lang('OK')
                    </button>
                </div>
            </div>
        </div>
    </div>
@endpush

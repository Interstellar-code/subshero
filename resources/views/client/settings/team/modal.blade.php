@push('modal')
    <div id="modal_settings_team_link" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <form class="" id="frm_settings_team_link" action="{{ route('app/settings/team/link') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('Invite new user')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="position-relative form-group">
                            <label for="settings_team_link_name" class="">@lang('Name')</label>
                            <input name="name" id="settings_team_link_name" type="text" class="form-control" data-toggle="tooltip" data-placement="bottom" title="@lang('Enter User Name')">
                        </div>
                        <div class="position-relative form-group">
                            <label for="settings_team_link_email" class="">@lang('Email')</label>
                            <input name="email" id="settings_team_link_email" type="email" class="form-control" required data-toggle="tooltip" data-placement="bottom" title="@lang('Enter User Email')">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary pull-right" onclick="app.settings.team.link(this);" data-toggle="tooltip" data-placement="left" title="@lang('Click here to send the invitation')">
                            <i class="fa fa-paper-plane"></i>&nbsp;
                            @lang('Send')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush

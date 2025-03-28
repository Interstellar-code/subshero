<form class="" id="frm_settings_api_token_edit" action="{{ route('app/settings/api/token/update') }}" method="POST">
    @csrf
    <input name="id" id="settings_api_token_edit_id" type="hidden" value="{{ $data->id }}">

    <div class="modal-header">
        <h5 class="modal-title">@lang('Update Token Key')</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="position-relative form-group">
            <label for="settings_api_token_edit_api_token_name" class="">@lang('Name')</label>
            <input name="name" id="settings_api_token_edit_api_token_name" value="{{ $data->name }}" maxlength="{{ len()->personal_access_tokens->name }}" type="text" class="form-control" required data-toggle="tooltip" data-placement="left" title="@lang('Add New Alert Profile')">
        </div>

        @if (!empty($data->secret_key))
            <div class="position-relative form-group mb-3">
                <label for="settings_api_token_edit_secret_key" class="">@lang('Token Key')</label>
                <div class="input-group">
                    <input name="secret_key" id="settings_api_token_edit_secret_key" value="{{ $data->secret_key }}" type="text" class="form-control" readonly>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary btn_copy" type="button" data-clipboard-text="{{ $data->secret_key }}" onclick="lib.do.copy(this);" title="@lang('Copy to clipboard')" data-toggle="tooltip" data-placement="right">
                            <i class="fa fa-copy"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endif

    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary pull-right" onclick="app.settings.api.token.update(this);" data-toggle="tooltip" data-placement="left" title="@lang('Save your changes')">
            <i class="fa fa-save"></i>&nbsp;
            @lang('Save')
        </button>
    </div>
</form>

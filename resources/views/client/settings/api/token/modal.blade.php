@push('modal')
    <div id="modal_settings_api_token_add" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <form class="" id="frm_settings_api_token_add" action="{{ route('app/settings/api/token/create') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('Create a new Token Key')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="position-relative form-group">
                            <label for="settings_api_token_add_name" class="">@lang('Name')</label>
                            <input name="name" id="settings_api_token_add_name" maxlength="{{ len()->personal_access_tokens->name }}" type="text" class="form-control" required data-toggle="tooltip" data-placement="left" title="@lang('Enter a Name')">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary pull-right" onclick="app.settings.api.token.create(this);" data-toggle="tooltip" data-placement="left" title="@lang('Create your token key')">
                            <i class="fa fa-plus"></i>&nbsp;
                            @lang('Create')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="modal_settings_api_token_edit" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">

            </div>
        </div>
    </div>
@endpush

<form class="" id="frm_settings_webhook_edit" action="{{ route('app/settings/webhook/update', $data->id) }}" method="POST">
    @csrf
    <input type="hidden" name="id" value="{{ $data->id }}">
    <input type="hidden" name="token" value="{{ $data->token }}">
    <div class="modal-header">
        <h5 class="modal-title">@lang('Create Webhook')</h5>

        <div class="switch_container m-0">
            <label class="switch !switch_lg ml-2" data-toggle="tooltip" data-placement="top" title="@lang('Active')">
                <input type="checkbox" name="status" value="1" {{ $data->status ? 'checked' : null }}>
                <span class="slider round"></span>
            </label>
        </div>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6 col-lg-4">
                <div class="position-relative form-group">
                    <label for="settings_webhook_edit_type" class="">@lang('Type')</label>
                    <select name="type" id="settings_webhook_edit_type" class="form-control" onchange="app.settings.webhook.update_type_check(this);" required data-toggle="tooltip" data-placement="left" title="@lang('Select type')">
                        @foreach (table('webhooks.type') as $key => $val)
                            <option value="{{ $key }}" {{ $data->type == $key ? 'selected' : null }}>{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6 col-lg-8">
                <div class="position-relative form-group">
                    <label for="settings_webhook_edit_name" class="">@lang('Name')</label>
                    <input name="name" id="settings_webhook_edit_name" value="{{ $data->name }}" maxlength="{{ len()->webhooks->name }}" type="text" class="form-control" required data-toggle="tooltip" data-placement="right" title="@lang('Enter a Name')">
                </div>
            </div>
        </div>
        <div class="position-relative form-group">
            <label for="settings_webhook_edit_endpoint_url" class="">@lang('Endpoint URL')</label>
            <div class="input-group">
                <input name="endpoint_url" id="settings_webhook_edit_endpoint_url" value="{{ $data->endpoint_url }}" maxlength="{{ len()->webhooks->endpoint_url }}" type="text" class="form-control" {{ $data->type == 1 ? 'readonly' : null }} required data-toggle="tooltip" data-placement="left" title="@lang('Enter Webhook URL')">
                <div class="input-group-append">
                    <button id="settings_webhook_edit_endpoint_url_generate" style="display: {{ $data->type == 1 ? 'block' : 'none' }};" class="btn btn-outline-secondary" type="button" title="@lang('Regenerate link')" data-toggle="tooltip" data-placement="right" onclick="app.settings.webhook.update_generate(this);">
                        <i class="fa fa-sync"></i>
                    </button>
                    <button id="settings_webhook_edit_endpoint_url_copy" class="btn btn-outline-secondary" type="button" title="@lang('Copy to clipboard')" data-toggle="tooltip" data-placement="right" onclick="app.settings.webhook.update_copy_endpoint_url(this);">
                        <i class="fa fa-copy"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="position-relative form-group">
            <label for="settings_webhook_edit_events" class="">@lang('Events')</label>
            <select name="events[]" id="settings_webhook_edit_events" class="form-control" multiple required data-toggle="tooltip" data-placement="left" title="@lang('Select events')">
                @foreach (table('webhooks.events') as $event)
                    <option value="{{ $event }}" {{ in_array($event, $data->events) ? 'selected' : null }}>{{ $event }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary pull-right" onclick="app.settings.webhook.update(this);" data-toggle="tooltip" data-placement="left" title="@lang('Save webhook')">
            <i class="fa fa-save"></i>&nbsp;
            @lang('Save')
        </button>
    </div>
</form>

<script>
    $(document).ready(function() {
        $('#settings_webhook_edit_events').select2({
            theme: 'bootstrap4',
            placeholder: "@lang('Select events')",
            // allowClear: true,
            tags: true,
            closeOnSelect: false,
            // tokenSeparators: [',', ' '],
            dropdownParent: $('#modal_settings_webhook_edit')
        });

        // Reset default select2 width
        $('#settings_webhook_edit_events').siblings('.select2-container').find('.select2-selection .select2-search .select2-search__field').css('width', '');
    });
</script>

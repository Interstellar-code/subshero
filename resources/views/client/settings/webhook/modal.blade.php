@push('modal')
    <div id="modal_settings_webhook_add" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form class="" id="frm_settings_webhook_add" action="{{ route('app/settings/webhook/create') }}" method="POST">
                    @csrf
                    <input type="hidden" name="token" value="">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('Create Webhook')</h5>

                        <div class="switch_container m-0">
                            <label class="switch !switch_lg ml-2" data-toggle="tooltip" data-placement="top" title="@lang('Active')">
                                <input type="checkbox" name="status" value="1" checked>
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
                                    <label for="settings_webhook_add_type" class="">@lang('Type')</label>
                                    <select name="type" id="settings_webhook_add_type" class="form-control" onchange="app.settings.webhook.create_type_check(this);" required data-toggle="tooltip" data-placement="left" title="@lang('Select type')">
                                        @foreach (table('webhooks.type') as $key => $val)
                                            <option value="{{ $key }}">{{ $val }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-8">
                                <div class="position-relative form-group">
                                    <label for="settings_webhook_add_name" class="">@lang('Name')</label>
                                    <input name="name" id="settings_webhook_add_name" maxlength="{{ len()->webhooks->name }}" type="text" class="form-control" required data-toggle="tooltip" data-placement="right" title="@lang('Enter a Name')">
                                </div>
                            </div>
                        </div>
                        <div class="position-relative form-group">
                            <label for="settings_webhook_add_endpoint_url" class="">@lang('Endpoint URL')</label>
                            <div class="input-group">
                                <input name="endpoint_url" id="settings_webhook_add_endpoint_url" maxlength="{{ len()->webhooks->endpoint_url }}" type="text" class="form-control" readonly required data-toggle="tooltip" data-placement="left" title="@lang('Enter Webhook URL')">
                                <div class="input-group-append">
                                    <button id="settings_webhook_add_endpoint_url_generate" class="btn btn-outline-secondary" type="button" title="@lang('Regenerate link')" data-toggle="tooltip" data-placement="right" onclick="app.settings.webhook.create_generate(this);">
                                        <i class="fa fa-sync"></i>
                                    </button>
                                    <button id="settings_webhook_add_endpoint_url_copy" class="btn btn-outline-secondary" type="button" title="@lang('Copy to clipboard')" data-toggle="tooltip" data-placement="right" onclick="app.settings.webhook.create_copy_endpoint_url(this);">
                                        <i class="fa fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="position-relative form-group">
                            <label for="settings_webhook_add_events" class="">@lang('Events')</label>
                            <select name="events[]" id="settings_webhook_add_events" class="form-control " multiple required data-toggle="tooltip" data-placement="left" title="@lang('Select events')">
                                @foreach (table('webhooks.events') as $event)
                                    <option value="{{ $event }}">{{ $event }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary pull-right" onclick="app.settings.webhook.create(this);" data-toggle="tooltip" data-placement="left" title="@lang('Create webhook')">
                            <i class="fa fa-plus"></i>&nbsp;
                            @lang('Create')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="modal_settings_webhook_edit" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#settings_webhook_add_events').select2({
                theme: 'bootstrap4',
                // containerCssClass: 'flex-grow-1',
                placeholder: "@lang('Select events')",
                // allowClear: true,
                tags: true,
                closeOnSelect: false,
                // tokenSeparators: [',', ' '],
                dropdownParent: $('#modal_settings_webhook_add')
            });

            // Reset default select2 width
            $('#settings_webhook_add_events').siblings('.select2-container').find('.select2-selection .select2-search .select2-search__field').css('width', '');
            // $('#settings_webhook_add_events').siblings('.select2-container').addClass('flex-grow-1');
        });
    </script>

@endpush

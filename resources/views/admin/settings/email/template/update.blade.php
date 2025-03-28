@extends(request()->ajax() ? 'admin/layouts/ajax' : 'admin/layouts/settings')

@section('head')
@endsection

@section('content')
    <div class="card hp-100">
        <h5 class="card-header">@lang('Update email template')</h5>
        <div class="card-body">
            <form class="row" id="settings_email_template_update_form" action="{{ route('admin/settings/email/template/update', $data->id) }}" method="POST">
                @csrf
                <div class="col-md-9 col-xl-9">
                    <div class="row">
                        <div class="col-md-3 col-lg-4">
                            <div class="position-relative form-group">
                                <label for="settings_email_template_update_type" class="">@lang('Type')</label>
                                <select name="type" id="settings_email_template_update_type" onchange="app.settings.template.update_type_change(this);" class="form-control" required>
                                    <option selected="" disabled="" value="" style="display: none;">@lang('Select')</option>
                                    @foreach (lib()->email->type->get_all_template_name() as $key => $val)
                                        <option value="{{ $val->template_name }}" {{ $data->type == $val->template_name ? 'selected' : null }}>{{ $val->template_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5 col-lg-6">
                            <div class="position-relative form-group">
                                <label for="settings_email_template_update_subject" class="">@lang('Email Subject')</label>
                                <input name="subject" id="settings_email_template_update_subject" value="{{ $data->subject }}" maxlength="{{ len()->email_templates->subject }}" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-2 col-lg-1">
                            <div class="position-relative form-group">
                                <label for="settings_email_template_update_is_default" class="">@lang('Default')</label>
                                <br>
                                <input name="is_default" id="settings_email_template_update_is_default" value="1" type="checkbox" data-toggle="toggle" {{ $data->is_default ? 'checked' : null }}>
                            </div>
                        </div>
                        <div class="col-md-2 col-lg-1">
                            <div class="position-relative form-group">
                                <label for="settings_email_template_update_is_status" class="">@lang('Enable')</label>
                                <br>
                                <input name="status" id="settings_email_template_update_is_status" value="1" type="checkbox" data-toggle="toggle" {{ $data->status ? 'checked' : null }}>
                            </div>
                        </div>
                    </div>

                    <div class="position-relative form-group">
                        <label for="settings_email_template_update_body" class="">@lang('Email Body')</label>
                        <textarea name="body" id="settings_email_template_update_body" maxlength="{{ len()->email_templates->body }}" rows="20" class="form-control">{{ $data->body }}</textarea>
                    </div>
                </div>

                <div class="col-md-3 col-xl-3 mb-3">
                    <div class="card hp-100">
                        <h5 class="card-header">@lang('Dynamic fields')</h5>
                        <div id="settings_email_template_update_fields_container" class="card-body pt-0">
                            @foreach (lib()->email->type->get_all() as $key => $val)
                                <button type="button" class="btn btn-outline-light btn-block border-dark btn_email_template_field" data-template_name="{{ $val->template_name }}" data-clipboard-text="&#123;{{ $val->field_value }}&#125;" title="@lang('Copy to clipboard')" data-toggle="tooltip" data-placement="right" onclick="app.settings.template.copied(this);" style="display: {{ $data->type == $val->template_name ? null : 'none' }};">
                                    <h6 class="m-0">
                                        <span>{{ $val->field_name }}</span>
                                        <small class="text-primary">&#123;{{ $val->field_value }}&#125;</small>
                                    </h6>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="row">
                        <div class="col-md-6 col-xl-7">
                        </div>
                        <div class="col-md-6 col-xl-2">
                            <button type="button" class="btn btn-outline-primary btn-lg btn-block pull-right" onclick="app.settings.template.update_preview(this);">
                                <i class="fa fa-binoculars"></i>&nbsp;
                                @lang('Preview')
                            </button>
                        </div>
                        <div class="col-md-6 col-xl-3">
                            <button type="submit" class="btn btn-primary btn-lg btn-block pull-right" onclick="app.settings.template.update(this);">
                                <i class="fa fa-save"></i>&nbsp;
                                @lang('Save')
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            var clipboard = new ClipboardJS('.btn_email_template_field');
        });
    </script>

@endsection

@extends(request()->ajax() ? 'client/layouts/ajax' : 'client/layouts/default')

@section('content')

    <form class="" id="frm_settings_contact_edit" action="{{ route('app/settings/contact/update') }}" method="POST">
        @csrf
        <input name="id" id="settings_contact_edit_id" type="hidden" value="{{ $data->id }}">
        <div class="row">
            <div class="col-md-5">
                <div class="position-relative form-group">
                    <label for="settings_contact_edit_name" class="">@lang('Name')</label>
                    <input name="name" id="settings_contact_edit_name" value="{{ $data->name }}" type="text" class="form-control" required data-toggle="tooltip" data-placement="left" title="@lang('Edit Contact Name')">
                </div>
            </div>
            <div class="col-md-5">
                <div class="position-relative form-group">
                    <label for="settings_contact_edit_email" class="">@lang('Email')</label>
                    <input name="email" id="settings_contact_edit_email" value="{{ $data->email }}" type="email" class="form-control" required data-toggle="tooltip" data-placement="bottom" title="@lang('Edit Contact Email')">
                </div>
            </div>
            <div class="col-md-2">
                <div class="position-relative form-group">
                    <label for="settings_contact_add_status" class="">@lang('Active')</label>
                    <br>
                    <input name="status" id="settings_contact_add_status" value="1" type="checkbox" {{ $data->status == 1 ? 'checked' : null }} data-toggle="tooltip" data-placement="right" title="@lang('Set active or inactive status')">
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary pull-right" onclick="app.settings.contact.update(this);" data-toggle="tooltip" data-placement="left" title="@lang('Save your Changes ')">
            <i class="fa fa-save"></i>&nbsp;
            @lang('Save')
        </button>
    </form>

@endsection

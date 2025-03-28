@extends(request()->ajax() ? 'client/layouts/ajax' : 'client/layouts/default')

@section('content')

    <input type="hidden" name="id" id="settings_payment_edit_id" value="{{ $data->id }}">
    <div class="row">
        <div class="col-md-6">
            <div class="position-relative form-group">
                <label for="settings_payment_edit_name" class="">@lang('Name')</label>
                <input name="name" id="settings_payment_edit_name" value="{{ $data->name }}" type="text" class="form-control" required data-toggle="tooltip" data-placement="left" title="@lang('Enter Payment Name')">
            </div>
        </div>
        <div class="col-md-6">
            <div class="position-relative form-group">
                <label for="settings_payment_edit_payment_type" class="">@lang('Payment type')</label>
                <select name="payment_type" id="settings_payment_edit_payment_type" class="form-control" required data-toggle="tooltip" data-placement="right" title="@lang('Select Payment Type ')">
                    <option selected="" disabled="" value="" style="display: none;">@lang('Select')</option>
                    @foreach (table('subscription.payment_type') as $val)
                        <option value="{{ $val }}" {{ $data->payment_type == $val ? 'selected' : null }}>{{ $val }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="position-relative form-group">
                <label for="settings_payment_edit_description" class="">@lang('Description')</label>
                <input name="description" id="settings_payment_edit_description" value="{{ $data->description }}" type="text" class="form-control" data-toggle="tooltip" data-placement="left" title="@lang('Enter Payment Description')">
            </div>
        </div>
        <div class="col-md-6">
            <div class="position-relative form-group">
                <label for="settings_payment_edit_expiry" class="">@lang('Expiry')</label>
                <input name="expiry" id="settings_payment_edit_expiry" value="{{ $data->expiry }}" type="date" class="form-control" data-toggle="tooltip" data-placement="right" title="@lang('Set Expiry Date')">
            </div>
        </div>
    </div>

@endsection

@extends(request()->ajax() ? 'admin/layouts/ajax' : 'admin/layouts/default')

@section('head')
@endsection

@section('content')
    <form class="row" id="customer_edit_form" action="{{ route('admin/customer/update', $data->id) }}" method="POST">
        @csrf
        <input type="hidden" name="id" id="customer_edit_id" value="{{ $data->id }}">

        <div class="col-sm-12">
            <div class="position-relative form-group">
                <label for="customer_edit_plan_id" class="">@lang('Plan')</label>
                <select name="plan_id" id="customer_edit_plan_id" class="form-control" required>
                    @foreach (lib()->plan->get_all() as $val)
                        <option value="{{ $val->id }}" {{ $data->plan_id == $val->id ? 'selected' : null }}>{{ $val->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="position-relative form-group">
                <label for="customer_edit_email" class="">@lang('Email')</label>
                <input name="email" id="customer_edit_email" value="{{ $data->email }}" maxlength="{{ len()->users->email }}" type="email" class="form-control" required>
            </div>
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary btn-sm pull-right" onclick="app.customer.update(this, '{{ $data->id }}');">
                <i class="fa fa-save"></i>&nbsp;
                @lang('Save')
            </button>
        </div>
    </form>

@endsection

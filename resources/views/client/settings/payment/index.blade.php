@extends(request()->ajax() ? 'client/layouts/ajax' : 'client/layouts/settings')

@section('content')

    <div class="main-card mb-3">
        <h5>@lang('Payment Methods')</h5>
        <div class="row">
            <div class="col-md-6">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">@lang('Name')</th>
                            <th scope="col">@lang('Description')</th>
                            <th scope="col">@lang('Expiry')</th>
                            <th scope="col">@lang('Payment Type')</th>
                            <th scope="col">@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (lib()->user->payment_methods ?? [] as $val)
                            <tr data-id="{{ $val->id }}">
                                <!-- <td>{{ $loop->iteration }}</td> -->
                                <td>{{ $val->name }}</td>
                                <td>{{ $val->description }}</td>

                                @if ($val->expiry)
                                    <td>{{ date('d M Y', strtotime($val->expiry)) }}</td>
                                @else
                                    <td>&nbsp;</td>
                                @endif

                                <td>{{ $val->payment_type }}</td>
                                <td>
                                    <button onclick="app.settings.payment.edit(this);" title="@lang('Edit')" class="btn-icon btn-icon-only btn-pill btn btn-outline-primary btn-sm" data-toggle="tooltip" data-placement="top" type="button">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    &nbsp;
                                    <button onclick="app.settings.payment.delete(this);" title="@lang('Delete')" class="btn-icon btn-icon-only btn-pill btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" type="button">
                                        <i class="fa fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
           <!--Added tooltip for Payment Add button-->
        <button class="mb-2 mr-2 btn btn-primary" type="button" data-target="#modal_settings_payment_add" data-toggle="modal,tooltip" data-placement="right" title="@lang('Click on Add to Add New Payment Method')">
            <i class="fa fa-plus"></i>&nbsp;
            @lang('Add New')
        </button>
    </div>

@endsection

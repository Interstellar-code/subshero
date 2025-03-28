@extends(request()->ajax() ? 'client/layouts/ajax' : 'client/layouts/settings')

@section('head')
@endsection

@section('content')

    <div class="main-card mb-3">
        <h5>@lang('Contacts')</h5>
        <div class="row">
            <div class="col-md-6">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">@lang('Name')</th>
                            <th scope="col">@lang('Email')</th>
                            <th scope="col">@lang('Status')</th>
                            <th scope="col">@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (lib()->user->contacts ?? [] as $val)
                            <tr data-id="{{ $val->id }}">
                                <!-- <td>{{ $loop->iteration }}</td> -->
                                <td>{{ $val->name }}</td>
                                <td>{{ $val->email }}</td>
                                <td>
                                    @if ($val->status == 1)
                                        <span class="badge badge-success">@lang('Active')</span>
                                    @else
                                        <span class="badge badge-warning">@lang('Inactive')</span>
                                    @endif
                                </td>

                                <td>
                                    <button onclick="app.settings.contact.edit(this, '{{ $val->id }}');" title="@lang('Edit')" class="btn-icon btn-icon-only btn-pill btn btn-outline-primary btn-sm" data-toggle="tooltip" data-placement="top" type="button">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    &nbsp;
                                    <button onclick="app.settings.contact.delete(this);" title="@lang('Delete')" class="btn-icon btn-icon-only btn-pill btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" type="button">
                                        <i class="fa fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!--Added tooltip for Contact Add button-->
        <button class="mb-2 mr-2 btn btn-primary" type="button" data-target="#modal_settings_contact_add" data-toggle="modal,tooltip" data-placement="right" title="@lang('Click on Add to Add New Contact')">
            <i class="fa fa-plus"></i>&nbsp;
            @lang('Add New')
        </button>
    </div>

@endsection

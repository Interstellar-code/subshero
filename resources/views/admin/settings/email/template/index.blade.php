@extends(request()->ajax() ? 'admin/layouts/ajax' : 'admin/layouts/settings')

@section('head')
@endsection

@section('content')
    <div class="main-card mb-3">
        <div class="card hp-100">
            <div class="card-header row m-0">
                <div class="col-6">
                    <h5 class="m-0">@lang('Email Templates')</h5>
                </div>
                <div class="col-6 text-right">
                    <a href="{{ route('admin/settings/email/template/create') }}" onclick="app.page.change(this);" class="btn btn-primary">
                        <i class="fa fa-plus"></i>&nbsp;
                        @lang('Add')
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive card border-0 p-3">
                    <table id="tbl_settings_email_template" class="align-middle mb-0 table table-borderless table-striped table-hover text-center mb-4">
                        <thead>
                            <tr>
                                <th class="p-3">@lang('Type')</th>
                                <th class="p-3">@lang('Subject')</th>
                                <th class="p-3">@lang('Default')</th>
                                <th class="p-3">@lang('Status')</th>
                                <th class="p-3">@lang('Actions')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (lib()->settings->email->get_by_user() ?? [] as $val)
                                <tr data-id="{{ $val->id }}">
                                    <!-- <td>{{ $loop->iteration }}</td> -->
                                    <td>{{ $val->type }}</td>
                                    <td>{{ $val->subject }}</td>
                                    {{-- <td>{{ table('email_templates.is_default')[$val->is_default] }}</td> --}}
                                    <td>
                                        <span class="text-capitalize badge badge-{{ $val->is_default ? 'success' : 'info' }}">{{ table('email_templates.is_default')[$val->is_default] }}</span>
                                    </td>
                                    <td>
                                        <span class="text-capitalize badge badge-{{ $val->status ? 'success' : 'warning' }}">{{ table('email_templates.status')[$val->status] }}</span>
                                    </td>
                                    <td>
                                        <a class="mb-2 mr-2 btn-icon btn-icon-only btn-pill btn btn-outline-primary btn-sm border" href="{{ route('admin/settings/email/template/update', $val->id) }}" onclick="app.page.hit(this);">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <div class="dropdown d-inline-block">
                                            <button class="mb-2 mr-2 btn-icon btn-icon-only btn-pill btn btn-outline-primary btn-sm border" type="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown">
                                                <i class="fa fa-ellipsis-v"></i>
                                            </button>
                                            <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-right dropdown-menu-rounded dropdown-menu mt-1">
                                                <button class="btn-icon btn-icon-only btn-pill btn btn-outline-danger btn-sm" onclick="app.settings.template.delete(this);" type="button">
                                                    <!--  title="@lang('Delete')" data-toggle="tooltip" data-placement="top" -->
                                                    @lang('Delete')&nbsp;
                                                    <i class="fa fa-trash-alt"></i>
                                                </button>
                                                <!-- <button type="button" tabindex="0" class="dropdown-item" onclick="app.subscription.delete(this);">@lang('Delete')</button> -->
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function() {
            $('#tbl_settings_email_template').DataTable({});
        });
    </script>

@endsection

@extends(request()->ajax() ? 'admin/layouts/ajax' : 'admin/layouts/default')

@section('head')
@endsection

@section('content')
    <div class="main-card mb-3">
        <div class="table-responsive card p-3">
            <table id="tbl_customer" class="align-middle mb-0 table table-borderless table-striped table-hover text-center mb-4">
                <thead>
                    <tr>
                        <th class="p-3">@lang('Image')</th>
                        <th class="p-3">@lang('Name')</th>
                        <th class="p-3">@lang('Email')</th>
                        <th class="p-3">@lang('Coupons')</th>
                        <th class="p-3">@lang('Plan')</th>
                        <th scope="col">@lang('Action')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (lib()->customer->get_all() ?? [] as $val)
                        <tr data-id="{{ $val->id }}">
                            <!-- <td>{{ $loop->iteration }}</td> -->
                            <td>
                                <img src="{{ img_url($val->image, SUB_DImg) }}" class="!rounded-circle img_table">
                            </td>
                            <td>{{ $val->name }}</td>
                            <td>{{ $val->email }}</td>
                            <td>{{ $val->coupons }}</td>
                            <td>{{ $val->plan_name }}</td>
                            <td>
                                <button onclick="app.customer.edit(this, '{{ $val->id }}');" title="@lang('Edit')" class="btn-icon btn-icon-only btn-pill btn btn-outline-primary btn-sm" data-toggle="tooltip" data-placement="top" type="button">
                                    <i class="fa fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


    </div>

    <script>
        $(document).ready(() => {

            $('#tbl_customer').DataTable({
                // ... skipped ...
                // dom: 'l<"toolbar">frtip',
                initComplete: function() {
                    // $('#tbl_customer_filter').prepend('<button type="button" class="btn-shadow btn btn-wide btn-primary mr-3"  data-toggle="modal" data-target="#customer_add_modal">Add</button>');
                },
            });

        });
    </script>

@endsection

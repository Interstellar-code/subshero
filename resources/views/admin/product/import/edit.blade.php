@extends(request()->ajax() ? 'admin/layouts/ajax' : 'admin/layouts/default')

@section('content')

    <style>
        .form-wizard-content.tab-content {
            height: initial !important;
        }

        /* #product_import_table  {
            display: block;
            max-height: 40vh;
            overflow-y: scroll;
        } */

        .td_error{
            border: 2px solid transparent;
            border-color: #d92550;
        }

    </style>

    <div id="product_import_form_container">
        @foreach ($data['items'] as $val)
            <form class="row" id="product_import_form_{{ $loop->iteration }}" action="{{ route('admin/product/import/validate') }}" method="POST">
                @csrf
            </form>
        @endforeach
    </div>

    <div class="table-responsive">
        <table id="product_import_table" class="align-middle mb-0 table table-borderless text-center mb-4">
            <thead>
                <tr>
                    <th class="p-3 pl-5 pr-5">id</th>
                    <th class="p-3 pl-5 pr-5">admin_id</th>
                    <th class="p-3 pl-5 pr-5">category_id</th>
                    <th class="p-3 pl-5 pr-5">product_name</th>
                    <th class="p-3 pl-5 pr-5">brandname</th>
                    <th class="p-3 pl-5 pr-5">product_type</th>
                    <th class="p-3 pl-5 pr-5">description</th>
                    <th class="p-3 pl-5 pr-5">url</th>
                    <th class="p-3 pl-5 pr-5">url_app</th>
                    <th class="p-3 pl-5 pr-5">image</th>
                    <th class="p-3 pl-5 pr-5">favicon</th>
                    <th class="p-3 pl-5 pr-5">status</th>
                    <th class="p-3 pl-5 pr-5">sub_ltd</th>
                    <th class="p-3 pl-5 pr-5">launch_year</th>
                    <th class="p-3 pl-5 pr-5">sub_platform</th>
                    <th class="p-3 pl-5 pr-5">pricing_type</th>
                    <th class="p-3 pl-5 pr-5">currency_code</th>
                    <th class="p-3 pl-5 pr-5">price1_name</th>
                    <th class="p-3 pl-5 pr-5">price1_value</th>
                    <th class="p-3 pl-5 pr-5">price2_name</th>
                    <th class="p-3 pl-5 pr-5">price2_value</th>
                    <th class="p-3 pl-5 pr-5">price3_name</th>
                    <th class="p-3 pl-5 pr-5">price3_value</th>
                    <th class="p-3 pl-5 pr-5">refund_days</th>
                    <th class="p-3 pl-5 pr-5">billing_frequency</th>
                    <th class="p-3 pl-5 pr-5">billing_cycle</th>
                    <th class="p-3 pl-5 pr-5">ltdval_price</th>
                    <th class="p-3 pl-5 pr-5">ltdval_frequency</th>
                    <th class="p-3 pl-5 pr-5">ltdval_cycle</th>
                    <th class="p-3 pl-5 pr-5">created_at</th>
                    <th class="p-3 pl-5 pr-5">created_by</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['items'] as $val)
                    <tr data-id="{{ $val['id'] }}">
                        <td>
                            <input name="id" id="product_import_form_{{ $loop->iteration }}_id" value="{{ $val['id'] }}" form="product_import_form_{{ $loop->iteration }}" type="text" class="form-control">
                        </td>
                        <td>
                            <input name="admin_id" id="product_import_form_{{ $loop->iteration }}_admin_id" value="{{ $val['admin_id'] }}" form="product_import_form_{{ $loop->iteration }}" type="text" class="form-control">
                        </td>
                        <td>
                            <input name="category_id" id="product_import_form_{{ $loop->iteration }}_category_id" value="{{ $val['category_id'] }}" form="product_import_form_{{ $loop->iteration }}" type="text" class="form-control">
                        </td>
                        <td>
                            <input name="product_name" id="product_import_form_{{ $loop->iteration }}_product_name" value="{{ $val['product_name'] }}" form="product_import_form_{{ $loop->iteration }}" type="text" class="form-control">
                        </td>
                        <td>
                            <input name="brandname" id="product_import_form_{{ $loop->iteration }}_brandname" value="{{ $val['brandname'] }}" form="product_import_form_{{ $loop->iteration }}" type="text" class="form-control">
                        </td>
                        <td>
                            <input name="product_type" id="product_import_form_{{ $loop->iteration }}_product_type" value="{{ $val['product_type'] }}" form="product_import_form_{{ $loop->iteration }}" type="text" class="form-control">
                        </td>
                        <td>
                            <input name="description" id="product_import_form_{{ $loop->iteration }}_description" value="{{ $val['description'] }}" form="product_import_form_{{ $loop->iteration }}" type="text" class="form-control">
                        </td>
                        <td>
                            <input name="url" id="product_import_form_{{ $loop->iteration }}_url" value="{{ $val['url'] }}" form="product_import_form_{{ $loop->iteration }}" type="text" class="form-control">
                        </td>
                        <td>
                            <input name="url_app" id="product_import_form_{{ $loop->iteration }}_url_app" value="{{ $val['url_app'] }}" form="product_import_form_{{ $loop->iteration }}" type="text" class="form-control">
                        </td>
                        <td>
                            <input name="image" id="product_import_form_{{ $loop->iteration }}_image" value="{{ $val['image'] }}" form="product_import_form_{{ $loop->iteration }}" type="text" class="form-control">
                        </td>
                        <td>
                            <input name="favicon" id="product_import_form_{{ $loop->iteration }}_favicon" value="{{ $val['favicon'] }}" form="product_import_form_{{ $loop->iteration }}" type="text" class="form-control">
                        </td>
                        <td>
                            <input name="status" id="product_import_form_{{ $loop->iteration }}_status" value="{{ $val['status'] }}" form="product_import_form_{{ $loop->iteration }}" type="text" class="form-control">
                        </td>
                        <td>
                            <input name="sub_ltd" id="product_import_form_{{ $loop->iteration }}_sub_ltd" value="{{ $val['sub_ltd'] }}" form="product_import_form_{{ $loop->iteration }}" type="text" class="form-control">
                        </td>
                        <td>
                            <input name="launch_year" id="product_import_form_{{ $loop->iteration }}_launch_year" value="{{ $val['launch_year'] }}" form="product_import_form_{{ $loop->iteration }}" type="text" class="form-control">
                        </td>
                        <td>
                            <input name="sub_platform" id="product_import_form_{{ $loop->iteration }}_sub_platform" value="{{ $val['sub_platform'] }}" form="product_import_form_{{ $loop->iteration }}" type="text" class="form-control">
                        </td>
                        <td>
                            <input name="pricing_type" id="product_import_form_{{ $loop->iteration }}_pricing_type" value="{{ $val['pricing_type'] }}" form="product_import_form_{{ $loop->iteration }}" type="text" class="form-control">
                        </td>
                        <td>
                            <input name="currency_code" id="product_import_form_{{ $loop->iteration }}_currency_code" value="{{ $val['currency_code'] }}" form="product_import_form_{{ $loop->iteration }}" type="text" class="form-control">
                        </td>
                        <td>
                            <input name="price1_name" id="product_import_form_{{ $loop->iteration }}_price1_name" value="{{ $val['price1_name'] }}" form="product_import_form_{{ $loop->iteration }}" type="text" class="form-control">
                        </td>
                        <td>
                            <input name="price1_value" id="product_import_form_{{ $loop->iteration }}_price1_value" value="{{ $val['price1_value'] }}" form="product_import_form_{{ $loop->iteration }}" type="text" class="form-control">
                        </td>
                        <td>
                            <input name="price2_name" id="product_import_form_{{ $loop->iteration }}_price2_name" value="{{ $val['price2_name'] }}" form="product_import_form_{{ $loop->iteration }}" type="text" class="form-control">
                        </td>
                        <td>
                            <input name="price2_value" id="product_import_form_{{ $loop->iteration }}_price2_value" value="{{ $val['price2_value'] }}" form="product_import_form_{{ $loop->iteration }}" type="text" class="form-control">
                        </td>
                        <td>
                            <input name="price3_name" id="product_import_form_{{ $loop->iteration }}_price3_name" value="{{ $val['price3_name'] }}" form="product_import_form_{{ $loop->iteration }}" type="text" class="form-control">
                        </td>
                        <td>
                            <input name="price3_value" id="product_import_form_{{ $loop->iteration }}_price3_value" value="{{ $val['price3_value'] }}" form="product_import_form_{{ $loop->iteration }}" type="text" class="form-control">
                        </td>
                        <td>
                            <input name="refund_days" id="product_import_form_{{ $loop->iteration }}_refund_days" value="{{ $val['refund_days'] }}" form="product_import_form_{{ $loop->iteration }}" type="text" class="form-control">
                        </td>
                        <td>
                            <input name="billing_frequency" id="product_import_form_{{ $loop->iteration }}_billing_frequency" value="{{ $val['billing_frequency'] }}" form="product_import_form_{{ $loop->iteration }}" type="text" class="form-control">
                        </td>
                        <td>
                            <input name="billing_cycle" id="product_import_form_{{ $loop->iteration }}_billing_cycle" value="{{ $val['billing_cycle'] }}" form="product_import_form_{{ $loop->iteration }}" type="text" class="form-control">
                        </td>
                        <td>
                            <input name="ltdval_price" id="product_import_form_{{ $loop->iteration }}_ltdval_price" value="{{ $val['ltdval_price'] }}" form="product_import_form_{{ $loop->iteration }}" type="text" class="form-control">
                        </td>
                        <td>
                            <input name="ltdval_frequency" id="product_import_form_{{ $loop->iteration }}_ltdval_frequency" value="{{ $val['ltdval_frequency'] }}" form="product_import_form_{{ $loop->iteration }}" type="text" class="form-control">
                        </td>
                        <td>
                            <input name="ltdval_cycle" id="product_import_form_{{ $loop->iteration }}_ltdval_cycle" value="{{ $val['ltdval_cycle'] }}" form="product_import_form_{{ $loop->iteration }}" type="text" class="form-control">
                        </td>
                        <td>
                            <input name="created_at" id="product_import_form_{{ $loop->iteration }}_created_at" value="{{ $val['created_at'] }}" form="product_import_form_{{ $loop->iteration }}" type="text" class="form-control">
                        </td>
                        <td>
                            <input name="created_by" id="product_import_form_{{ $loop->iteration }}_created_by" value="{{ $val['created_by'] }}" form="product_import_form_{{ $loop->iteration }}" type="text" class="form-control">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        <button onclick="app.product.import.validate(this);" type="submit" class="btn btn-primary pull-right">
            <i class="fa fa-check"></i>&nbsp;
            @lang('Validate')
        </button>
        <br>
        <br>
    </div>

@endsection

@extends(request()->ajax() ? 'admin/layouts/ajax' : 'admin/layouts/default')

@section('head')
    <style>
        #tbl_product_adapt tbody tr {
            height: 73px;
        }

    </style>
@endsection

@section('content')
    <div class="main-card mb-3">
        <div class="table-responsive card p-3">
            <table id="tbl_product_adapt" class="align-middle mb-0 table table-borderless table-striped table-hover text-center mb-4">
            </table>
        </div>
    </div>

    <script>
        $(document).ready(() => {
            app.product.init();

            var table = $('#tbl_product_adapt').DataTable({
                processing: true,
                serverSide: true,
                // stateSave: true,
                ajax: {
                    type: 'POST',
                    url: "{{ route('admin/product/adapt/datatable/index') }}",
                    data: {
                        _token: app.config.token,
                    },
                    beforeSend: function() {
                        // Abort previous ajax request
                        if (table && table.hasOwnProperty('settings')) {
                            table.settings()[0].jqXHR.abort();
                        }
                    }
                },
                order: [
                    [0, 'desc'],
                ],
                aLengthMenu: [
                    [5, 10, 25, 50, 100],
                    [5, 10, 25, 50, 100],
                ],
                iDisplayLength: 10,
                // responsive: true,

                // Customize table row column
                fnCreatedRow: function(nRow, aData, iDataIndex) {
                    $(nRow).attr('data-id', aData.id);
                },
                columns: [{
                        data: 'id',
                        visible: false,
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'column_brand',
                        title: lang('Type'),
                        orderable: false,
                    },
                    {
                        data: 'user_name',
                        title: lang('User Name'),
                    },
                    {
                        data: 'product_name',
                        title: lang('Product Name'),
                    },
                    {
                        data: 'product_type_name',
                        title: lang('Product Type'),
                    },
                    {
                        data: 'column_pricing_type',
                        title: lang('Pricing Type'),
                    },
                    {
                        data: 'url',
                        title: lang('URL'),
                    },
                    {
                        data: 'price',
                        title: lang('Price'),
                    },
                    {
                        data: 'currency_code',
                        title: lang('Currency Code'),
                    },
                    {
                        data: 'column_action',
                        title: lang('Actions'),
                        width: '100px',
                        orderable: false,
                        searchable: false,
                    },
                ],
                initComplete: function() {
                    // $('#tbl_product_adapt_filter').prepend($('#tpl_product_table_buttons').html());
                },
            });
        });
    </script>
@endsection

@extends(request()->ajax() ? 'admin/layouts/ajax' : 'admin/layouts/default')

@section('head')
@endsection

@section('content')
        <div class="table-responsive card p-3">
            <table id="tbl_product_related_entity" class="align-middle mb-0 table table-borderless table-striped table-hover text-center mb-4 product-related-table">
            </table>
        </div>

        <template id="tpl_product_related_entity_table_buttons">
            @include('admin/product/import_menu')
            <button type="button" class="btn-shadow btn btn-wide btn-primary mr-3" data-toggle="modal" data-target="#product_related_entity_add_modal">@lang('Add')</button>
        </template>

        <div id="product-related-entity-name" hidden>@lang(ucfirst($productRelatedEntity))</div>
        <div id="product-related-entity" hidden>{{ucfirst($productRelatedEntity)}}</div>

        <script>
        $(document).ready(() => {

            var table = $('#tbl_product_related_entity').DataTable({
                processing: true,
                serverSide: true,
                // stateSave: true,
                ajax: {
                    type: 'POST',
                    url: "{{ route('admin/product/productRelatedEntity/datatable/index', $productRelatedEntity) }}",
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
                    [0, 'asc'],
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
                        title: 'id',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'name',
                        title: lang('Product {{ucfirst($productRelatedEntity)}}'),
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
                    $('#tbl_product_related_entity_filter').prepend($('#tpl_product_related_entity_table_buttons').html());
                },
            });
        });
    </script>
@endsection

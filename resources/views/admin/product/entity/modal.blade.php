<div id="product_related_entity_add_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl product-related-modal">
        <div class="modal-content">
            <form id="product_related_entity_add_form" action="{{route('admin/product/entity/create')}}" method="POST">
                @csrf
                <div class="modal-header">
                    <img class="favicon img-thumbnail mr-1" src="{{ asset_ver('assets/images/favicon.ico') }}">
                    <h5 class="modal-title">@lang('Product') <span class="product-related-entity-name">{{isset($productRelatedEntity) ? __(ucfirst($productRelatedEntity)) : ''}}</span> @lang('Create')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">

                        <div class="col-12">
                            
                            <div class="position-relative form-group">
                                <label for="add_product_related_entity_name">@lang('Product') <span class="product-related-entity-name">{{isset($productRelatedEntity) ? __(ucfirst($productRelatedEntity)) : ''}}</span> @lang('Name')</label>
                                <input name="product_related_entity_name" id="add_product_related_entity_name" maxlength="{{ len()->products->product_name }}" type="text" class="form-control" required>
                                <input name="entity" class="product-related-entity-input" type="text" value="{{isset($productRelatedEntity) ? ucfirst($productRelatedEntity) : ''}}" hidden>
                            </div>
                            
                        </div>

                        <div class="col-12">
                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block pull-right" onclick="app.product.related.entity.create(this);">
                                        <i class="fa fa-plus"></i>&nbsp;
                                        @lang('Add')
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="product_related_entity_edit_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl product-related-modal">
        <div class="modal-content">
            <div class="modal-header">
                <img class="favicon img-thumbnail mr-1" src="{{ asset_ver('assets/images/favicon.ico') }}">
                <h5 class="modal-title">@lang('Product') <span class="product-related-entity-name">{{isset($productRelatedEntity) ? __(ucfirst($productRelatedEntity)) : ''}}</span> @lang('Update')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>

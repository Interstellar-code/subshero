@extends(request()->ajax() ? 'admin/layouts/ajax' : 'admin/layouts/default')

@section('head')
@endsection

@section('content')
    <form class="row" id="product_related_entity_edit_form" action="{{ route('admin/product/productRelatedEntity/update', ['id' => $data->id, 'productRelatedEntity' => $productRelatedEntity]) }}" method="POST">
        @csrf
        <input type="hidden" name="id" id="product_related_entity_edit_id" value="{{ $data->id }}">

        

        <div class="col-12">
            <div class="position-relative form-group">
                <label for="edit_product_related_entity_name"><span class="product-related-entity-name">@lang(ucfirst($slug))</span>  @lang('Name')</label>
                <input name="product_related_entity_name" id="edit_product_related_entity_name" value="{{ $data->name }}" maxlength="{{ len()->products->product_name }}" type="text" class="form-control" required>
                <input name="entity" class="product-related-entity-input" type="text" value="{{ucfirst($productRelatedEntity)}}" hidden>
            </div>
        </div>

        

        
        <div class="col-12">
            <div class="row">

                <div class="col-md-6 col-xl-3">
                    <button type="submit" class="btn btn-primary btn-lg btn-block pull-right" onclick="app.product.related.entity.update(this, '{{ $data->id }}');">
                        <i class="fa fa-save"></i>&nbsp;
                        @lang('Save')
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection

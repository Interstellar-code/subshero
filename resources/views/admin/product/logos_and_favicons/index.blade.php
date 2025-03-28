@extends(request()->ajax() ? 'admin/layouts/ajax' : 'admin/layouts/default')

@section('head')
@endsection

@section('content')

<div class="mb-3 card">
    <div class="card-header">
        <ul class="nav nav-justified">
            <li class="nav-item col-2"><a data-toggle="tab" href="#tab-eg7-0" class="nav-link show images-import active">@lang('Import Logos')</a></li>
            <li class="nav-item col-2"><a data-toggle="tab" href="#tab-eg7-1" class="nav-link show images-import">@lang('Import Favicons')</a></li>
            <li class="images-import" style="margin-left: 5px;">@include('admin/product/import_menu')</li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content">
            <div class="tab-pane show active" id="tab-eg7-0" role="tabpanel">
                <form action="" method="post" enctype="multipart/form-data">
                    @csrf
                    <ol>
                        <li>@lang('Upload a zip file. eg: logos.zip')</li>
                        <li>@lang('Make sure the Logo size is 320 *120')</li>
                        <li>@lang('Make sure the name of the logo file is in lower case.')</li>
                        <li>@lang('Folder name should be "logos" by default.')</li>
                    </ol>
                    <div class="form-group">
                        <input type="file" class="form-control-file" name="images-archive" accept=".zip" required>
                        <input type="text" name="images-type" value="logos" hidden>
                    </div>
                    <button type="button" onclick="app.product.images.import(this)" class="btn btn-primary logos-and-favicons-btn">@lang('Submit Logos')</button>
                </form>
            </div>
            <div class="tab-pane show" id="tab-eg7-1" role="tabpanel">
                <form action="" method="post" enctype="multipart/form-data">
                    @csrf
                    <ol>
                        <li>@lang('Upload a zip file. eg: favicons.zip')</li>
                        <li>@lang('Make sure the Logo size is 128 *128')</li>
                        <li>@lang('Make sure the name of the favicons file is in lower case with no special charcter.')</li>
                        <li>@lang('Folder name should be "favicons" by default.')</li>
                    </ol>
                    <div class="form-group">
                        <input type="file" class="form-control-file" name="images-archive" accept=".zip" required>
                        <input type="text" name="images-type" value="favicons" hidden>
                    </div>
                    <button type="button" onclick="app.product.images.import(this)" class="btn btn-primary logos-and-favicons-btn">@lang('Submit Favicons')</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

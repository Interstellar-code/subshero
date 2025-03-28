<div id="modal_folder_add" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Add new folder')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="" id="frm_folder_add" action="{{ route('app/folder/create') }}" method="POST">
                    @csrf
                    <input type="hidden" name="color" id="folder_add_color_input" value="">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="folder_add_name" class="">@lang('Folder name')</label>
                                <input name="name" id="folder_add_name" type="text" class="form-control" required data-toggle="tooltip" data-placement="left" title="@lang('Enter the folder Name')">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="position-relative form-group" data-toggle="tooltip" data-placement="top" title="@lang('Check the box if you want this to be the deafult folder')">
                                <label for="folder_add_is_default" class="">@lang('Default')</label>
                                <br>
                                <input name="is_default" id="folder_add_is_default" value="1" type="checkbox" data-toggle="toggle">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="position-relative form-group" data-toggle="tooltip" data-placement="right" title="@lang('Set the Color of this Folder')">
                                <label for="folder_add_color" class="">@lang('Folder color')</label>
                                <div class="pickr_wrapper">
                                    <span id="folder_add_color_picker" class="color-picker"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="folder_add_price_type">@lang('Currency')</label>
                            <select name="price_type" id="folder_add_price_type" class="form-control text-center" required data-toggle="tooltip" data-placement="top" title="@lang('Select Currency Code')">
                                <option value="All">All</option>
                                @foreach (lib()->config->currency as $val)
                                    <option value="{{ $val['code'] }}" {{ lib()->prefer->currency == $val['code'] ? 'selected' : null }}>{{ $val['code'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary pull-right" onclick="app.folder.create(this);" data-toggle="tooltip" data-placement="left" title="@lang('Add your Folder')">
                        <i class="fa fa-plus"></i>&nbsp;
                        @lang('Add')
                    </button>
                </form>
            </div>
            <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">@lang('Add')</button>
                </div> -->
        </div>
    </div>
</div>

<div id="modal_folder_edit" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Update folder')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="" id="frm_folder_edit" action="{{ route('app/folder/update', 0) }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="folder_edit_id" value="0">
                    <input type="hidden" name="color" id="folder_edit_color_input" value="">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="folder_edit_name" class="">@lang('Folder name')</label>
                                <input name="name" id="folder_edit_name" type="text" class="form-control" required data-toggle="tooltip" data-placement="left" title="@lang('Edit the folder Name')">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="position-relative form-group" data-toggle="tooltip" data-placement="top" title="@lang('Check to make this folder as your default folder')">
                                <label for="folder_edit_is_default" class="">@lang('Default')</label>
                                <br>
                                <input name="is_default" id="folder_edit_is_default" value="1" type="checkbox" data-toggle="toggle">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="position-relative form-group" data-toggle="tooltip" data-placement="right" title="@lang('Set the Color of this Folder')">
                                <label for="folder_edit_color" class="">@lang('Folder color')</label>
                                <div class="pickr_wrapper">
                                    <span id="folder_edit_color_picker" class="color-picker"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="folder_edit_price_type">@lang('Currency')</label>
                            <select name="price_type" id="folder_edit_price_type" class="form-control text-center" required data-toggle="tooltip" data-placement="top" title="@lang('Select Currency Code')">
                                <option value="All">All</option>
                                @foreach (lib()->config->currency as $val)
                                    <option value="{{ $val['code'] }}" {{ lib()->prefer->currency == $val['code'] ? 'selected' : null }}>{{ $val['code'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary pull-right" onclick="app.folder.update(this);" data-toggle="tooltip" data-placement="left" title="@lang('Save your changes')">
                        <i class="fa fa-save"></i>&nbsp;
                        @lang('Save')
                    </button>
                </form>
            </div>
            <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">@lang('Add')</button>
                </div> -->
        </div>
    </div>
</div>

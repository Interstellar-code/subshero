@push('modal')

    <div id="modal_settings_tag_add" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add new tag')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="" id="frm_settings_tag_add" action="{{ route('app/settings/tag/create') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="settings_tag_add_name" class="">@lang('Name')</label>
                                    <input name="name" id="settings_tag_add_name" type="text" class="form-control" required data-toggle="tooltip" data-placement="left" title="@lang('Add New Tag')">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary pull-right" onclick="app.settings.tag.create(this);" data-toggle="tooltip" data-placement="left" title="@lang('Add your tag')">
                            <i class="fa fa-plus"></i>&nbsp;
                            @lang('Add')
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="modal_settings_tag_edit" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Update tag')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="" id="frm_settings_tag_edit" action="{{ route('app/settings/tag/update') }}" method="POST">
                        @csrf
                        <input name="id" id="settings_tag_edit_id" type="hidden" value="0">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="settings_tag_edit_name" class="">@lang('Name')</label>
                                    <input name="name" id="settings_tag_edit_name" type="text" class="form-control" required data-toggle="tooltip" data-placement="left" title="@lang('Edit Tag Name')">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary pull-right" onclick="app.settings.tag.update(this);" data-toggle="tooltip" data-placement="left" title="@lang('Save your changes')">
                            <i class="fa fa-save"></i>&nbsp;
                            @lang('Save')
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endpush

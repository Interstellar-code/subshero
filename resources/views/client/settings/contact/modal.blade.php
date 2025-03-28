@push('modal')

    <div id="modal_settings_contact_add" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add new contact')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="" id="frm_settings_contact_add" action="{{ route('app/settings/contact/create') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-5">
                                <div class="position-relative form-group">
                                    <label for="settings_contact_add_name" class="">@lang('Name')</label>
                                    <input name="name" id="settings_contact_add_name" type="text" class="form-control" required data-toggle="tooltip" data-placement="left" title="@lang('Enter Contact Name')">
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="position-relative form-group">
                                    <label for="settings_contact_add_email" class="">@lang('Email')</label>
                                    <input name="email" id="settings_contact_add_email" type="email" class="form-control" required data-toggle="tooltip" data-placement="bottom" title="@lang('Enter Contact Email')">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="position-relative form-group">
                                    <label for="settings_contact_add_status" class="">@lang('Active')</label>
                                    <br>
                                    <input name="status" id="settings_contact_add_status" value="1" type="checkbox" data-toggle="tooltip" data-placement="right" title="@lang('Set active or inactive status')">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary pull-right" onclick="app.settings.contact.create(this);" data-toggle="tooltip" data-placement="left" title="@lang('Click on Add to Add Contact')">
                            <i class="fa fa-plus"></i>&nbsp;
                            @lang('Add')
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="modal_settings_contact_edit" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Update contact')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="" id="frm_settings_contact_edit" action="{{ route('app/settings/contact/update') }}" method="POST">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>

@endpush

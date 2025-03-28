<script>
    app.folder.o.all = [];
</script>

<a class="dropdown-item p-2 mt-4" href="javascript:void(0);">
    <div class="widget-content p-0" onclick="app.folder.sort_disable();">
        <div class="widget-content-wrapper">
            <div class="widget-content-left mr-4">
                <i class="icon fa fa-folder-open fa-2x"></i>
            </div>
            <div class="widget-content-left w-100">
                <div class="folder_text">@lang('All Folders')</div>
            </div>
        </div>
    </div>
</a>

@foreach (lib()->folder->get_by_user() as $val)
    <a class="dropdown-item p-2 {{ isset($_SESSION['subscription_folder_id']) && $_SESSION['subscription_folder_id'] == $val->id ? 'active' : null }}" data-id="{{ $val->id }}" href="javascript:void(0);">

        <div class="widget-content p-0">
            <div class="widget-content-wrapper">
                <div class="widget-content-left mr-4" onclick="app.subscription.get_by_folder('{{ $val->id }}');">
                    <i class="icon fa fa-folder-open fa-2x"></i>
                </div>
                <div class="widget-content-left w-85">
                    <div class="folder_text d-flex justify-content-between">
                        <span class="truncate" onclick="app.subscription.get_by_folder('{{ $val->id }}');">
                            {{ $val->name }} <span class="badge badge_recurring">{{ lib()->config->currency_symbol[$val->price_type] ?? 'All' }}</span>
                        </span>
                        <button onclick="app.folder.edit(this, '{{ $val->id }}');" title="@lang('Edit')" class="btn-icon btn-icon-only btn-pill btn btn-outline-primary btn-sm" data-toggle="tooltip" data-placement="top">
                            <i class="fa fa-edit"></i>
                        </button>
                        &nbsp;
                        <button onclick="app.folder.delete(this, '{{ $val->id }}');" title="@lang('Delete')" class="btn-icon btn-icon-only btn-pill btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top">
                            <i class="fa fa-trash-alt"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </a>
@endforeach


<script>
    $(document).ready(function() {
        // lib.Reset_SelectBox(app.subscription.e.add.folder);
        // if (app.folder.o.all.length && Array.isArray(app.folder.o.all)) {
        //     app.folder.o.all.forEach(function(item) {
        //         $(app.subscription.e.add.folder).append('<option value="' + item.id + '">' + item.name + '</option>');
        //     });
        // }

        // $(app.subscription.e.folder).append();
    });
</script>

<div class="btn_expand_container btn_expand_hover mx-auto">
    {{-- <button class="btn btn_collapse" onclick="app.subscription.pause(this);" data-toggle="tooltip" data-placement="top" title="@lang('Pause')">
        <span class="fa fa-pause"></span>
    </button> --}}

    <button class="btn btn_collapse warning" onclick="app.subscription.attachment(this);" data-toggle="tooltip" data-placement="top" title="@lang('Attachment')">
        <span class="badge p-0 m-0">
            <i class="fa fa-paperclip fa-lg"></i>
            <span class="attachment_count">{{ $val->attachment_count }}</span>
        </span>
    </button>

    @if ($val->type == 1)
        <button class="btn btn_collapse primary" onclick="app.subscription.history(this);" data-toggle="tooltip" data-placement="top" title="@lang('History')">
            <i class="fa fa-history mb-2"></i>
        </button>
    @endif

    {{-- @if (empty($val->sub_id) && $val->status == 1)
        <button class="btn btn_collapse black" onclick="app.subscription.addon(this);" data-toggle="tooltip" data-placement="top" title="@lang('Addon')">
            <span class="fa fa-puzzle-piece mb-2"></span>
        </button>
    @endif --}}

    @if ($val->status == 1)
        <button class="btn btn_collapse warning" onclick="app.subscription.refund(this);" data-toggle="tooltip" data-placement="top" title="@lang('Refund')">
            <span class="fa fa-hand-holding-usd mb-2"></span>
        </button>
    @endif

    <button class="btn btn_collapse primary" onclick="app.subscription.clone(this);" data-toggle="tooltip" data-placement="top" title="@lang('Clone')">
        <ion-icon name="copy-outline" size="small"></ion-icon>
    </button>
    <button class="btn btn_collapse danger" onclick="app.subscription.delete(this);" data-toggle="tooltip" data-placement="top" title="@lang('Delete')">
        <ion-icon name="trash-outline" size="small"></ion-icon>
    </button>

    {{-- Button display expect cancel status --}}
    @unless(in_array($val->status, [0, 2, 3, 4, 5]))
        <button class="btn btn_collapse warning" onclick="app.subscription.cancel(this);" data-toggle="tooltip" data-placement="top" title="@lang('Cancel')">
            <ion-icon name="close-circle-outline" size="small"></ion-icon>
        </button>
    @endunless

    <button class="btn btn_toggle" data-toggle="tooltip" data-placement="top" title="@lang('Actions')">
        <ion-icon name="settings-outline" size="small"></ion-icon>
    </button>
</div>

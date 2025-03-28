<div id="modal_subscription_adapt_edit" class="modal fade subscription_history_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

        </div>
    </div>
</div>


<div id="modal_subscription_pdf_import" class="modal fade subscription_history_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <span>@lang('Invoice Parser')</span>
                </h5>
                <button type="button" class="btn_close btn_ripple" data-dismiss="modal" aria-label="Close">
                    <img class="icon" src="{{ asset_ver('assets/icons/close.svg') }}">
                </button>
            </div>
            <div class="modal-body">
                <div class="step_1 mb-3">
                    <div class="input-group">
                        <img id="subscription_pdf_import_draggable" class="img-fluid w-100" src="{{ asset_ver('assets/images/subscription_pdf/drop_area.svg') }}" onclick="$(this).siblings('input').click();">
                        <input type="file" class="form-control d-none" onchange="app.subscription.pdf.import(this);">
                    </div>
                </div>
                <div class="step_2">
                    <div class="container text-center pt-4" style="display: none;">
                        <img class="img-fluid" src="{{ asset_ver('assets/images/subscription_pdf/scan.gif') }}">
                        <p class="h5">@lang('Scanning Documents')</p>
                        <p class="m-0">@lang('It might take upto 30 seconds')</p>
                    </div>
                </div>
                <div class="step_3" style="display: none;">
                    <p class="h5">@lang('Detected Subs'):</p>
                    <div class="step_3_container"></div>
                </div>
            </div>
        </div>
    </div>
</div>

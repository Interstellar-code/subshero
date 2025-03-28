
<div id="product_adapt_edit_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header py-0 pl-3">
                <div class="" style="width: 100px; ">
                    <input type="file" class="filepond m-0" id="product_adapt_edit_image_favicon_file" name="imag_favicon" accept="image/*" data-size="100x100" style="display: none;">
                </div>
                <h5 class="modal-title">@lang('Product Adapt')</h5>

                <div class="header_toggle_btn_container toggle btn btn-warning mr-3" data-toggle="toggle" style="min-width: 80px;">
                    <input type="checkbox" id="product_adapt_edit_status_toggle" value="1" data-toggle="toggle" data-onstyle="success" data-offstyle="danger" checked>
                    <div class="toggle-group">
                        <label class="btn btn-success toggle-on">@lang('Active')</label>
                        <label class="btn btn-danger toggle-off">@lang('Inactive')</label>
                        <span class="toggle-handle btn btn-light"></span>
                    </div>
                </div>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        $('#product_add_url').on('change', function(e) {

            let url = e.target.value;
            if (url) {
                if (url.substr(url.length - 1) == '/') {
                    favicon_url = url + 'favicon.ico';
                } else {
                    favicon_url = url + '/favicon.ico';
                }

                $('#product_add_favicon_img').attr('src', favicon_url);
            } else {
                $('#product_add_favicon_img').attr('src', null);
            }

            $('#product_add_favicon_img').on('load', function(e) {
                e.target.style.display = '';
            }).on('error', function(e) {
                e.target.style.display = 'none';
            });
        });

        app.ui.btn_toggle();



        // Edit
        $(document).on('click', '#product_adapt_edit_modal .toggle[data-toggle="toggle"]', function() {

            let checkbox = $('#product_adapt_edit_status_toggle');
            if (checkbox.length) {
                $('#product_adapt_edit_status').val(checkbox.get(0).checked ? '1' : '0');
            }
        });

    })
</script>

<div id="modal_search_add" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <!-- <form class="" id="frm_search_add" method="POST"> -->
                @csrf
                <div class="input-group input-group-lg">
                    <div class="input-group-prepend">
                        <label for="search_add_name" class="input-group-text">
                            <i class="fa fa-search"></i>
                        </label>
                    </div>
                    <input name="name" id="search_add_name" type="text" class="form-control" placeholder="@lang('Search')" required>
                </div>
                <!-- </form> -->
            </div>
        </div>
    </div>
</div>

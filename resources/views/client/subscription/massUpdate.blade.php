@extends('client/layouts/default')



@section('head')
@endsection

@section('content')
    <style>
        .subscription_page_elements {
            display: block;
        }
        .list-loading {
            position: relative;
            min-height: 100px;
        }
        .list-loading [list-control="table-container"] {
            opacity: 0.4;
        }
        .loader {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            position: relative;
            animation: rotate 1s linear infinite;
            display: block;
            position: absolute;
            left: calc(50% - 24px);
            top: 50px;
        }
        .loader::before , .loader::after {
            content: "";
            box-sizing: border-box;
            position: absolute;
            inset: 0px;
            border-radius: 50%;
            border: 5px solid rgb(249, 201, 22);
            animation: prixClipFix 2s linear infinite ;
        }
        .loader::after{
            inset: 8px;
            transform: rotate3d(90, 90, 0, 180deg );
            border-color: #FF3D00;
        }

        .loader.loader-sm {
            width: 20px;
            height: 20px;
            top: auto;
            z-index: 2;
        }

        @keyframes rotate {
            0%   {transform: rotate(0deg)}
            100%   {transform: rotate(360deg)}
        }

        @keyframes prixClipFix {
            0%   {clip-path:polygon(50% 50%,0 0,0 0,0 0,0 0,0 0)}
            50%  {clip-path:polygon(50% 50%,0 0,100% 0,100% 0,100% 0,100% 0)}
            75%, 100%  {clip-path:polygon(50% 50%,0 0,100% 0,100% 100%,100% 100%,100% 100%)}
        }
        .toggle-slider {
            width: 70px;
            height: 30px;
            top: 0;
            left: 0;
            position: absolute;
            overflow: hidden;
            border: solid 1px #aaa;
            border-radius: 4px;
        }
        .toggle-slider-inner {
            display: flex;
            width: 155px;
            position: absolute;
            transition: all 0.3s ease-in-out;
            margin-left: -70px;
        }
        .checkbox-toggle {
            opacity: 0;
        }
        .checkbox-toggle-anchor {
            width: 30px;
            height: 32px;
            background: #fff;
            border-radius: 4px;
            cursor: pointer;
            margin-left: -5px;
            margin-right: -5px;
            z-index: 2;
            border: solid 1px #aaa;
            margin-top: -1px;
            margin-bottom: -1px;
        }
        .checkbox-toggle-label-off, .checkbox-toggle-label-on {
            width: 60px;
            height: 30px;
            text-align: center;
            padding: 4px;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
        }
        .checkbox-toggle-label-off:hover, .checkbox-toggle-label-on:hover {
            opacity: 0.9;
        }
        .checkbox-toggle-label-off {
            background: #f7b924;
        }
        .checkbox-toggle-label-on {
            background: #3ac47d;
            color: #fff;
        }
        .checkbox-toggle:checked ~ .toggle-slider .toggle-slider-inner {
            margin-left: 0;
        }
    </style>
    
    <div>
        <div class="d-flex align-items-center mb-4">
            <h2 class="mb-0">@lang('Bulk Edit')</h2>
            <div class="ml-auto">
                <form list-control="mass-update-form" method="POST" action="{{ action('App\Http\Controllers\Client\SubscriptionController@massUpdateSaveAll') }}">
                    @csrf
                    <!-- if folders is empty so no need to mass update by folder -->
                    
                    <div class="d-flex align-items-center mb-3">
                        <div list-control="item-selected-label" class="mr-4">

                        </div>
                        
                        <div class="mr-3">
                            <div class="position-relative form-group mb-0" data-toggle="tooltip" data-placement="left" title="@lang('Add tags')">
                                <select style="width: 150px;" name="add_tags[]" id="subscription_mass_add_tags" class="form-control" multiple placeholder="@lang('Tags')">
                                    @foreach (lib()->tag->get_by_user() as $val)
                                        <option value="{{ $val->name }}">+ {{ $val->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mr-3">
                            <div class="position-relative form-group mb-0" data-toggle="tooltip" data-placement="left" title="@lang('Remove tags')">
                                <select style="width: 150px;" name="remove_tags[]" id="subscription_remove_tags" class="form-control" multiple placeholder="@lang('Tags')">
                                    @foreach (lib()->tag->get_by_user() as $val)
                                        <option value="{{ $val->name }}">- {{ $val->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @if (count($folders))
                            <div class="mr-3">
                                <select name="folder_id" class="form-control mb-0" data-toggle="tooltip" data-placement="bottom" title="@lang('Select folder')">
                                    <option value="">@lang('Folder')</option>
                                    @foreach ($folders as $folder)
                                        <option value="{{ $folder->id }}">{{ $folder->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div class="mr-3">
                            <select name="status" class="form-control mb-0" data-toggle="tooltip" data-placement="bottom" title="@lang('Select status')">
                                <option value="">@lang('Status')</option>
                                <option value="0">@lang('Draft')</option>
                                <option value="1">@lang('Active')</option>
                            </select>
                        </div>
                        <div>
                            <button mass-update-control="update" type="button" class="btn btn-primary btn-sm" style="height:38px;padding:8px 20px!important">
                                <span class="btn-icon-wrapper pr-1 opacity-7">
                                    <i class="fa fa-plus"></i>
                                </span>
                                @lang('Update')
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="mb-2">
            <div class="d-flex align-items-center">
                <div>
                    <label class="d-flex align-items-center">
                        <span class="mr-2">@lang('Show')</span>
                        <span class="mr-2">
                            <select name="per_page" list-control="per_page" class="custom-select custom-select-sm form-control form-control-sm">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </span>
                        <span>@lang('entries')</span>
                    </label>
                </div>
                <div class="ml-auto">
                    <label class="d-flex align-items-center">
                        <div class="mr-2">
                            <input list-control="search_input" name="keyword" style="height:38px;width:200px;" 
                                type="search" class="form-control form-control-sm"
                                placeholder="@lang('Look for a subscription')"
                            >
                        </div>
                        <div>
                            <button list-control="search_button" type="button" class="btn btn-primary btn-sm" style="height:38px;padding:8px 10px!important">
                                <span class="btn-icon-wrapper pr-1 opacity-7">
                                    <i class="fa fa-search"></i>
                                </span>
                            </button>
                        </div>
                    </label>
                </div>
            </div>
        </div>
        <div class="">
            <div id="ListContainer">
            </div>
        </div>
    </div>

    <script>
        $(function() {
            // init page
            SubscriptionMassUpdate.init();

            // 
            $('#subscription_mass_add_tags').select2({
                tags: true,
                theme: 'bootstrap4',
                placeholder: 'Add Tags',
                insertTag: function (data, tag) {
                    // Disable selecting numeric value
                    if (isNaN(tag.text)) {
                        data.push(tag);
                    }
                },
            });

            // 
            $('#subscription_remove_tags').select2({
                tags: true,
                theme: 'bootstrap4',
                placeholder: 'Remove Tags',
                insertTag: function (data, tag) {
                    // Disable selecting numeric value
                    if (isNaN(tag.text)) {
                        data.push(tag);
                    }
                },
            });
        });

        var InlineEditableList = class {
            constructor(options) {
                this.url = options.url;
                this.container = document.querySelector(options.selector);
                this.sort = {
                    name: null,
                    direction: null
                };
                this.perPage = 10;
                this.keyword = '';
                this.filters = {};

                // set type filter
                this.updateTypeFilter();

                // init some default values
                this.init();

                // load list
                this.load();
            }

            setFilter(name, data) {
                this.filters[name] = data;

                console.log(this.filters);
            }

            getFilters() {
                return this.filters;
            }

            getFilter(key) {
                return this.getFilters()[key];
            }

            getPerPage() {
                return this.perPage;
            }

            setPerPage(per_page) {
                this.perPage = per_page;
            }

            getKeyword() {
                return this.keyword.trim();
            }

            setKeyword(keyword) {
                this.keyword = keyword;
            }

            getPerPageControls() {
                return document.querySelectorAll('[list-control=per_page]');
            }

            getSearchInput() {
                return document.querySelector('[list-control=search_input]');
            }

            getSearchButton() {
                return document.querySelector('[list-control=search_button]');
            }

            getTypeFilters() {
                return document.querySelectorAll('[list-control="filter-type"]');
            }

            getCheckedTypeFilter() {
                return document.querySelector('[list-control="filter-type"]:checked');
            }

            getRowCheckers() {
                return this.container.querySelectorAll('[list-control="row-checker"]');
            }

            getCheckedRowCheckers() {
                return this.container.querySelectorAll('[list-control="row-checker"]:checked');
            }

            getCheckedIds() {
                var ids = [];
                this.getCheckedRowCheckers().forEach(checker => {
                    ids.push(checker.value);
                });

                return ids;
            }

            getAllChecker() {
                return this.container.querySelector('[list-control="all-checker"]');
            }

            getItemsSelectedLabel() {
                return document.querySelector('[list-control="item-selected-label"]');
            }

            getCheckedTypeFilterValue() {
                return this.getCheckedTypeFilter().value;
            }

            updateTypeFilter() {
                this.filters.type = this.getCheckedTypeFilterValue();
            }

            getMassUpdateForm() {
                return document.querySelector('[list-control="mass-update-form"]');
            }

            getMassUpdateButton() {
                return document.querySelector('[mass-update-control="update"]');
            }

            getMassUpdateURL() {
                return this.getMassUpdateForm().getAttribute('action');
            }

            massUpdate() {
                var _thisList = this;

                // data
                var data = this.getListData();
                data.mass_tags = $(this.getMassUpdateForm()).find('[name="add_tags[]"]').val();
                data.mass_remove_tags = $(this.getMassUpdateForm()).find('[name="remove_tags[]"]').val();
                data.mass_folder_id = $(this.getMassUpdateForm()).find('[name="folder_id"]').val();
                data.mass_status = $(this.getMassUpdateForm()).find('[name="status"]').val();
                data.ids = this.getCheckedIds();

                // check selected row empty
                if (!data.ids.length) {
                    alert('@lang('Select at least one item from the list')');
                    return;
                }

                

                // loading effects
                _thisList.addLoadingEffect();

                $.ajax({
                    url: this.getMassUpdateURL(),
                    method: 'POST',
                    data: data
                }).done(function (response) {
                    // reload list
                    _thisList.load();
                }).fail(function (response) {

                }).always(function() {
                    // loading effects
                    // _thisList.removeLoadingEffect();
                });
            }

            init() {
                var _thisList = this;

                // set default per page
                this.getPerPageControls().forEach(control => {
                    $(control).val(_thisList.getPerPage());
                });;

                // update keyword when changing input
                this.getSearchInput().addEventListener("change", (e) => {
                    e.preventDefault();

                    // keyword
                    var keyword = _thisList.getSearchInput().value;

                    // update keyword
                    this.setKeyword(keyword);

                    // load list
                    _thisList.load();
                });

                // update keyword when hit enter
                this.getSearchInput().addEventListener("keyup", (e) => {
                    // when enter
                    if (e.key === "Enter") {
                        // Do work
                        _thisList.load();
                    }
                });

                // click on search button
                this.getSearchButton().addEventListener("click", (e) => {
                    e.preventDefault();

                    // if keyword is empty
                    if (!_thisList.getKeyword()) {
                        alert('@lang('Keyword is empty!')');
                        return;
                    }

                    // search
                    _thisList.load();
                });

                // change per page
                this.getPerPageControls().forEach(control => {
                    control.addEventListener("change", (e) => {
                        e.preventDefault();

                        var per_page = e.target.value;
                        _thisList.setPerPage(per_page);

                        // search
                        _thisList.load();
                    });
                });

                // change type
                this.getTypeFilters().forEach(control => {
                    control.addEventListener("change", (e) => {
                        // update type filter
                        _thisList.updateTypeFilter();

                        // search
                        _thisList.load();
                    });
                });

                // mass update
                this.getMassUpdateButton().addEventListener("click", (e) => {
                    // mass update
                    _thisList.massUpdate();
                });
            }

            setUrl(url) {
                this.url = url;
            }

            getUrl() {
                return this.url;
            }

            getContainer() {
                return this.container;
            }

            addLoadingEffect() {
                this.getContainer().classList.add('list-loading');
                $(this.getContainer()).prepend(`<span class="loader"></span>`);
            }

            removeLoadingEffect() {
                this.getContainer().classList.remove('list-loading');
            }

            getListData() {
                return {
                    _token: '{{ csrf_token() }}',
                    sort: this.getSort(),
                    keyword: this.getKeyword(),
                    per_page: this.getPerPage(),
                    filters: this.getFilters(),
                };
            }

            load(onload) {
                var _thisList = this;

                // loading effects
                this.addLoadingEffect();

                $.ajax({
                    url: this.getUrl(),
                    method: 'GET',
                    data: this.getListData()            
                }).done(function (response) {
                    _thisList.renderList(response);
                }).fail(function (response) {
                }).always(function() {
                    // loading effects
                    _thisList.removeLoadingEffect();
                });

                // // request ajax to the dataUrl to get list data
                // const xhr = new XMLHttpRequest();
                // xhr.open("GET", this.getUrl(), true);
                // xhr.getResponseHeader("Content-type", "application/json");
                // xhr.onload = function() {
                //     // set data
                //     _thisList.renderList(this.responseText);

                //     // after load callback
                //     if (onload) {
                //         onload();
                //     }
                // }

                // const params = {
                //     sort: this.getSort()
                // }
                // xhr.send(JSON.stringify(params));
            }

            sortList(name, direction) {
                this.setSort(name, direction);

                this.load();
            }

            setSort(name, direction) {
                this.sort = {
                    name: name,
                    direction: direction
                };
            }

            getSort() {
                return this.sort;
            }

            loadUrl(url) {
                this.setUrl(url);
                this.load();
            }

            renderList(html) {
                var _this = this;

                $(this.getContainer()).html(html); //.innerHTML = html;

                // update selected items label
                _this.updateItemsSelectedLabel();

                // after render events
                _this.afterRenderEvents();
            }

            afterRenderEvents() {
                var _this = this;
                this.getAllChecker().addEventListener('change', (e) => {
                    var checked = _this.getAllChecker().checked;

                    if (checked) {
                        _this.checkAllRowCheckers();
                    } else {
                        _this.uncheckAllRowCheckers();
                    }

                    // update selected items label
                    _this.updateItemsSelectedLabel();
                });

                // row checker change
                this.getRowCheckers().forEach((checker) => {
                    checker.addEventListener('change', (e) => {
                        var checked = checker.checked;

                        if (_this.getCheckedRowCheckers().length == _this.getRowCheckers().length) {
                            _this.getAllChecker().checked = true;
                        } else {
                            _this.getAllChecker().checked = false;
                        }

                        // update selected items label
                        _this.updateItemsSelectedLabel();
                    });
                    
                    
                })
            }

            updateItemsSelectedLabel() {
                var container = this.getItemsSelectedLabel();
                var selectedCount = this.getCheckedRowCheckers().length;

                if (selectedCount > 0) {
                    container.innerHTML = `
                        <span><strong>`+selectedCount+`</strong> @lang('items selected')</span>
                    `;
                } else {
                    container.innerHTML = '';
                }
            }

            checkAllRowCheckers() {
                this.getRowCheckers().forEach(checkbox => {
                    checkbox.checked = true;
                });
            }

            uncheckAllRowCheckers() {
                this.getRowCheckers().forEach(checkbox => {
                    checkbox.checked = false;
                });
            }

            clear() {
                this.getContainer().innerHTML = '';
            }
        }

        var SubscriptionMassUpdate = {
            init: function() {
                // select2 control helpers
                $('[name="tag[folder]"],[name="tag[value]"],[name="status[folder]"],[name="status[value]"]').select2({
                    tags: false,
                    theme: 'bootstrap4'
                });

                // init inline editable list
                this.list = new InlineEditableList({
                    url: '{{ action('App\Http\Controllers\Client\SubscriptionController@massUpdateList') }}',
                    selector: '#ListContainer',
                });
            },

            getList: function() {
                return this.list;
            }
        }

        // ListFilter class
        class ListFilter {
            constructor(list, container) {
                this.list = list;
                this.container = container;
                this.active = false;

                // events
                this.events();
            }

            isActive() {
                return this.active;
            }

            setActive() {
                this.active = true;
                this.container.classList.add('active');
            }

            removeActive() {
                this.active = false;
                this.container.classList.remove('active');
            }

            events() {
                var _this = this;

                // click on icon show form box
                this.getIconBox().addEventListener('click', (e) => {
                    _this.showFormBox();
                });

                // click outside form box
                $(document).click(function(event) { 
                    var $target = $(event.target);
                    if(!$target.closest('[filter-control="form-box"],[filter-control="icon-box"]').length && 
                    $('[filter-control="form-box"],[filter-control="icon-box"]').is(":visible")) {
                        _this.hideFormBox();
                    }        
                });
            }

            getList() {
                return this.list;
            }

            getContainer() {
                return this.container;
            }

            getIconBox() {
                return this.container.querySelector('[filter-control="icon-box"]');
            }

            getFormBox() {
                return this.container.querySelector('[filter-control="form-box"]');
            }

            getForm() {
                return this.container.querySelector('[filter-control="form"]');
            }

            showFormBox() {
                this.getFormBox().style.display = 'block';
            }

            hideFormBox() {
                this.getFormBox().style.display = 'none';
            }
        }
    </script>
@endsection

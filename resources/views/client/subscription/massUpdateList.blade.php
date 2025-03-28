<style>
    .list-sort-up {
        margin-right:-12.1px;
    }
    .list-sort-down, .list-sort-up {
        opacity:0.3;
    }
    .list-sort-up.current, .list-sort-down.current  {
        opacity: 1;
    }
    .list-sort-button:active, .list-sort-button:focus {
        text-decoration: none;
    }

    .list-filter-button {
        opacity: 0.3;
        font-size: 11px;
    }
    .active .list-filter-button {
        opacity: 1;
    }
    .inline-saving {
        opacity: 0.3;
    }

    form .inline-button {
        padding: 2px!important;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 38px;
        width: 38px;
    }
    [inline-control="value-box"] {
        cursor: pointer;
        position: relative;
    }
    [inline-control="value-box"]:hover {
    }
    [inline-control="value-box"]:after {
        content: "\f044";
        pointer-events: none;
        position: absolute;
        font-family: 'Font Awesome 5 Free';
        top: 0;
        right: -20px;
        opacity: 0.4;
        display: none;
    }
    [inline-control="value-box"]:hover::after {
        display: block;
    }
    [filter-control="form-box"] {
        position: absolute;
        background: #fff;
        padding: 10px;
        border: solid 1px #ddd;
        right: 0;
        z-index: 3;
        top: 29px;
        border-radius: 2px;
        box-shadow: 1px 1px 7px rgba(0,0,0,0.07);
    }
</style>

<div list-control="table-container" class="table-responsive" style="min-height:300px;">
    <table class="table">
        <thead>
            <tr>
                <!-- List checker -->
                <th class="text-nowrap">
                    <div class="d-flex align-items-center" style="height:100%">
                        <input list-control="all-checker" style="width:17px" type="checkbox"
                            name="check_all"
                            value="all"
                        />
                    </div>
                </th>

                <!-- Name -->
                <th class="text-nowrap">
                    <span>
                        @lang('Name')
                    </span>
                    <a href="javascript:;" class="ml-1 list-sort-button" list-control="sort" sort-name="product_name"
                        sort-direction="{{ $sort['name'] == 'product_name' && $sort['direction'] == 'asc' ? 'desc' : 'asc'  }}"
                    >
                        <span class="list-sort-icon">
                            <i class="fa fa-sort-up list-sort-up {{ $sort['name'] == 'product_name' && $sort['direction'] == 'asc' ? 'current' : ''  }}"></i>
                            <i class="fa fa-sort-down list-sort-down {{ $sort['name'] == 'product_name' && $sort['direction'] == 'desc' ? 'current' : ''  }}"></i>
                        </span>
                    </a>
                </th>

                <!-- Description -->
                <th class="text-nowrap">
                    <span>
                        @lang('Description')
                    </span>
                </th>

                <!-- Tags -->
                <th class="text-nowrap">
                    <span class="d-flex align-items-center">
                        <span>
                            @lang('Tags')
                        </span>

                        <div list-control="filter-tag" data-active="false" class="d-inline-block position-relative">
                            <span filter-control="icon-box">
                                <a href="javascript:;" class="ml-2 list-filter-button" filter-name="brand">
                                    <span class="list-filter-icon">
                                        <i class="fa fa-filter list-filter-icon"></i>
                                    </span>
                                </a>
                            </span>
                            <div filter-control="form-box" style="display:none">
                                <form filter-control="form">
                                    @csrf
                                    <select name="tag_id" class="form-control" style="width:150px;">
                                        @foreach (lib()->tag->get_by_user() as $val)
                                            <option value="{{ $val->id }}">{{ $val->name }}</option>
                                        @endforeach
                                    </select>

                                    <hr>
                                    <div class="text-center">
                                        <a href="javascript:;" style="padding: 8px 12px!important;font-size:14px;height:37px;" filter-control="save" class="btn btn-primary py-2 mr-1" title="@lang('Save')">
                                            @lang('Filter')
                                        </a>
                                        <a href="javascript:;" style="padding: 8px 12px!important;font-size:14px;height:37px;" filter-control="remove" class="btn btn-secondary py-2" title="@lang('Save')">
                                            @lang('Remove')
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </span>
                </th>

                <!-- Rating -->
                <th class="text-nowrap">
                    <span class="d-flex align-items-center">
                        <span>
                            @lang('Rating')
                        </span>
                        <a href="javascript:;" class="ml-1 list-sort-button" list-control="sort" sort-name="rating"
                            sort-direction="{{ $sort['name'] == 'rating' && $sort['direction'] == 'asc' ? 'desc' : 'asc'  }}"
                        >
                            <span class="list-sort-icon">
                                <i class="fa fa-sort-up list-sort-up {{ $sort['name'] == 'rating' && $sort['direction'] == 'asc' ? 'current' : ''  }}"></i>
                                <i class="fa fa-sort-down list-sort-down {{ $sort['name'] == 'rating' && $sort['direction'] == 'desc' ? 'current' : ''  }}"></i>
                            </span>
                        </a>
                        <div list-control="filter-rating" data-active="false" class="d-inline-block position-relative">
                            <span filter-control="icon-box">
                                <a href="javascript:;" class="ml-2 list-filter-button" filter-name="brand">
                                    <span class="list-filter-icon">
                                        <i class="fa fa-filter list-filter-icon"></i>
                                    </span>
                                </a>
                            </span>
                            <div filter-control="form-box" style="display:none">
                                <form filter-control="form">
                                    @csrf
                                    <label class="d-flex align-items-center">
                                        <div class="d-flex align-items-center">
                                            <input rating-control="all" style="width:15px" type="checkbox" name="all_stars" value="all" />
                                        </div>
                                        <div>
                                            @lang('All rating')
                                        </div>
                                    </label>
                                    <hr>
                                    @for($i = 0; $i <= 10; $i++)
                                        <label class="d-flex align-items-center">
                                            <div class="d-flex align-items-center">
                                                <input rating-control="star" data-value="{{ $i }}" style="width:15px" type="checkbox" name="stars[]" value="{{ $i }}" />
                                            </div>
                                            <div>
                                                {!! view('client/datatable/subscription/column_rating', [
                                                    'rating' => $i,
                                                ]) !!}
                                            </div>
                                            <div class="pl-3 ml-auto" style="width:30px;text-align:center;">
                                                {{ $i }}
                                            </div>
                                        </label>
                                    @endfor

                                    <hr>
                                    <div class="text-center">
                                        <a href="javascript:;" style="padding: 8px 12px!important;font-size:14px;height:37px;" filter-control="save" class="btn btn-primary py-2 mr-1" title="@lang('Save')">
                                            @lang('Filter')
                                        </a>
                                        <a href="javascript:;" style="padding: 8px 12px!important;font-size:14px;height:37px;" filter-control="remove" class="btn btn-secondary py-2" title="@lang('Save')">
                                            @lang('Remove')
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </span>
                </th>

                <!-- Price -->
                <th class="text-nowrap">
                    <span>
                        @lang('Price')
                    </span>
                    <a href="javascript:;" class="ml-1 list-sort-button" list-control="sort" sort-name="price"
                        sort-direction="{{ $sort['name'] == 'price' && $sort['direction'] == 'asc' ? 'desc' : 'asc'  }}"
                    >
                        <span class="list-sort-icon">
                            <i class="fa fa-sort-up list-sort-up {{ $sort['name'] == 'price' && $sort['direction'] == 'asc' ? 'current' : ''  }}"></i>
                            <i class="fa fa-sort-down list-sort-down {{ $sort['name'] == 'price' && $sort['direction'] == 'desc' ? 'current' : ''  }}"></i>
                        </span>
                    </a>
                </th>

                <!-- Due Date -->
                <th class="text-nowrap">
                    <span class="d-flex align-items-center">
                        <span>
                            @lang('Due Date')
                        </span>
                        <a href="javascript:;" class="ml-1 list-sort-button" list-control="sort" sort-name="next_payment_date"
                            sort-direction="{{ $sort['name'] == 'next_payment_date' && $sort['direction'] == 'asc' ? 'desc' : 'asc'  }}"
                        >
                            <span class="list-sort-icon">
                                <i class="fa fa-sort-up list-sort-up {{ $sort['name'] == 'next_payment_date' && $sort['direction'] == 'asc' ? 'current' : ''  }}"></i>
                                <i class="fa fa-sort-down list-sort-down {{ $sort['name'] == 'next_payment_date' && $sort['direction'] == 'desc' ? 'current' : ''  }}"></i>
                            </span>
                        </a>
                        <div list-control="filter-due" data-active="false" class="d-inline-block position-relative">
                            <span filter-control="icon-box">
                                <a href="javascript:;" class="ml-2 list-filter-button" filter-name="brand">
                                    <span class="list-filter-icon">
                                        <i class="fa fa-filter list-filter-icon"></i>
                                    </span>
                                </a>
                            </span>
                            <div filter-control="form-box" style="display:none">
                                <form filter-control="form">
                                    @csrf

                                    <div class="input-group d-flex" style="flex-wrap:nowrap">
                                        <input name="due_date"
                                            style="width:140px"
                                            type="text" placeholder="yyyy-mm-dd"
                                            value="{{ Carbon\Carbon::now()->format('Y-m-d') }}"
                                            maxlength="10" class="form-control" data-toggle="datepicker-and-icon"
                                        />
                                        <div class="input-group-append datepicker-trigger" style="    position: absolute;
                                        pointer-events: none;
                                        right: 10px;
                                        top: 9px;
                                        z-index: 3;
                                        background: transparent;">
                                            <div class="">
                                                <i class="fa fa-calendar-alt"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>
                                    <div class="text-center">
                                        <a href="javascript:;" style="padding: 8px 12px!important;font-size:14px;height:37px;" filter-control="save" class="btn btn-primary py-2 mr-1" title="@lang('Save')">
                                            @lang('Filter')
                                        </a>
                                        <a href="javascript:;" style="padding: 8px 12px!important;font-size:14px;height:37px;" filter-control="remove" class="btn btn-secondary py-2" title="@lang('Save')">
                                            @lang('Remove')
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </span>
                </th>

                <!-- Refund Date -->
                <th class="text-nowrap">
                    <span class="d-flex align-items-center">
                        <span>
                            @lang('Refund Date')
                        </span>
                        <a href="javascript:;" class="ml-1 list-sort-button" list-control="sort" sort-name="refund_date"
                            sort-direction="{{ $sort['name'] == 'refund_date' && $sort['direction'] == 'asc' ? 'desc' : 'asc'  }}"
                        >
                            <span class="list-sort-icon">
                                <i class="fa fa-sort-up list-sort-up {{ $sort['name'] == 'refund_date' && $sort['direction'] == 'asc' ? 'current' : ''  }}"></i>
                                <i class="fa fa-sort-down list-sort-down {{ $sort['name'] == 'refund_date' && $sort['direction'] == 'desc' ? 'current' : ''  }}"></i>
                            </span>
                        </a>
                        <div list-control="filter-refund" data-active="false" class="d-inline-block position-relative">
                            <span filter-control="icon-box">
                                <a href="javascript:;" class="ml-2 list-filter-button" filter-name="brand">
                                    <span class="list-filter-icon">
                                        <i class="fa fa-filter list-filter-icon"></i>
                                    </span>
                                </a>
                            </span>
                            <div filter-control="form-box" style="display:none">
                                <form filter-control="form">
                                    @csrf

                                    <div class="input-group d-flex" style="flex-wrap:nowrap">
                                        <input name="refund_date"
                                            style="width:140px"
                                            type="text" placeholder="yyyy-mm-dd"
                                            value="{{ Carbon\Carbon::now()->format('Y-m-d') }}"
                                            maxlength="10" class="form-control" data-toggle="datepicker-and-icon"
                                        />
                                        <div class="input-group-append datepicker-trigger" style="    position: absolute;
                                        pointer-events: none;
                                        right: 10px;
                                        top: 9px;
                                        z-index: 3;
                                        background: transparent;">
                                            <div class="">
                                                <i class="fa fa-calendar-alt"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>
                                    <div class="text-center">
                                        <a href="javascript:;" style="padding: 8px 12px!important;font-size:14px;height:37px;" filter-control="save" class="btn btn-primary py-2 mr-1" title="@lang('Save')">
                                            @lang('Filter')
                                        </a>
                                        <a href="javascript:;" style="padding: 8px 12px!important;font-size:14px;height:37px;" filter-control="remove" class="btn btn-secondary py-2" title="@lang('Save')">
                                            @lang('Remove')
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </span>
                </th>

                <!-- Expiry Date -->
                <th class="text-nowrap">
                    <span class="d-flex align-items-center">
                        <span>
                            @lang('Expiry Date')
                        </span>
                        <a href="javascript:;" class="ml-1 list-sort-button" list-control="sort" sort-name="expiry_date"
                            sort-direction="{{ $sort['name'] == 'expiry_date' && $sort['direction'] == 'asc' ? 'desc' : 'asc'  }}"
                        >
                            <span class="list-sort-icon">
                                <i class="fa fa-sort-up list-sort-up {{ $sort['name'] == 'expiry_date' && $sort['direction'] == 'asc' ? 'current' : ''  }}"></i>
                                <i class="fa fa-sort-down list-sort-down {{ $sort['name'] == 'expiry_date' && $sort['direction'] == 'desc' ? 'current' : ''  }}"></i>
                            </span>
                        </a>
                        <div list-control="filter-expiry" data-active="false" class="d-inline-block position-relative">
                            <span filter-control="icon-box">
                                <a href="javascript:;" class="ml-2 list-filter-button" filter-name="brand">
                                    <span class="list-filter-icon">
                                        <i class="fa fa-filter list-filter-icon"></i>
                                    </span>
                                </a>
                            </span>
                            <div filter-control="form-box" style="display:none">
                                <form filter-control="form">
                                    @csrf

                                    <div class="input-group d-flex" style="flex-wrap:nowrap">
                                        <input name="expiry_date"
                                            style="width:140px"
                                            type="text" placeholder="yyyy-mm-dd"
                                            value="{{ Carbon\Carbon::now()->format('Y-m-d') }}"
                                            maxlength="10" class="form-control" data-toggle="datepicker-and-icon"
                                        />
                                        <div class="input-group-append datepicker-trigger" style="    position: absolute;
                                        pointer-events: none;
                                        right: 10px;
                                        top: 9px;
                                        z-index: 3;
                                        background: transparent;">
                                            <div class="">
                                                <i class="fa fa-calendar-alt"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>
                                    <div class="text-center">
                                        <a href="javascript:;" style="padding: 8px 12px!important;font-size:14px;height:37px;" filter-control="save" class="btn btn-primary py-2 mr-1" title="@lang('Save')">
                                            @lang('Filter')
                                        </a>
                                        <a href="javascript:;" style="padding: 8px 12px!important;font-size:14px;height:37px;" filter-control="remove" class="btn btn-secondary py-2" title="@lang('Save')">
                                            @lang('Remove')
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </span>
                </th>

                <!-- Payment Mode -->
                <th class="text-nowrap">
                    <span class="d-flex align-items-center">
                        <span>
                            @lang('Payment Mode')
                        </span>
                        <a href="javascript:;" class="ml-1 list-sort-button" list-control="sort" sort-name="payment_mode"
                            sort-direction="{{ $sort['name'] == 'payment_mode' && $sort['direction'] == 'asc' ? 'desc' : 'asc'  }}"
                        >
                            <span class="list-sort-icon">
                                <i class="fa fa-sort-up list-sort-up {{ $sort['name'] == 'payment_mode' && $sort['direction'] == 'asc' ? 'current' : ''  }}"></i>
                                <i class="fa fa-sort-down list-sort-down {{ $sort['name'] == 'payment_mode' && $sort['direction'] == 'desc' ? 'current' : ''  }}"></i>
                            </span>
                        </a>
                        <div list-control="filter-payment" data-active="false" class="d-inline-block position-relative">
                            <span filter-control="icon-box">
                                <a href="javascript:;" class="ml-2 list-filter-button" filter-name="brand">
                                    <span class="list-filter-icon">
                                        <i class="fa fa-filter list-filter-icon"></i>
                                    </span>
                                </a>
                            </span>
                            <div filter-control="form-box" style="display:none">
                                <form filter-control="form">
                                    @csrf

                                    <select style="width:150px" name="payment_mode_id" class="form-control" required data-toggle="tooltip" >
                                        @foreach (lib()->user->payment_methods as $val)
                                            <option value="{{ $val->id }}">{{ $val->name }}</option>
                                        @endforeach
                                    </select>

                                    <hr>
                                    <div class="text-center">
                                        <a href="javascript:;" style="padding: 8px 12px!important;font-size:14px;height:37px;" filter-control="save" class="btn btn-primary py-2 mr-1" title="@lang('Save')">
                                            @lang('Filter')
                                        </a>
                                        <a href="javascript:;" style="padding: 8px 12px!important;font-size:14px;height:37px;" filter-control="remove" class="btn btn-secondary py-2" title="@lang('Save')">
                                            @lang('Remove')
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>

                        
                    </span>
                </th>

                <!-- Categories -->
                <th class="text-nowrap">
                    <span class="d-flex align-items-center">
                        <span>
                            @lang('Categories')
                        </span>
                        <a href="javascript:;" class="ml-1 list-sort-button" list-control="sort" sort-name="category_id"
                            sort-direction="{{ $sort['name'] == 'category_id' && $sort['direction'] == 'asc' ? 'desc' : 'asc'  }}"
                        >
                            <span class="list-sort-icon">
                                <i class="fa fa-sort-up list-sort-up {{ $sort['name'] == 'category_id' && $sort['direction'] == 'asc' ? 'current' : ''  }}"></i>
                                <i class="fa fa-sort-down list-sort-down {{ $sort['name'] == 'category_id' && $sort['direction'] == 'desc' ? 'current' : ''  }}"></i>
                            </span>
                        </a>
                        <div list-control="filter-category" data-active="false" class="d-inline-block position-relative">
                            <span filter-control="icon-box">
                                <a href="javascript:;" class="ml-2 list-filter-button" filter-name="brand">
                                    <span class="list-filter-icon">
                                        <i class="fa fa-filter list-filter-icon"></i>
                                    </span>
                                </a>
                            </span>
                            <div filter-control="form-box" style="display:none">
                                <form filter-control="form">
                                    @csrf

                                    <select style="width:150px" name="category_id" class="form-control" required data-toggle="tooltip" >
                                        <option value=''>@lang('Select category')</option>
                                        @foreach (App\Models\ProductCategory::all() as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>

                                    <hr>
                                    <div class="text-center">
                                        <a href="javascript:;" style="padding: 8px 12px!important;font-size:14px;height:37px;" filter-control="save" class="btn btn-primary py-2 mr-1" title="@lang('Save')">
                                            @lang('Filter')
                                        </a>
                                        <a href="javascript:;" style="padding: 8px 12px!important;font-size:14px;height:37px;" filter-control="remove" class="btn btn-secondary py-2" title="@lang('Save')">
                                            @lang('Remove')
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </span>
                </th>

                <!-- Folder Name -->
                <th class="text-nowrap">
                    <span class="d-flex align-items-center">
                        <span>
                            @lang('Folder Name')
                        </span>
                        <a href="javascript:;" class="ml-1 list-sort-button" list-control="sort" sort-name="folder_id"
                            sort-direction="{{ $sort['name'] == 'folder_id' && $sort['direction'] == 'asc' ? 'desc' : 'asc'  }}"
                        >
                            <span class="list-sort-icon">
                                <i class="fa fa-sort-up list-sort-up {{ $sort['name'] == 'folder_id' && $sort['direction'] == 'asc' ? 'current' : ''  }}"></i>
                                <i class="fa fa-sort-down list-sort-down {{ $sort['name'] == 'folder_id' && $sort['direction'] == 'desc' ? 'current' : ''  }}"></i>
                            </span>
                        </a>
                        <div list-control="filter-folder" data-active="false" class="d-inline-block position-relative">
                            <span filter-control="icon-box">
                                <a href="javascript:;" class="ml-2 list-filter-button" filter-name="brand">
                                    <span class="list-filter-icon">
                                        <i class="fa fa-filter list-filter-icon"></i>
                                    </span>
                                </a>
                            </span>
                            <div filter-control="form-box" style="display:none">
                                <form filter-control="form">
                                    @csrf

                                    <select style="width:150px" name="folder_id" class="form-control" required data-toggle="tooltip" >
                                        <option value="">@lang('Select folder')</option>
                                        @foreach ($folders as $folder)
                                            <option value="{{ $folder->id }}">{{ $folder->name }}</option>
                                        @endforeach
                                    </select>

                                    <hr>
                                    <div class="text-center">
                                        <a href="javascript:;" style="padding: 8px 12px!important;font-size:14px;height:37px;" filter-control="save" class="btn btn-primary py-2 mr-1" title="@lang('Save')">
                                            @lang('Filter')
                                        </a>
                                        <a href="javascript:;" style="padding: 8px 12px!important;font-size:14px;height:37px;" filter-control="remove" class="btn btn-secondary py-2" title="@lang('Save')">
                                            @lang('Remove')
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </span>
                </th>

                <!-- Status -->
                <th class="text-nowrap">
                    <span class="d-flex align-items-center">
                        <span>
                            @lang('Status')
                        </span>
                        <a href="javascript:;" class="ml-2 list-sort-button" list-control="sort" sort-name="status"
                            sort-direction="{{ $sort['name'] == 'status' && $sort['direction'] == 'asc' ? 'desc' : 'asc'  }}"
                        >
                            <span class="list-sort-icon">
                                <i class="fa fa-sort-up list-sort-up {{ $sort['name'] == 'status' && $sort['direction'] == 'asc' ? 'current' : ''  }}"></i>
                                <i class="fa fa-sort-down list-sort-down {{ $sort['name'] == 'status' && $sort['direction'] == 'desc' ? 'current' : ''  }}"></i>
                            </span>
                        </a>
                        <div list-control="filter-status" data-active="false" class="d-inline-block position-relative">
                            <span filter-control="icon-box">
                                <a href="javascript:;" class="ml-2 list-filter-button" filter-name="brand">
                                    <span class="list-filter-icon">
                                        <i class="fa fa-filter list-filter-icon"></i>
                                    </span>
                                </a>
                            </span>
                            <div filter-control="form-box" style="display:none">
                                <form filter-control="form">
                                    @csrf
                                    <label class="position-relative" style="width:70px;">
                                        <input list-control="status-checkbox" type="checkbox" name="status" value="1" class="checkbox-toggle">
                                        <span class="toggle-slider">
                                            <span class="toggle-slider-inner">
                                                <span class="checkbox-toggle-label-on">@lang('Active')</span>
                                                <span class="checkbox-toggle-anchor"></span>
                                                <span class="checkbox-toggle-label-off">@lang('Draft')</span>
                                            </span>
                                        </span>
                                    </label>

                                    <hr>
                                    <div class="text-center">
                                        <a href="javascript:;" style="padding: 8px 12px!important;font-size:14px;height:37px;" filter-control="save" class="btn btn-primary py-2 mr-1" title="@lang('Save')">
                                            @lang('Filter')
                                        </a>
                                        <a href="javascript:;" style="padding: 8px 12px!important;font-size:14px;height:37px;" filter-control="remove" class="btn btn-secondary py-2" title="@lang('Save')">
                                            @lang('Remove')
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </span>
                </th>

                <!-- Product Name -->
                <th class="text-nowrap">
                    <span class="d-flex align-items-center">
                        <span>
                            @lang('Product Name')
                        </span>
                        <a href="javascript:;" class="ml-1 list-sort-button" list-control="sort" sort-name="product_name"
                            sort-direction="{{ $sort['name'] == 'product_name' && $sort['direction'] == 'asc' ? 'desc' : 'asc'  }}"
                        >
                            <span class="list-sort-icon">
                                <i class="fa fa-sort-up list-sort-up {{ $sort['name'] == 'product_name' && $sort['direction'] == 'asc' ? 'current' : ''  }}"></i>
                                <i class="fa fa-sort-down list-sort-down {{ $sort['name'] == 'product_name' && $sort['direction'] == 'desc' ? 'current' : ''  }}"></i>
                            </span>
                        </a>
                        <div list-control="filter-product-name" data-active="false" class="d-inline-block position-relative">
                            <span filter-control="icon-box">
                                <a href="javascript:;" class="ml-2 list-filter-button" filter-name="brand">
                                    <span class="list-filter-icon">
                                        <i class="fa fa-filter list-filter-icon"></i>
                                    </span>
                                </a>
                            </span>
                            <div filter-control="form-box" style="display:none">
                                <form filter-control="form">
                                    @csrf
                                    <div class="d-flex align-items-center">
                                        <input name="product_name"
                                            type="text"
                                            class="form-control"
                                            style="width:200px;margin-right:2px;"
                                            value=""
                                            placeholder="@lang('Search for product name')"
                                        />
                                    </div>

                                    <hr>
                                    <div class="text-center">
                                        <a href="javascript:;" style="padding: 8px 12px!important;font-size:14px;height:37px;" filter-control="save" class="btn btn-primary py-2 mr-1" title="@lang('Save')">
                                            @lang('Filter')
                                        </a>
                                        <a href="javascript:;" style="padding: 8px 12px!important;font-size:14px;height:37px;" filter-control="remove" class="btn btn-secondary py-2" title="@lang('Save')">
                                            @lang('Remove')
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </span>
                </th>

                <!-- Product Type -->
                <th class="text-nowrap">
                    <span class="d-flex align-items-center">
                        <span>
                            @lang('Product Type')
                        </span>
                        <a href="javascript:;" class="ml-1 list-sort-button" list-control="sort" sort-name="product_type"
                            sort-direction="{{ $sort['name'] == 'product_type' && $sort['direction'] == 'asc' ? 'desc' : 'asc'  }}"
                        >
                            <span class="list-sort-icon">
                                <i class="fa fa-sort-up list-sort-up {{ $sort['name'] == 'product_type' && $sort['direction'] == 'asc' ? 'current' : ''  }}"></i>
                                <i class="fa fa-sort-down list-sort-down {{ $sort['name'] == 'product_type' && $sort['direction'] == 'desc' ? 'current' : ''  }}"></i>
                            </span>
                        </a>
                        {{-- <a href="javascript:;" class="ml-1 list-filter-button" filter-name="brand">
                            <span class="list-filter-icon">
                                <i class="fa fa-filter list-filter-icon"></i>
                            </span>
                        </a> --}}
                    </span>
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($subscriptions as $subscription)
                <tr>
                    <!-- List checker -->
                    <td class="text-nowrap">
                        <div class="d-flex align-items-center" style="height:100%">
                            <input list-control="row-checker" style="width:17px" type="checkbox"
                                name="id[]"
                                value="{{ $subscription->id }}"
                            />
                        </div>
                    </td>

                    <!-- Name -->
                    <td>
                        <strong inline-control="value-container">
                            {{ $subscription->product_name }}
                        </strong>
                        {{-- <div class="pr-4" list-control="inline-names">
                            <div inline-control="value-box">
                                <strong inline-control="value-container">
                                    {{ $subscription->product_name }}
                                </strong>
                            </div>
                            <div inline-control="form-box" style="display:none;">
                                <form inline-control="form" method="POST" action="{{ action('App\Http\Controllers\Client\SubscriptionController@massUpdateSave') }}">
                                    @csrf
                                    <input type="hidden" name="subscription_id" value="{{ $subscription->id }}" />
                                    <div class="d-flex align-items-center">
                                        <div class="d-flex align-items-center">
                                            <input name="product_name"
                                                type="text"
                                                class="form-control"
                                                placeholder=""
                                                style="width:120px;margin-right:2px;"
                                                value="{{ $subscription->product_name }}"
                                            />
                                        </div>
                                        <div class="ml-1">
                                            <button inline-control="save" class="btn btn-primary inline-button" title="@lang('Save')">
                                                <span class="fa fa-save"></span>
                                            </button>
                                        </div>
                                        <div class="ml-1">
                                            <button inline-control="cancel" class="btn btn-light inline-button" title="@lang('Cancel')">
                                                <ion-icon name="close"></ion-icon>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div> --}}
                    </td>

                    <!-- Description -->
                    <td>
                        <div class="pr-4" list-control="inline-description">
                            <div inline-control="value-box" style="min-width:200px">
                                <span inline-control="value-container">
                                    {{ $subscription->description ?? 'N/A' }}
                                </span>
                            </div>
                            <div inline-control="form-box" style="display:none;">
                                <form inline-control="form" method="POST" action="{{ action('App\Http\Controllers\Client\SubscriptionController@massUpdateSave') }}">
                                    @csrf
                                    <input type="hidden" name="subscription_id" value="{{ $subscription->id }}" />
                                    <div class="d-flex align-items-top">
                                        <textarea name="description"
                                            style="width:200px;"
                                            class="form-control form-control-sm"
                                        >{{ $subscription->description }}</textarea>
                                        <div class="ml-1">
                                            <button inline-control="save" class="btn btn-primary inline-button" title="@lang('Save')">
                                                <span class="fa fa-save"></span>
                                            </button>
                                        </div>
                                        <div class="ml-1">
                                            <button inline-control="cancel" class="btn btn-light inline-button" title="@lang('Cancel')">
                                                <ion-icon name="close"></ion-icon>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </td>

                    <!-- Tags -->
                    <td>
                        <div class="pr-4" list-control="inline-tags">
                            <div inline-control="value-box">
                                <span inline-control="value-container">
                                    {!! view('client/datatable/subscription/column_tags', [
                                        'subscription' => $subscription,
                                    ]) !!}
                                </span>
                            </div>
                            <div inline-control="form-box" style="display:none;">
                                <form inline-control="form" method="POST" action="{{ action('App\Http\Controllers\Client\SubscriptionController@massUpdateSave') }}">
                                    @csrf
                                    <input type="hidden" name="subscription_id" value="{{ $subscription->id }}" />
                                    <input type="hidden" name="has_tag" value="true" />
                                    <div class="d-flex align-items-top">
                                        <select name="inline_mass_tags[]" class="form-control select2_init_multi inline_mass_tags" multiple style="min-width:150px">
                                            <?php
                                                $subscription_tags = App\Models\SubscriptionModel::get_tags($subscription->id);
                                            ?>
                                            @foreach (lib()->tag->get_by_user() as $val)
                                                <option {{ $subscription_tags->contains(function ($tag) use ($val) {
                                                    return $tag->name == $val->name;
                                                }) ? 'selected' : null }} value="{{ $val->name }}">{{ $val->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="ml-1">
                                            <button inline-control="save" class="btn btn-primary inline-button" title="@lang('Save')">
                                                <span class="fa fa-save"></span>
                                            </button>
                                        </div>
                                        <div class="ml-1">
                                            <button inline-control="cancel" class="btn btn-light inline-button" title="@lang('Cancel')">
                                                <ion-icon name="close"></ion-icon>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                    </td>

                    <!-- Rating -->
                    <td>
                        <div class="pr-4" list-control="inline-rating">
                            <div inline-control="value-box">
                                <span inline-control="value-container">
                                    {!! view('client/datatable/subscription/column_rating', [
                                        'rating' => $subscription->rating,
                                    ]) !!}
                                </span>
                            </div>
                            <div inline-control="form-box" style="display:none;">
                                <form inline-control="form" method="POST" action="{{ action('App\Http\Controllers\Client\SubscriptionController@massUpdateSave') }}">
                                    @csrf
                                    <input type="hidden" name="subscription_id" value="{{ $subscription->id }}" />
                                    <div class="d-flex align-items-top">
                                        <select name="rating"
                                            style="width:70px;"
                                            class="form-control"
                                        >
                                            @for($i = 1; $i <= 10; $i++)
                                                <option {{ $i == $subscription->rating ? 'selected' : '' }} value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>
                                        <div class="ml-1">
                                            <button inline-control="save" class="btn btn-primary inline-button" title="@lang('Save')">
                                                <span class="fa fa-save"></span>
                                            </button>
                                        </div>
                                        <div class="ml-1">
                                            <button inline-control="cancel" class="btn btn-light inline-button" title="@lang('Cancel')">
                                                <ion-icon name="close"></ion-icon>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </td>

                    <!-- Price -->
                    <td>
                        <div class="pr-4" list-control="inline-price">
                            <div inline-control="value-box">
                                <span class="d-block text-nowrap text-center">
                                    <span inline-control="price-type-container">{{ lib()->do->get_currency_symbol($subscription->price_type) }}</span><span inline-control="price-container">{{ $subscription->price }}</span>
                                </span>
                            </div>
                            <div inline-control="form-box" style="display:none;">
                                <form inline-control="form" method="POST" action="{{ action('App\Http\Controllers\Client\SubscriptionController@massUpdateSave') }}">
                                    @csrf
                                    <input type="hidden" name="subscription_id" value="{{ $subscription->id }}" />
                                    <div class="d-flex align-items-center">
                                        <div class="d-flex align-items-center">
                                            <input name="price"
                                                min="0" type="number"
                                                class="form-control"
                                                placeholder=""
                                                style="width:90px;margin-right:2px;"
                                                value="{{ $subscription->price }}"
                                            />
                                        </div>
                                        <div class="ml-1">
                                            <button inline-control="save" class="btn btn-primary inline-button" title="@lang('Save')">
                                                <span class="fa fa-save"></span>
                                            </button>
                                        </div>
                                        <div class="ml-1">
                                            <button inline-control="cancel" class="btn btn-light inline-button" title="@lang('Cancel')">
                                                <ion-icon name="close"></ion-icon>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </td>

                    <!-- Due Date -->
                    <td>
                        <div class="pr-4" list-control="inline-next-payment-date" style="padding-right:20px;">
                            <div inline-control="value-box">
                                <span inline-control="value-container" class="d-block text-nowrap">
                                    {{ date('d M Y', strtotime($subscription->next_payment_date)) }}
                                </span>
                            </div>
                            <div inline-control="form-box" style="display:none;">
                                <form inline-control="form" method="POST" action="{{ action('App\Http\Controllers\Client\SubscriptionController@massUpdateSave') }}">
                                    @csrf
                                    <input type="hidden" name="subscription_id" value="{{ $subscription->id }}" />
                                    <div class="d-flex align-items-top">
                                        <div class="input-group d-flex" style="flex-wrap:nowrap">
                                            <input name="next_payment_date"
                                                style="width:140px"
                                                type="text" placeholder="yyyy-mm-dd"
                                                value="{{ date('Y-m-d', strtotime($subscription->next_payment_date)) }}"
                                                maxlength="10" class="form-control" data-toggle="datepicker-and-icon"
                                            />
                                            <div class="input-group-append datepicker-trigger" style="    position: absolute;
                                            pointer-events: none;
                                            right: 10px;
                                            top: 9px;
                                            z-index: 3;
                                            background: transparent;">
                                                <div class="">
                                                    <i class="fa fa-calendar-alt"></i>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="ml-1">
                                            <button inline-control="save" class="btn btn-primary inline-button" title="@lang('Save')">
                                                <span class="fa fa-save"></span>
                                            </button>
                                        </div>
                                        <div class="ml-1">
                                            <button inline-control="cancel" class="btn btn-light inline-button" title="@lang('Cancel')">
                                                <ion-icon name="close"></ion-icon>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                    </td>

                    <!-- Refund Date -->
                    <td>
                        <div class="pr-4" list-control="inline-refund-date" style="padding-right:20px;">
                            <div inline-control="value-box">
                                <span inline-control="value-container" class="d-block text-nowrap">
                                    {{ $subscription->refund_date ? date('d M Y', strtotime($subscription->refund_date)) : 'N/A' }}
                                </span>
                            </div>
                            <div inline-control="form-box" style="display:none;">
                                <form inline-control="form" method="POST" action="{{ action('App\Http\Controllers\Client\SubscriptionController@massUpdateSave') }}">
                                    @csrf
                                    <input type="hidden" name="subscription_id" value="{{ $subscription->id }}" />
                                    <div class="d-flex align-items-top">
                                        <div class="input-group d-flex" style="flex-wrap:nowrap">
                                            <input name="refund_date"
                                                style="width:140px"
                                                type="text" placeholder="yyyy-mm-dd"
                                                value="{{ date('Y-m-d', strtotime($subscription->refund_date)) }}"
                                                maxlength="10" class="form-control" data-toggle="datepicker-and-icon"
                                            />
                                            <div class="input-group-append datepicker-trigger" style="    position: absolute;
                                            pointer-events: none;
                                            right: 10px;
                                            top: 9px;
                                            z-index: 3;
                                            background: transparent;">
                                                <div class="">
                                                    <i class="fa fa-calendar-alt"></i>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="ml-1">
                                            <button inline-control="save" class="btn btn-primary inline-button" title="@lang('Save')">
                                                <span class="fa fa-save"></span>
                                            </button>
                                        </div>
                                        <div class="ml-1">
                                            <button inline-control="cancel" class="btn btn-light inline-button" title="@lang('Cancel')">
                                                <ion-icon name="close"></ion-icon>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </td>

                    <!-- Expiry date -->
                    <td>
                        <div class="pr-4" list-control="inline-expiry-date" style="padding-right:20px;">
                            <div inline-control="value-box">
                                <span inline-control="value-container" class="d-block text-nowrap">
                                    {{ $subscription->expiry_date ? date('d M Y', strtotime($subscription->expiry_date)) : 'N/A' }}
                                </span>
                            </div>
                            <div inline-control="form-box" style="display:none;">
                                <form inline-control="form" method="POST" action="{{ action('App\Http\Controllers\Client\SubscriptionController@massUpdateSave') }}">
                                    @csrf
                                    <input type="hidden" name="subscription_id" value="{{ $subscription->id }}" />
                                    <div class="d-flex align-items-top">
                                        <div class="input-group d-flex" style="flex-wrap:nowrap">
                                            <input name="expiry_date"
                                                style="width:140px"
                                                type="text" placeholder="yyyy-mm-dd"
                                                value="{{ date('Y-m-d', strtotime($subscription->expiry_date)) }}"
                                                maxlength="10" class="form-control" data-toggle="datepicker-and-icon"
                                            />
                                            <div class="input-group-append datepicker-trigger" style="    position: absolute;
                                            pointer-events: none;
                                            right: 10px;
                                            top: 9px;
                                            z-index: 3;
                                            background: transparent;">
                                                <div class="">
                                                    <i class="fa fa-calendar-alt"></i>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="ml-1">
                                            <button inline-control="save" class="btn btn-primary inline-button" title="@lang('Save')">
                                                <span class="fa fa-save"></span>
                                            </button>
                                        </div>
                                        <div class="ml-1">
                                            <button inline-control="cancel" class="btn btn-light inline-button" title="@lang('Cancel')">
                                                <ion-icon name="close"></ion-icon>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </td>

                    <!-- Payment Mode -->
                    <td>
                        <div class="pr-4" list-control="inline-payment-mode" style="padding-right:20px;">
                            <div inline-control="value-box">
                                <span inline-control="value-container" class="d-block text-nowrap">
                                    {{ $subscription->payment_method ? $subscription->payment_method->name : 'N/A' }}
                                </span>
                            </div>
                            <div inline-control="form-box" style="display:none;">
                                <form inline-control="form" method="POST" action="{{ action('App\Http\Controllers\Client\SubscriptionController@massUpdateSave') }}">
                                    @csrf
                                    <input type="hidden" name="subscription_id" value="{{ $subscription->id }}" />
                                    <div class="d-flex align-items-top">
                                        <select style="width:150px" name="payment_mode_id" class="form-control" required data-toggle="tooltip" >
                                            @foreach (lib()->user->payment_methods as $val)
                                                <option value="{{ $val->id }}" {{ $subscription->payment_mode_id == $val->id ? 'selected' : null }}>{{ $val->name }}</option>
                                            @endforeach
                                        </select>
                                        
                                        <div class="ml-1">
                                            <button inline-control="save" class="btn btn-primary inline-button" title="@lang('Save')">
                                                <span class="fa fa-save"></span>
                                            </button>
                                        </div>
                                        <div class="ml-1">
                                            <button inline-control="cancel" class="btn btn-light inline-button" title="@lang('Cancel')">
                                                <ion-icon name="close"></ion-icon>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </td>

                    <!-- Category -->
                    <td>
                        <div class="pr-4" list-control="inline-category" style="padding-right:20px;">
                            <div inline-control="value-box">
                                <span inline-control="value-container" class="d-block text-nowrap">
                                    {{ $subscription->product_category ? $subscription->product_category->name : 'N/A' }}
                                </span>
                            </div>
                            <div inline-control="form-box" style="display:none;">
                                <form inline-control="form" method="POST" action="{{ action('App\Http\Controllers\Client\SubscriptionController@massUpdateSave') }}">
                                    @csrf
                                    <input type="hidden" name="subscription_id" value="{{ $subscription->id }}" />
                                    <div class="d-flex align-items-top">
                                        <select style="width:150px" name="category_id" class="form-control" required data-toggle="tooltip" >
                                            <option value=''>@lang('Select category')</option>
                                            @foreach (App\Models\ProductCategory::all() as $category)
                                                <option {{ $subscription->category_id == $category->id ? 'selected' : '' }} value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        
                                        <div class="ml-1">
                                            <button inline-control="save" class="btn btn-primary inline-button" title="@lang('Save')">
                                                <span class="fa fa-save"></span>
                                            </button>
                                        </div>
                                        <div class="ml-1">
                                            <button inline-control="cancel" class="btn btn-light inline-button" title="@lang('Cancel')">
                                                <ion-icon name="close"></ion-icon>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </td>

                    <!-- Folder Name -->
                    <td>
                        <div class="pr-4" list-control="inline-folder" style="padding-right:20px;">
                            <div inline-control="value-box">
                                <span inline-control="value-container" class="d-block text-nowrap">
                                    {{ $subscription->folder ? $subscription->folder->name : 'N/A' }}
                                </span>
                            </div>
                            <div inline-control="form-box" style="display:none;">
                                <form inline-control="form" method="POST" action="{{ action('App\Http\Controllers\Client\SubscriptionController@massUpdateSave') }}">
                                    @csrf
                                    <input type="hidden" name="subscription_id" value="{{ $subscription->id }}" />
                                    <div class="d-flex align-items-top">
                                        <select style="width:150px" name="folder_id" class="form-control" required data-toggle="tooltip" >
                                            <option value=''>@lang('Select folder')</option>
                                            @foreach ($folders as $folder)
                                                <option {{ $subscription->folder_id == $folder->id ? 'selected' : '' }} value="{{ $folder->id }}">{{ $folder->name }}</option>
                                            @endforeach
                                        </select>
                                        
                                        <div class="ml-1">
                                            <button inline-control="save" class="btn btn-primary inline-button" title="@lang('Save')">
                                                <span class="fa fa-save"></span>
                                            </button>
                                        </div>
                                        <div class="ml-1">
                                            <button inline-control="cancel" class="btn btn-light inline-button" title="@lang('Cancel')">
                                                <ion-icon name="close"></ion-icon>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                    </td>

                    <!-- Status -->
                    <td>
                        <div>
                            <label class="position-relative" style="{!! $subscription->status == 1 ? 'pointer-events:none' : '' !!}">
                                <input {{ $subscription->status == 1 ? 'checked' : '' }} list-control="status-toggle" subscription-id="{{ $subscription->id }}" type="checkbox" name="status" value="1" class="checkbox-toggle">
                                <span class="toggle-slider">
                                    <span class="toggle-slider-inner">
                                        <span class="checkbox-toggle-label-on">@lang('Active')</span>
                                        <span class="checkbox-toggle-anchor"></span>
                                        <span class="checkbox-toggle-label-off">@lang('Draft')</span>
                                    </span>
                                </span>
                            </label>
                        </div>
                    </td>

                    <!-- Product Name -->
                    <td>
                        {{ $subscription->product_name }}
                    </td>

                    <!-- Product Type -->
                    <td>
                        {{ $subscription->product_type_obj ? $subscription->product_type_obj->name : '' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if (!$subscriptions->count()) 
        <div style="height:auto" class="text-center">
            <div class="my-4"><svg style="width:150px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 329.8 420.2"><g style="isolation:isolate"><g id="Layer_2" data-name="Layer 2"><g id="OBJECTS"><path d="M137.3,91,14.3,404.2s72.9,34.3,306.4,2.6L198.7,90s-6.3,2.4-30.4,3C145.8,93.6,137.3,91,137.3,91Z" style="fill:#e89f2b;opacity:0.15"/><g style="opacity:0.7000000000000001"><path d="M327.9,408.1c0,6-72.2,10.8-161.3,10.8S5.3,414.1,5.3,408.1s72.2-10.7,161.3-10.7S327.9,402.2,327.9,408.1Z" style="fill:#e89f2b"/></g><path d="M248.4,409.3a9.6,9.6,0,1,1,9.6-9.6A9.5,9.5,0,0,1,248.4,409.3Z" style="fill:#e89f2b"/><path d="M248.4,343.2a9.5,9.5,0,1,1,9.5-9.5A9.5,9.5,0,0,1,248.4,343.2Z" style="fill:#e89f2b"/><path d="M305.8,366.9c3.1.5,6.4.9,9.2,2.7a11.9,11.9,0,0,1,3.6,3.8c.8,1.3.9,2.4,2.2,3.2a1.2,1.2,0,0,0,1.7-.7c1.3-3.3-3.6-7.4-6-8.9s-7.4-2.9-10.8-.8c-.3.2-.3.7.1.7Z" style="fill:#d18c2b"/><path d="M316.8,378.9c3.2,8,15.6,3.1,12.5-4.9s-15.6-3.1-12.5,4.9Z" style="fill:#dc432b"/><path d="M154.1,411.1a336.8,336.8,0,0,1-36.2,1.2c-12.1-.3-24.2-2.9-36.2-2.9a1.1,1.1,0,0,0-.3,2.1c11.3,3.5,24.7,3.5,36.5,3.7a223.6,223.6,0,0,0,36.5-2.2c1-.2.8-2-.3-1.9Z" style="fill:#dc432b"/><path d="M27.5,402.7c14.9.9,29.8,1,44.7.8s28.5.9,41.6-1.9a.9.9,0,0,0-.2-1.7c-7.4-.8-14.9.4-22.3.5s-13.8.3-20.7.3c-14.4.2-28.7,0-43.1,0a1,1,0,0,0,0,2Z" style="fill:#dc432b"/><polygon points="162.1 345.7 162 387.3 156.6 387.3 147.3 366.5 158.3 345.7 162.1 345.7" style="fill:#f1efe8"/><rect x="111.2" y="316" width="94.9" height="81.43" rx="7.9" transform="translate(515.5 198.4) rotate(90.1)" style="fill:#dc432b"/><rect x="107.9" y="340.6" width="59.2" height="12.16" rx="2.8" transform="translate(509.5 373) rotate(115.7)" style="fill:#e89f2b"/><path d="M143.1,326c-2.1,4.3,4.7,7.5,6.7,3.2s-4.6-7.6-6.7-3.2Z"/><path d="M136.5,339.8c-2.1,4.3,4.6,7.6,6.7,3.2s-4.7-7.5-6.7-3.2Z"/><path d="M130.2,352.8c-2.1,4.3,4.7,7.5,6.7,3.2s-4.6-7.6-6.7-3.2Z"/><path d="M124.8,364.1c-2.1,4.3,4.6,7.5,6.7,3.2s-4.6-7.6-6.7-3.2Z"/><path d="M172.4,404.2h1.4l.7-6.4h.8l2,6.1,1.3-.2-.6-6.5.8-.2,3.2,5.6,1.2-.6-2-6.1.8-.5,4.3,4.9,1.1-.8-3.3-5.7a1.8,1.8,0,0,0,.7-.5L190,397l.9-1-4.3-4.8.5-.7,5.9,2.6.7-1.1-5.3-3.8.4-.8,6.3,1.4a9.3,9.3,0,0,0,.4-1.3l-5.9-2.7c.1-.2.1-.5.2-.8h6.5c0-.4.1-.9.1-1.3l-6.3-1.4v-.8l6.3-1.4c0-.4-.1-.9-.1-1.3h-6.5c-.1-.3-.1-.6-.2-.8l5.9-2.7a9.3,9.3,0,0,0-.4-1.3l-6.3,1.4a3,3,0,0,0-.4-.8l5.3-3.8a4.1,4.1,0,0,0-.7-1.1l-5.9,2.6-.5-.7,4.3-4.8-.9-1-5.2,3.8-.6-.6,3.2-5.6-1.1-.8-4.3,4.8-.7-.4,2-6.1-1.3-.6-3.2,5.6-.8-.2.7-6.5-1.3-.3-2,6.2h-.9l-.6-6.4h-1.4l-.7,6.4H171l-2-6.2-1.3.3.6,6.4-.8.3-3.2-5.6-1.2.5,2,6.2-.8.4-4.3-4.8-1.1.8,3.3,5.6-.7.6-5.2-3.8-.9,1,4.3,4.8-.5.7-5.9-2.7-.7,1.2,5.3,3.8a3,3,0,0,0-.4.8l-6.3-1.4a6.5,6.5,0,0,0-.4,1.3l5.9,2.6c-.1.3-.1.6-.2.9H150c0,.5-.1.9-.1,1.4l6.3,1.3v.9l-6.3,1.3c0,.5.1.9.1,1.4h6.5c.1.2.1.5.2.8l-5.9,2.6a5,5,0,0,0,.4,1.3l6.3-1.3a2.5,2.5,0,0,0,.4.7l-5.3,3.8a7.5,7.5,0,0,0,.7,1.2l5.9-2.6.5.7-4.3,4.8.9,1,5.2-3.8.6.5-3.2,5.6,1.1.8,4.3-4.8.7.4-2,6.2,1.3.6,3.2-5.7.8.3-.7,6.4,1.3.3,2-6.1h.9Zm3.3-23.3a2.5,2.5,0,0,1-5,0,2.5,2.5,0,1,1,5,0Zm-2.5-12.8a12.8,12.8,0,0,1,12.7,11.4h-6.8a6,6,0,0,0-5.9-4.7,4.7,4.7,0,0,0-1.7.3l-3.4-6A14,14,0,0,1,173.2,368.1Zm-12.8,12.8a12.5,12.5,0,0,1,5.2-10.3l3.4,5.9a5.8,5.8,0,0,0-1.9,4.4,6,6,0,0,0,1.9,4.4l-3.4,5.9A12.6,12.6,0,0,1,160.4,380.9Zm7.6,11.7,3.5-5.9a4.7,4.7,0,0,0,1.7.3,6,6,0,0,0,5.9-4.7h6.8A12.8,12.8,0,0,1,168,392.6Z" style="fill:#e89f2b"/><path d="M189.2,380.9a16.1,16.1,0,1,0-16.1,16A16.1,16.1,0,0,0,189.2,380.9Zm-16,2.5a2.5,2.5,0,0,1-2.5-2.5,2.5,2.5,0,1,1,5,0A2.5,2.5,0,0,1,173.2,383.4Zm-5.1-14.3a14,14,0,0,1,5.1-1,12.8,12.8,0,0,1,12.7,11.4h-6.8a6,6,0,0,0-5.9-4.7,4.7,4.7,0,0,0-1.7.3Zm-7.7,11.8a12.5,12.5,0,0,1,5.2-10.3l3.4,5.9a5.8,5.8,0,0,0-1.9,4.4,6,6,0,0,0,1.9,4.4l-3.4,5.9A12.6,12.6,0,0,1,160.4,380.9Zm11.1,5.8a4.7,4.7,0,0,0,1.7.3,6,6,0,0,0,5.9-4.7h6.8A12.8,12.8,0,0,1,168,392.6Z" style="fill:#e89f2b"/><path d="M187.9,380.9a14.8,14.8,0,1,0-14.8,14.7A14.7,14.7,0,0,0,187.9,380.9Zm-29,0a14.3,14.3,0,1,1,14.2,14.2A14.3,14.3,0,0,1,158.9,380.9Z" style="fill:#d9d9d9"/><g style="mix-blend-mode:multiply"><path d="M170.7,380.9a2.5,2.5,0,1,1,5,0,2.5,2.5,0,0,1-5,0Zm2.5-12.8a12.8,12.8,0,0,1,12.7,11.4h-6.8a6,6,0,0,0-5.9-4.7,4.7,4.7,0,0,0-1.7.3l-3.4-6A14,14,0,0,1,173.2,368.1Zm-12.8,12.8a12.5,12.5,0,0,1,5.2-10.3l3.4,5.9a5.8,5.8,0,0,0-1.9,4.4,6,6,0,0,0,1.9,4.4l-3.4,5.9A12.6,12.6,0,0,1,160.4,380.9Zm7.6,11.7,3.5-5.9a4.7,4.7,0,0,0,1.7.3,6,6,0,0,0,5.9-4.7h6.8A12.8,12.8,0,0,1,168,392.6Z" style="fill:#fff"/></g><path d="M163.3,367.4Z" style="fill:#f1efe8"/><path d="M163.3,368.8Z" style="fill:#f1efe8"/><path d="M312.2,364c0-10.5-11.4-18.9-25.5-18.9v37.8C300.8,382.9,312.2,374.4,312.2,364Z" style="fill:#e89f2b"/><rect x="216.4" y="323" width="70.3" height="91.85" rx="9.8" transform="translate(620.4 117.2) rotate(90)" style="fill:#dc432b"/><rect x="197.1" y="305.1" width="5.8" height="17.88" rx="1.3" transform="translate(516 510.8) rotate(154.4)" style="fill:#5a9eaa"/><rect x="204.5" y="281.6" width="7.3" height="32.38" rx="1.7" transform="matrix(0.96, 0.28, -0.28, 0.96, 92.06, -46.5)" style="fill:#488c93"/><rect x="199" y="257.2" width="7.3" height="20.03" rx="1.7" transform="translate(-132.6 277.2) rotate(-54.5)" style="fill:#488c93"/><path d="M216.4,278.2a4.1,4.1,0,0,0-3.7-4.5,4,4,0,0,0-4.5,3.6,4.1,4.1,0,0,0,3.6,4.5A4.2,4.2,0,0,0,216.4,278.2Z" style="fill:#dc432b"/><rect x="199.6" y="400" width="5.8" height="17.88" rx="1.3" transform="translate(-215.9 591.2) rotate(-87.2)" style="fill:#5a9eaa"/><rect x="98.3" y="369" width="7.3" height="32.38" rx="1.7" transform="translate(-278.8 502.5) rotate(-92.3)" style="fill:#488c93"/><rect x="76.4" y="354.8" width="7.3" height="20.03" rx="1.7" transform="translate(-55 16.6) rotate(-8.8)" style="fill:#488c93"/><path d="M86.7,375.3a4.2,4.2,0,0,1,.8,5.8,4.1,4.1,0,1,1-.8-5.8Z" style="fill:#dc432b"/><polygon points="297.1 400.9 310.2 396.2 315.7 405 311.9 407.7 308.8 402.5 302.2 404.8 303 410.5 298.3 412.2 297.1 400.9" style="fill:#dc432b"/><rect x="88.2" y="391.3" width="12.5" height="21.97" rx="1.6" transform="translate(74.7 -10.5) rotate(10.5)" style="fill:#5a9eaa"/><path d="M95.2,397.9a15.4,15.4,0,0,0-18,12.4l30.5,5.7A15.6,15.6,0,0,0,95.2,397.9Z" style="fill:#d18c2b"/><rect x="59" y="377" width="11.5" height="21.97" rx="1.6" transform="translate(360 648) rotate(140.8)" style="fill:#488c93"/><rect x="45" y="362.6" width="15.9" height="21.97" rx="1.6" transform="translate(329.9 629.8) rotate(140.8)" style="fill:#5a9eaa"/><path d="M36.9,395.1a15.6,15.6,0,0,0-16.6,14.3l31,2.3A15.5,15.5,0,0,0,36.9,395.1Z" style="fill:#d18c2b"/><path d="M250.5,348.3c1.5,2.6,4.7,4.4,7.1,6.1s5.6,4.6,8.8,5.8c1.5.6,2.5-1.6,1.5-2.6s-5.5-3.9-8.3-5.6-5.5-3.9-8.6-4.4c-.3,0-.7.3-.5.7Z"/><path d="M253.6,361.9c2.9-1.2,5.2-3.8,7.4-6s5.7-4.6,6.3-7.4a1.5,1.5,0,0,0-1.8-1.8c-2.8.7-5.2,4.4-7.2,6.5s-4.8,4.6-5.9,7.5a1,1,0,0,0,1.2,1.2Z"/><path d="M250.5,385.5c1.5,2.6,4.7,4.4,7.1,6.1s5.6,4.6,8.8,5.9c1.5.6,2.5-1.6,1.5-2.7s-5.5-3.9-8.3-5.6-5.5-3.9-8.6-4.4c-.3,0-.7.3-.5.7Z"/><path d="M253.6,399.1c2.9-1.1,5.2-3.8,7.4-6s5.7-4.6,6.3-7.4a1.4,1.4,0,0,0-1.8-1.7c-2.8.6-5.2,4.3-7.2,6.4s-4.8,4.6-5.9,7.5a1,1,0,0,0,1.2,1.2Z"/><path d="M170.2,291.7l-3.3-1.6,2.3-7.1a1.8,1.8,0,0,1,.7-.8,8.7,8.7,0,0,0,2.8-3.5c3.4-7,6.9-13.9,10.4-20.8a8,8,0,0,1,.8-1.2l-3.1-1.4a1.2,1.2,0,0,1-.4-1.1,5.2,5.2,0,0,1,.6-1.3c.2-.6.6-.7,1.3-.6a1.9,1.9,0,0,0,1.5-.6,1.7,1.7,0,0,0-.5-1.6c-.5-.5-.5-.8-.2-1.3,3.1-6.2,6.1-12.3,9.2-18.4a4.3,4.3,0,0,1,3.7-2.5,6.7,6.7,0,0,1,6.6,3.3,4.5,4.5,0,0,1,.2,4.6c-3,6-6.1,12.1-9.1,18.2a1.1,1.1,0,0,1-1.3.7,1.4,1.4,0,0,0-1.5.5,1.4,1.4,0,0,0,.2,1.4c1,.7.7,1.3.3,2s-.5,1-1,1.8l-3.4-2.3-1.7,3.6-9.9,19.7a5,5,0,0,0-.5,2.1,5.7,5.7,0,0,1-1.4,3.7Zm17.6-42.2.8-1.1,7.5-14.9a4.1,4.1,0,0,0,.3-.8,2.3,2.3,0,0,0-.6-1.3,2.4,2.4,0,0,0-1.4.3c-.3.1-.4.5-.5.8-2.5,4.9-5,9.8-7.4,14.7a3.6,3.6,0,0,0-.4.9A1.2,1.2,0,0,0,187.8,249.5Zm4.2,2.1a4.3,4.3,0,0,0,.7-1c2.6-5,5.1-10.1,7.6-15.1a1.3,1.3,0,0,0,.3-.8,1.1,1.1,0,0,0-1.5-1.3,2.4,2.4,0,0,0-.9,1.1c-2.6,5-5.1,9.9-7.5,14.9a3.6,3.6,0,0,0-.4.9A1.2,1.2,0,0,0,192,251.6Z"/><polygon points="190.7 266.8 198.3 255.1 191.1 247.7 187.5 250.7 191.8 255 188 260.9 182.7 258.8 179.9 262.9 190.7 266.8" style="fill:#dc432b"/><rect x="134.9" y="306.2" width="48.6" height="6.08" rx="1.1" style="fill:#bc2f20"/><path d="M161.3,309.6a28.4,28.4,0,0,0-18.5-35.4c-1.5-.5-2.1,1.9-.6,2.4A25.9,25.9,0,0,1,158.9,309c-.5,1.5,1.9,2.1,2.4.6Z"/><path d="M166.6,302.7a1.2,1.2,0,0,0,0-2.4,1.2,1.2,0,0,0,0,2.4Z"/><path d="M150.4,291.1a1.2,1.2,0,1,0,0-2.4,1.2,1.2,0,0,0,0,2.4Z"/><path d="M142,291.6a4.5,4.5,0,0,1-3.6-1.7,3.4,3.4,0,0,1-.6-1.5h-.2a3.7,3.7,0,0,1-2.6-2.9,6.2,6.2,0,0,1,.1-1.9,1.1,1.1,0,0,1-.8-.3,3.3,3.3,0,0,1-1.9-2.1,4.5,4.5,0,0,1-.1-2,5.3,5.3,0,0,1-2.5-2.4,4,4,0,0,1,0-3.4.7.7,0,0,1,.9-.3.5.5,0,0,1,.2.8,3.3,3.3,0,0,0,0,2.4,4.2,4.2,0,0,0,1.8,1.7,7.5,7.5,0,0,1,.7-1.2,4,4,0,0,1,3.7-1.6,1.7,1.7,0,0,1,1.5,1.1,1.8,1.8,0,0,1-.5,1.8,5.9,5.9,0,0,1-4.6,1.4,2.8,2.8,0,0,0,.1,1.3,2.4,2.4,0,0,0,1.2,1.4l.6.2.3-.6a3.9,3.9,0,0,1,2.2-2.3,2.3,2.3,0,0,1,2.1.4,1.8,1.8,0,0,1,.7,1.5,2.5,2.5,0,0,1-1.8,1.9l-2.6.5a3.8,3.8,0,0,0,0,1.5,2.1,2.1,0,0,0,1.4,1.8v-.3a3.7,3.7,0,0,1,1.6-2.4,2.1,2.1,0,0,1,2.9.2,2.1,2.1,0,0,1-.2,2.4,3.7,3.7,0,0,1-1.7,1l-1.3.4a3,3,0,0,0,.4.8,3.5,3.5,0,0,0,4,.9.6.6,0,0,1,.8.3.6.6,0,0,1-.3.8A4.3,4.3,0,0,1,142,291.6Zm-1.3-6.4-.7.2a2.3,2.3,0,0,0-1,1.6c0,.1,0,.1-.1.2l1-.3a4.7,4.7,0,0,0,1.2-.7.8.8,0,0,0,.1-.8C141.1,285.2,140.9,285.2,140.7,285.2Zm-2.2-4.6h-.2a2.6,2.6,0,0,0-1.5,1.6v.2l1.8-.4c.4-.1.9-.4.9-.8s-.1-.3-.2-.4A.9.9,0,0,0,138.5,280.6Zm-4.6-2.3a4.4,4.4,0,0,0,3.3-1.1c.3-.3.3-.5.2-.6s-.2-.2-.4-.2a3.4,3.4,0,0,0-2.6,1Z"/><path d="M89.7,334.1l-.4-.2a5,5,0,0,1-.5-6.2,5.3,5.3,0,0,1,1.4-1.1c0-.1-.1-.2-.1-.3a3.9,3.9,0,0,1,2.3-3.6,5.9,5.9,0,0,1,2-.6v-1a4.2,4.2,0,0,1,1.7-2.7,4,4,0,0,1,2-.7,6.5,6.5,0,0,1,1.7-3.4,4,4,0,0,1,3.6-1.2c.3.1.6.4.5.7a.5.5,0,0,1-.7.5,3.3,3.3,0,0,0-2.6.9,4.5,4.5,0,0,0-1.2,2.5l1.6.4a4.4,4.4,0,0,1,2.9,3.3,1.9,1.9,0,0,1-.7,1.9,1.8,1.8,0,0,1-1.9.1,6.5,6.5,0,0,1-3.1-4.4,2.5,2.5,0,0,0-1.5.5,2.7,2.7,0,0,0-1.1,1.8v.8h.8a4.1,4.1,0,0,1,3.2,1.5,2.6,2.6,0,0,1,.2,2.3,1.8,1.8,0,0,1-1.3,1.2,2.5,2.5,0,0,1-2.5-1.3,7.4,7.4,0,0,1-1.4-2.5,4.7,4.7,0,0,0-1.7.4,2.8,2.8,0,0,0-1.5,2.3h.4a4.1,4.1,0,0,1,3.1.9,2.2,2.2,0,0,1,.7,3,2.2,2.2,0,0,1-2.5.6,5.8,5.8,0,0,1-1.7-1.4,5.7,5.7,0,0,1-.8-1.3,2.7,2.7,0,0,0-.8.7,3.9,3.9,0,0,0,.4,4.6.7.7,0,0,1,0,.9Zm2-6.8a8.6,8.6,0,0,0,.7,1,3.5,3.5,0,0,0,1.2,1,1.1,1.1,0,0,0,1-.1c.2-.4-.1-1-.5-1.4a2.9,2.9,0,0,0-2.1-.5Zm4.2-3.9a7.8,7.8,0,0,0,1.1,1.8c.3.4.8.9,1.2.8a.8.8,0,0,0,.5-.5,1.4,1.4,0,0,0-.1-1.1,3.4,3.4,0,0,0-2.4-1Zm3.5-4.4a5,5,0,0,0,2.4,3.2c.5.3.7.1.7.1s.2-.3.2-.7a3.6,3.6,0,0,0-2-2.3Z"/><path d="M172.5,305.2a5.9,5.9,0,0,1-2.3-.5.6.6,0,1,1,.5-1.1,4.4,4.4,0,0,0,5.3-1.3,3.4,3.4,0,0,0,.6-1.2l-1.9-.5a4.4,4.4,0,0,1-2-1.3,2.4,2.4,0,0,1-.3-2.8,2.6,2.6,0,0,1,3.5-.3,4.6,4.6,0,0,1,1.9,3,2,2,0,0,1,.1.7,3.5,3.5,0,0,0,2.2-2.6,8.3,8.3,0,0,0-.1-2.2,11.1,11.1,0,0,1-3.4-.5c-1.3-.5-2.1-1.3-2.2-2.3a1.8,1.8,0,0,1,.8-1.8,2.7,2.7,0,0,1,2.6-.5,4.9,4.9,0,0,1,2.7,2.9,3.6,3.6,0,0,0,.4.9l.9-.3a3.2,3.2,0,0,0,1.7-1.9,3.1,3.1,0,0,0,0-1.9,7.8,7.8,0,0,1-5.9-1.8,2.4,2.4,0,0,1-.6-2.1,2.2,2.2,0,0,1,1.8-1.4,5.3,5.3,0,0,1,4.6,2,16.2,16.2,0,0,1,1,1.8,5.8,5.8,0,0,0,2.5-2.4,3.9,3.9,0,0,0,0-3.2.6.6,0,0,1,.3-.8.5.5,0,0,1,.8.2,4.7,4.7,0,0,1,0,4.3,6.6,6.6,0,0,1-3.3,3.1,3.6,3.6,0,0,1,0,2.5,4.5,4.5,0,0,1-2.3,2.7l-1.2.4a7.1,7.1,0,0,1,.1,2.5,4.5,4.5,0,0,1-3.1,3.6h-.4a4.6,4.6,0,0,1-.8,1.9A5.7,5.7,0,0,1,172.5,305.2Zm1.7-8.3a1.1,1.1,0,0,0-.8.3,1.2,1.2,0,0,0,.2,1.3,4.4,4.4,0,0,0,1.6,1l1.5.4a.6.6,0,0,1-.1-.4,3.2,3.2,0,0,0-1.4-2.2A2.1,2.1,0,0,0,174.2,296.9Zm2.8-5.8a1.3,1.3,0,0,0-1,.4.7.7,0,0,0-.4.7c.1.5.8,1,1.4,1.2l2.6.5c-.1-.2-.2-.3-.2-.5s-1.2-2-2-2.2Zm2.3-5.5H179a.8.8,0,0,0-.8.4,1.1,1.1,0,0,0,.3.9,6.1,6.1,0,0,0,4.6,1.5l-.7-1.4A4.2,4.2,0,0,0,179.3,285.6Z"/><path d="M156.2,308.5c.7-5.5-1.9-11.1-7.3-13.3s-12.5,1.6-16.5,6.5c-6,7.4-10,17-12.8,26-3.6,11.7-4.1,24.4-12.3,34.1-1,1.3.7,3,1.8,1.8,7.8-9.4,9-21.1,12.1-32.5a94.8,94.8,0,0,1,9.1-21.8c2.3-3.8,4.8-8.1,8.7-10.5,7.4-4.8,15.8,1.3,14.7,9.7-.2,1.6,2.3,1.6,2.5,0Z"/><path d="M194.2,318.9a14,14,0,0,0,0,2.9c0,.2.2.2.2,0a14.3,14.3,0,0,0,.1-2.9.2.2,0,1,0-.3,0Z" style="fill:#bc2f20"/><path d="M125.1,323.8a6.5,6.5,0,0,0-.7,5.1c.1.3.7.4.7,0a21.1,21.1,0,0,0,.4-5,.2.2,0,0,0-.4-.1Z" style="fill:#bc2f20"/><path d="M192.7,322.7l.7.6c.1.1.3,0,.2-.1a4.2,4.2,0,0,0-.6-.8c-.1-.1-.4.1-.3.3Z" style="fill:#bc2f20"/><path d="M195.8,322c-.1,1,0,2-.1,3s-.1,2,0,3,.4.3.5,0a15.2,15.2,0,0,0-.3-6c-.1-.1-.1-.1-.1,0Z" style="fill:#bc2f20"/><path d="M120.3,392.1a9.7,9.7,0,0,0-.1,1.7.2.2,0,1,0,.4,0,3.3,3.3,0,0,0-.1-1.7c0-.1-.2-.1-.2,0Z" style="fill:#bc2f20"/><path d="M121.5,392.6a12.2,12.2,0,0,0,0,2.7c0,.2.3.2.4,0a8.2,8.2,0,0,0-.1-2.7c0-.2-.2-.2-.3,0Z" style="fill:#bc2f20"/><path d="M122.9,395.6a4.6,4.6,0,0,0,.2,2,.2.2,0,0,0,.4-.1,4.1,4.1,0,0,0-.4-1.9c-.1-.1-.2-.1-.2,0Z" style="fill:#bc2f20"/><path d="M120.6,396.3a2.5,2.5,0,0,0,.4,1.1c.1.1.3,0,.2-.1a1.9,1.9,0,0,0-.5-1.1.1.1,0,0,0-.1.1Z" style="fill:#bc2f20"/><path d="M158.6,333a7.4,7.4,0,0,0,0,2.1.3.3,0,0,0,.6,0,7.4,7.4,0,0,0,.1-2.1.4.4,0,1,0-.7,0Z" style="fill:#bc2f20"/><path d="M159.3,333a1.9,1.9,0,0,0,0,1.3.3.3,0,0,0,.5,0,1.9,1.9,0,0,0,0-1.3c0-.4-.5-.4-.5,0Z" style="fill:#bc2f20"/><path d="M159.8,329.7c.2,1.3.1,2.7.4,3.9s.3.2.3,0-.1-2.8-.4-4-.3-.1-.3.1Z" style="fill:#bc2f20"/><path d="M158.7,338.1a1.4,1.4,0,0,0,.2,1.5c.2.1.4,0,.4-.2s-.2-.8-.1-1.2a.3.3,0,1,0-.5-.1Z" style="fill:#bc2f20"/><path d="M123.4,345.6a2.8,2.8,0,0,0,.4,1.5.2.2,0,0,0,.4-.1,2.7,2.7,0,0,0-.5-1.5c-.1-.2-.4-.1-.3.1Z" style="fill:#bc2f20"/><path d="M124.1,344.8c.4.3.3.5.5.8a.2.2,0,0,0,.4.1,1.1,1.1,0,0,0-.4-1.3.3.3,0,0,0-.5.4Z" style="fill:#bc2f20"/><path d="M133.6,316a10.5,10.5,0,0,0-.1,2.5c0,.2.3.2.4,0a10.5,10.5,0,0,0-.1-2.5c0-.2-.2-.2-.2,0Z" style="fill:#bc2f20"/><path d="M134.2,316.6c.1,1-.1,2.2.1,3.2s.3.1.4,0a6.2,6.2,0,0,0-.2-3.3c0-.1-.3-.1-.3.1Z" style="fill:#bc2f20"/><path d="M144,368.3a5.1,5.1,0,0,0,.1,4.5c.1.1.4,0,.3-.2a7.5,7.5,0,0,1,.1-4.2c.1-.3-.4-.4-.5-.1Z" style="fill:#bc2f20"/><path d="M141.3,367.9a2.5,2.5,0,0,0,.1.9.1.1,0,0,0,.3,0,2.5,2.5,0,0,0,.1-.9.3.3,0,0,0-.5,0Z" style="fill:#bc2f20"/><path d="M184.3,344.1a5.8,5.8,0,0,0-.3,1.9.2.2,0,0,0,.4.1,5.9,5.9,0,0,0,.5-1.9c.1-.4-.5-.5-.6-.1Z" style="fill:#bc2f20"/><path d="M185.3,346.2l-.6,2c0,.2.3.5.5.2a2.9,2.9,0,0,0,.4-2.2c-.1-.2-.3-.2-.3,0Z" style="fill:#bc2f20"/><path d="M220.1,344.3a1.4,1.4,0,0,0-.2.6.8.8,0,0,0,.1.7c.3.1.4.2.7.1s.3-.2.4-.4a1.7,1.7,0,0,0,.1-.7.6.6,0,0,0-1.1-.3Z" style="fill:#bc2f20"/><path d="M217.3,346.5a1.2,1.2,0,0,0-.2,1.5c.1.1.4.1.4-.1s.1-.8.2-1.3a.2.2,0,0,0-.4-.1Z" style="fill:#bc2f20"/><path d="M285.9,342.3a2.5,2.5,0,0,1-.1,1.2c0,.2.2.3.3.1a1.5,1.5,0,0,0,.3-1.3c0-.4-.5-.3-.5,0Z" style="fill:#bc2f20"/><path d="M287.5,342.9a5.6,5.6,0,0,0,0,1.3c0,.1.2.1.3,0a2.8,2.8,0,0,0-.1-1.3Z" style="fill:#bc2f20"/><path d="M288.6,387.8a6.7,6.7,0,0,0-.9,1.4c-.1.2.2.3.3.2l.8-1.5c0-.2-.1-.2-.2-.1Z" style="fill:#bc2f20"/><path d="M286.1,386.5a9.6,9.6,0,0,0,0,1.7c0,.2.2.2.2,0a9.6,9.6,0,0,0,0-1.7c0-.1-.2-.1-.2,0Z" style="fill:#bc2f20"/><path d="M224.9,390.7a4.6,4.6,0,0,0,1.9,2.6c.2.1.5-.1.4-.3a7.6,7.6,0,0,0-1.8-2.6.3.3,0,0,0-.5.3Z" style="fill:#bc2f20"/><path d="M222.5,393.5a9.1,9.1,0,0,0,.9,1.7c.1.2.4,0,.4-.2a6.7,6.7,0,0,0-.7-1.7c-.2-.3-.7-.1-.6.2Z" style="fill:#bc2f20"/><path d="M277.3,364.5c-.7.4-1.1,1.5-.3,2a.3.3,0,0,0,.4-.2,3.5,3.5,0,0,1,.3-1.4c.2-.3-.2-.6-.4-.4Z" style="fill:#bc2f20"/><path d="M217.9,237.8l.9,1.8h1.9a.7.7,0,0,0,.5-.3,5.4,5.4,0,0,0,.7-1.3.4.4,0,0,1,.5-.2l2,.6v1.7c-.1.4,0,.6.3.7l.8.5a.9.9,0,0,0,1.3,0l1.1-.7,1.9,1.6-1,1.6c-.2.3-.2.5,0,.7a4.1,4.1,0,0,1,.5.9.9.9,0,0,0,1,.6h1.1c.4-.1.5.1.6.4s.3,1.1.4,1.7v.2l-1.6.9a.5.5,0,0,0-.3.5c0,.5-.2,1.2.1,1.7s.9.6,1.5.9.3.3.2.6-.3,1.1-.5,1.6a.4.4,0,0,1-.4.4h-1.5c-.2,0-.3,0-.5.3a8.3,8.3,0,0,1-.7,1.3c-.2.2-.2.3,0,.6l.9,1.5-.5.5c-.2.2-.4.3-.5.5s-.8.7-1.6.2a1.5,1.5,0,0,0-1.9-.1,1.6,1.6,0,0,0-1,1.8c.1.9,0,.9-.8,1.1s-1.1.4-1.5.2-.5-.7-.7-1.2-.4-.6-.9-.5H219a.6.6,0,0,0-.6.3,10.9,10.9,0,0,1-.8,1.5l-2-.5a.5.5,0,0,1-.4-.5,6.6,6.6,0,0,0,.1-1.4.9.9,0,0,0-.3-.5l-1.4-.8h-.5l-1.3.7a.3.3,0,0,1-.4,0l-1.5-1.5.9-1.5c.1-.1,0-.4,0-.5L210,255c-.1-.3-.3-.3-.5-.3h-1.7v-.2c-.1-.4-.3-.8-.4-1.3s-.2-.9.6-1.3,1.1-.7.9-1.5a2.2,2.2,0,0,1,.1-.8.7.7,0,0,0-.5-.8c-.4-.2-1-.4-1.1-.7s.1-.9.2-1.3.3-1,1.3-1h.6c.3.1.4,0,.6-.3l.8-1.4a.5.5,0,0,0,0-.5,7.6,7.6,0,0,0-.7-1.3l1.6-1.8a4.1,4.1,0,0,1,1.1.7.9.9,0,0,0,1.2,0l1.1-.6c.2-.1.4-.2.3-.5v-1.3c0-.3.1-.5.4-.5l1.7-.5Zm9.3,12.8a7.5,7.5,0,1,0-7.4,7.3A7.5,7.5,0,0,0,227.2,250.6Z"/><path d="M124.8,243a1.2,1.2,0,0,0,.8,2l.9.3c1.8.4,1.8.4,1.9,2.2a1,1,0,0,1-.7,1l-1.8.6a1.3,1.3,0,0,0-.5,2.2l.6.7c1.2,1.3,1.1,1.3.1,2.8s-.6.6-1.1.4l-1.9-.5a1.3,1.3,0,0,0-1.7,1.3,14.9,14.9,0,0,1,.2,2.1c0,.2,0,.6-.1.6a9,9,0,0,1-2.1.8c-.2.1-.6-.5-.9-.9l-.7-.8c-.8-1.1-1.8-1.1-2.5.2a13,13,0,0,1-1,1.5c0,.1-.2.4-.3.4a14.1,14.1,0,0,1-2.2-.6c-.2-.2-.2-.8-.3-1.3v-1.2a1.4,1.4,0,0,0-1.9-1.3l-1.8.8h-.7a5.5,5.5,0,0,1-1.3-1.8c-.2-.3.4-.8.6-1.3l.5-.6c.7-1.2.4-2.1-1-2.4l-1.7-.4c-.1,0-.4-.1-.4-.3a8.7,8.7,0,0,1-.1-2.3c0-.2.7-.4,1.1-.6l1.4-.4a1.5,1.5,0,0,0,.5-1.9l-1.8-2.1c.4-.6.7-1.3,1.1-1.8a1.3,1.3,0,0,1,.8-.3l2,.6a1.3,1.3,0,0,0,1.7-1.4,2.2,2.2,0,0,1-.1-.8c-.1-2-.1-2,1.8-2.5a1.1,1.1,0,0,1,.8.2l1.4,1.8.8.4c1,.2,1.2-.7,1.6-1.3s1.1-1.8,3-1.1a.9.9,0,0,1,.5.7c.1.7,0,1.4.1,2.1a1.3,1.3,0,0,0,.5.9c.6.7,1.2.2,1.7,0,2.3-.9,2.3-.9,3.7,1.2Zm-15.5,4.4a6.6,6.6,0,0,0,6.3,6.9,6.7,6.7,0,0,0,6.9-6.4,6.6,6.6,0,0,0-13.2-.5Z"/><path d="M171.7,258.9c-.5.4-.4,1.8.2,2a1.1,1.1,0,0,1,.6,1.7c-.1.2-.1.4-.2.6s-1.5-.3-2,.7.2,1.4.4,2.3l-.7.5c-.4.5-.9.6-1.3.1s-1.1,0-1.6.2-.5.6-.5,1a.8.8,0,0,1-.8,1.1c-.6.1-1.1.3-1.4-.5a1.4,1.4,0,0,0-.6-.7c-.8-.1-1.6-.1-1.9.9l-.3.4-.9-.2-.8-.3v-1.1c0-.6-1.3-1.4-1.8-1s-1.3.4-1.8-.3l-.4-.5.4-.7a1.5,1.5,0,0,0-1-2.1c-.9.1-1.2-.2-1.3-1s-.1-.4-.1-.7l.3-.2c.5-.3,1-.5,1-1.3a1.5,1.5,0,0,0-.9-1.5c-.7-.3-.1-.8-.1-1.3s.2-.7.7-.6a1.4,1.4,0,0,0,1.3-2.4c-.1-.1-.1-.3-.2-.4l1.3-1.2c.7.3,1.2.8,2.1.4s.6-1.3.7-2l1.8-.4.4.8c.3.8.9.5,1.4.6s.7-.2.9-.6.7-.9,1.4-.6.5.2.8.3v1c-.2.7.4.8.8,1.1a.8.8,0,0,0,1.1,0c.6-.6,1.1-.4,1.6.2l.5.6c-.2.7-1.1,1.1-.5,2.1s1.3.5,2,.7S172.9,258.1,171.7,258.9Zm-14,1a5.7,5.7,0,0,0,5.6,5.6,5.7,5.7,0,0,0,5.6-5.6,5.6,5.6,0,0,0-11.2,0Z"/><path d="M182.9,205.4c-.5.2-.7,0-1-.3a1.8,1.8,0,0,0-2.7,0c-.3.2-.6.4-1,.2a1,1,0,0,1-.4-1,2.1,2.1,0,0,0-1.6-2.2,1.4,1.4,0,0,1-.7-.6c-.3-.4,0-.7.4-1a2,2,0,0,0,.7-2.6,1,1,0,0,1,.1-.9c.1-.3.4-.5.9-.3a2,2,0,0,0,2.5-1,1,1,0,0,1,1.7,0,1.9,1.9,0,0,0,2.3,1.3c.3-.1.6.1.9.3a.8.8,0,0,1,.1,1,1.6,1.6,0,0,0,.5,2.5.8.8,0,0,1,.3,1c-.1.5-.4.6-.8.7a1.7,1.7,0,0,0-1.6,1.9C183.5,204.8,183.5,205.2,182.9,205.4Zm-2.2-7.6a2.7,2.7,0,0,0-2.6,2.7,2.6,2.6,0,0,0,5.2-.1A2.6,2.6,0,0,0,180.7,197.8Z"/><path d="M7.5,402.8c-.5.2-.7-.1-1-.4a1.9,1.9,0,0,0-2.6,0c-.4.3-.6.5-1.1.2s-.4-.5-.4-.9a1.9,1.9,0,0,0-1.6-2.2,1.2,1.2,0,0,1-.7-.7c-.2-.4,0-.7.4-.9a2,2,0,0,0,.7-2.6,1.3,1.3,0,0,1,.1-.9.7.7,0,0,1,1-.4,1.9,1.9,0,0,0,2.5-1,1,1,0,0,1,.7-.4,1.3,1.3,0,0,1,.9.4,1.9,1.9,0,0,0,2.3,1.3,1.5,1.5,0,0,1,.9.4c.4.3.4.6.1,1a1.7,1.7,0,0,0,.5,2.5.8.8,0,0,1,.3,1c-.1.5-.3.6-.7.6a1.8,1.8,0,0,0-1.7,2A.9.9,0,0,1,7.5,402.8Zm-2.1-7.6a2.7,2.7,0,0,0-2.7,2.6,2.6,2.6,0,0,0,2.7,2.6,2.6,2.6,0,0,0,2.5-2.6A2.5,2.5,0,0,0,5.4,395.2Z"/><path d="M161.2,241.4a5,5,0,0,1-2.4-.6.6.6,0,0,1-.3-.8.6.6,0,0,1,.8-.3,4.4,4.4,0,0,0,5.3-1.2,3.4,3.4,0,0,0,.6-1.2l-1.8-.5a4.3,4.3,0,0,1-2.1-1.4,2.3,2.3,0,0,1-.3-2.7,2.6,2.6,0,0,1,3.5-.3,4.2,4.2,0,0,1,1.9,3,1.3,1.3,0,0,1,.1.6,3,3,0,0,0,2.2-2.6,5,5,0,0,0-.1-2.1,15.9,15.9,0,0,1-3.4-.6,2.9,2.9,0,0,1-2.2-2.3,2.5,2.5,0,0,1,.8-1.8,3.1,3.1,0,0,1,2.6-.4c1.1.3,2,1.3,2.7,2.9a5.9,5.9,0,0,0,.4.8l1-.3a2.8,2.8,0,0,0,1.6-1.9,3.7,3.7,0,0,0,.1-1.8,7.6,7.6,0,0,1-5.9-1.9,2.2,2.2,0,0,1-.7-2.1,2.1,2.1,0,0,1,1.8-1.3,5.2,5.2,0,0,1,4.6,2l1,1.7a4.9,4.9,0,0,0,2.5-2.4,3.5,3.5,0,0,0,0-3.2.7.7,0,0,1,.3-.8.6.6,0,0,1,.8.3,4.7,4.7,0,0,1,0,4.3,7.1,7.1,0,0,1-3.2,3,4.6,4.6,0,0,1-.1,2.6,4.5,4.5,0,0,1-2.3,2.7l-1.2.3a7.1,7.1,0,0,1,.1,2.5,4.3,4.3,0,0,1-3.1,3.6h-.4a4.1,4.1,0,0,1-.8,1.9A5.4,5.4,0,0,1,161.2,241.4Zm1.6-8.3a1.1,1.1,0,0,0-.8.3,1.3,1.3,0,0,0,.2,1.3,6.7,6.7,0,0,0,3.1,1.3c0-.1-.1-.2-.1-.4a3.7,3.7,0,0,0-1.4-2.2A1.9,1.9,0,0,0,162.8,233.1Zm2.8-5.8a1.9,1.9,0,0,0-1,.3.6.6,0,0,0-.3.7c0,.6.7,1,1.3,1.2a6.7,6.7,0,0,0,2.6.5l-.2-.4a3.9,3.9,0,0,0-2-2.3Zm2.3-5.5h-.3c-.4.1-.7.2-.8.5s-.1.4.4.9a6.7,6.7,0,0,0,4.6,1.5,10.9,10.9,0,0,0-.8-1.5A4.5,4.5,0,0,0,167.9,221.8Z"/><path d="M173.5,82.7c-2.2-.8-4.8-.7-7.1-.9s-4.9.1-6.9,1.9a8.5,8.5,0,0,0-2.4,4.7,2.2,2.2,0,0,0-.9,1c-1.6,3.1-1.1,8.7,1.2,11.3s9.5,4.6,13.8,3.2a13.1,13.1,0,0,0,8.4-9.7C180.5,89.4,178.3,84.3,173.5,82.7Z" style="fill:#e89f2b"/><path d="M198.4,84.6c-3.6-4.1-24.2-17.3-29.5-17.3-8.3,0-24.7,12.1-31.7,18.2-3.4,3-3.9,4.5-1.2,5.2a75,75,0,0,0,16.3,2.1q11.8.5,23.7,0c5.4-.3,14.6-.8,20.1-2.2C199.9,89.6,203.2,90,198.4,84.6Z" style="fill:#488c93"/><path d="M167.9,75.3q-.9-36.9-.7-73.6" style="fill:#488c93"/><path d="M168.7,75.5c2.1-5.5,1.9,3.4,2.1-2.4s-.2-28.3-.2-34.6c0-12.4,1-25.1-1.8-37.3a1.7,1.7,0,0,0-3.2,0c-3,12.4-1.9,25.6-1.6,38.3.2,6.3-.9,26.3-.4,32.6s1.3-1.6,3.6,3.6c.3.7,1.3.4,1.5-.2Z" style="fill:#488c93"/><path d="M170.2,69.6a95.1,95.1,0,0,1,16.4,10.1c.8.5,1.6-.8.8-1.3a92.3,92.3,0,0,0-16.5-10.1c-.8-.4-1.6.9-.7,1.3Z" style="fill:#7db4b7"/><path d="M157,94.9a.4.4,0,0,0,0-.8.4.4,0,0,0,0,.8Z" style="fill:#f4b251"/><path d="M157.2,97c2,5.7,8.3,7.7,13.8,6a.4.4,0,0,0-.2-.8c-5,1.6-11.1-.1-12.9-5.4-.2-.5-.9-.3-.7.2Z" style="fill:#f4b251"/><path d="M189.2,81.3a.7.7,0,1,0,0-1.5.7.7,0,1,0,0,1.5Z" style="fill:#7db4b7"/></g></g></g></svg>
            </div>
                <p style="font-size:18px">@lang('No records were found!')</p>
        </div>
    @endif
</div>

<div list-control="listPagination">
    <div class="d-flex align-items-center mt-3">
        <div class="mr-auto">
            @lang('Showing')
            {{ $subscriptions->toArray()["per_page"]*($subscriptions->toArray()["current_page"]-1)+1 }}
            @lang('to')
            {{ ($subscriptions->toArray()["per_page"]*$subscriptions->toArray()["current_page"] > $subscriptions->toArray()["total"] ? $subscriptions->toArray()["total"] : $subscriptions->toArray()["per_page"]*$subscriptions->toArray()["current_page"]) }}
            @lang('of') {{ $subscriptions->total() }} @lang('entries')
        </div>
        <div>
            {{ $subscriptions->links() }}
        </div>
    </div>
</div>

<script>
    $(function() {
        SubscriptionMassUpdateList.init();

        // name inline control
        document.querySelectorAll('[list-control="inline-name"]').forEach(container => {
            var inlineName = new NameInlineControl(container);
        });
        
        // price inline control
        document.querySelectorAll('[list-control="inline-price"]').forEach(container => {
            var inlinePrice = new PriceInlineControl(container);
        });

        // description inline control
        document.querySelectorAll('[list-control="inline-description"]').forEach(container => {
            var descriptionInline = new DescriptionInlineControl(container);
        });

        // payment mode inline control
        document.querySelectorAll('[list-control="inline-payment-mode"]').forEach(container => {
            var paymentModeInline = new PaymentModeInlineControl(container);
        });

        // folder inline control
        document.querySelectorAll('[list-control="inline-folder"]').forEach(container => {
            var folderInline = new FolderInlineControl(container);
        });

        // category inline control
        document.querySelectorAll('[list-control="inline-category"]').forEach(container => {
            var categoryInline = new CategoryInlineControl(container);
        });

        // rating inline control
        document.querySelectorAll('[list-control="inline-rating"]').forEach(container => {
            var ratingInline = new RatingInlineControl(container);
        });

        // next_payment_date inline control
        document.querySelectorAll('[list-control="inline-next-payment-date"]').forEach(container => {
            var nextPaymentDateInline = new NextPaymentDateInlineControl(container);
        });

        // refund_date inline control
        document.querySelectorAll('[list-control="inline-refund-date"]').forEach(container => {
            var refundDateInline = new RefundDateInlineControl(container);
        });

        // refund_date inline control
        document.querySelectorAll('[list-control="inline-expiry-date"]').forEach(container => {
            var expiryDateInline = new ExpiryDateInlineControl(container);
        });

        // tags inline control
        document.querySelectorAll('[list-control="inline-tags"]').forEach(container => {
            var tagsInline = new TagsInlineControl(container);
        });

        // status toggle
        document.querySelectorAll('[list-control="status-toggle"]').forEach(checkbox => {
            var statusToggle = new StatusToggle(SubscriptionMassUpdate.list, checkbox);
        });

        // rating filter
        var ratingFilter = new RatingFilter(
            SubscriptionMassUpdate.list,
            document.querySelector('[list-control="filter-rating"]')
        );

        // product name filter
        var productNameFilter = new ProductNameFilter(
            SubscriptionMassUpdate.list,
            document.querySelector('[list-control="filter-product-name"]')
        );

        // status filter
        var statusFilter = new StatusFilter(
            SubscriptionMassUpdate.list,
            document.querySelector('[list-control="filter-status"]')
        );

        // folder filter
        var folderFilter = new FolderFilter(
            SubscriptionMassUpdate.list,
            document.querySelector('[list-control="filter-folder"]')
        );

        // category filter
        var categoryFilter = new CategoryFilter(
            SubscriptionMassUpdate.list,
            document.querySelector('[list-control="filter-category"]')
        );

        // payment filter
        var paymentFilter = new PaymentFilter(
            SubscriptionMassUpdate.list,
            document.querySelector('[list-control="filter-payment"]')
        );

        // expiry filter
        var expiryFilter = new ExpiryFilter(
            SubscriptionMassUpdate.list,
            document.querySelector('[list-control="filter-expiry"]')
        );

        // refund filter
        var refundFilter = new RefundFilter(
            SubscriptionMassUpdate.list,
            document.querySelector('[list-control="filter-refund"]')
        );

        // due filter
        var dueFilter = new DueFilter(
            SubscriptionMassUpdate.list,
            document.querySelector('[list-control="filter-due"]')
        );

        // tag filter
        var tagFilter = new TagFilter(
            SubscriptionMassUpdate.list,
            document.querySelector('[list-control="filter-tag"]')
        );

    });

    var StatusToggle = class {
        constructor(list, checkbox) {
            this.list = list;
            this.checkbox = checkbox;
            this.saveUrl = '{{ action('App\Http\Controllers\Client\SubscriptionController@massUpdateSave') }}';
            this.subscription_id = this.checkbox.getAttribute('subscription-id');

            // events
            this.events();
        }

        events() {
            var _this = this;

            this.checkbox.addEventListener('change', (e) => {
                _this.save();
            }); 
        }

        isChecked() {
            return this.checkbox.checked;
        }

        save() {
            // prevent change back to draft
            if (this.isChecked()) {
                $(this.checkbox).closest('label').css('pointer-events','none');
            }

            $.ajax({
                url: this.saveUrl,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: this.isChecked() ? '1' : '0',
                    subscription_id: this.subscription_id,
                }
            }).done(function (response) {
            }).fail(function (response) {
            }).always(function() {
            });
        }
         
    }

    var SubscriptionMassUpdateList = {
        init: function() {
            // click on pagination link
            document.querySelectorAll('[list-control="listPagination"] a.page-link').forEach(pageLink => {
                pageLink.addEventListener("click", (e) => {
                    e.preventDefault();

                    var url = pageLink.getAttribute('href');

                    // load list with pagination url
                    SubscriptionMassUpdate.list.loadUrl(url);
                });
            });

            // click on sort header
            document.querySelectorAll('[list-control="sort"]').forEach(link => {
                link.addEventListener("click", (e) => {
                    e.preventDefault();

                    var sort_name = link.getAttribute('sort-name');
                    var sort_direction = link.getAttribute('sort-direction');

                    // load list with pagination url
                    SubscriptionMassUpdate.getList().sortList(sort_name, sort_direction);
                });
            });
        }
    }

    // status filter
    var StatusFilter = class extends ListFilter {
        constructor(list, container) {
            super(list, container);

            // default data
            this.data = {
                status: null,
            };

            // set active if list has filter
            if (this.list.getFilter('status')) {
                this.setActive();
                this.data = this.list.getFilter('status');
            } else {
                this.getRemoveButton().style.display = 'none';
            }

            // render data value
            this.applyData();

            // events
            this.addEvents();
        }

        getCheckbox() {
            return this.container.querySelector('[name=status]');
        }

        applyData() {
            var _this = this;

            // current value
            this.getCheckbox().checked = this.getData().status;
        }

        filter() {
            if (this.isActive()) {
                this.list.setFilter('status', this.getData());
            } else {
                this.list.setFilter('status', null);
            }

            this.list.load();
        }

        save() {
            // set active
            this.setActive();

            // save data
            this.updateData();

            // load list
            this.filter();
        }

        addEvents() {
            var _this = this;

            // click on save filter button
            this.getSaveButton().addEventListener('click', (e) => {
                _this.save();
            });

            // click on remove filter button
            this.getRemoveButton().addEventListener('click', (e) => {
                // set active
                _this.removeActive();

                // save data
                _this.updateData();

                // load list
                _this.filter();
            });

            // prevent submit 
            this.getForm().addEventListener('submit', (e) => {
                e.preventDefault();
                
                _this.save();

                return false;
            });
        }

        getData() {
            return this.data;
        }

        updateData() {
            this.data.status = this.getCheckbox().checked;
        }

        getSaveButton() {
            return this.container.querySelector('[filter-control="save"]');
        }

        getRemoveButton() {
            return this.container.querySelector('[filter-control="remove"]');
        }
    }

    // folder filter
    var FolderFilter = class extends ListFilter {
        constructor(list, container) {
            super(list, container);

            // default data
            this.data = {
                folder_id: null,
            };

            // set active if list has filter
            if (this.list.getFilter('folder')) {
                this.setActive();
                this.data = this.list.getFilter('folder');
            } else {
                this.getRemoveButton().style.display = 'none';
            }

            // render data value
            this.applyData();

            // events
            this.addEvents();
        }

        getSelectBox() {
            return this.container.querySelector('[name=folder_id]');
        }

        applyData() {
            var _this = this;

            // current value
            this.getSelectBox().value = this.getData().folder_id;
        }

        filter() {
            if (this.isActive()) {
                this.list.setFilter('folder', this.getData());
            } else {
                this.list.setFilter('folder', null);
            }

            this.list.load();
        }

        save() {
            // set active
            this.setActive();

            // save data
            this.updateData();

            // load list
            this.filter();
        }

        addEvents() {
            var _this = this;

            // click on save filter button
            this.getSaveButton().addEventListener('click', (e) => {
                _this.save();
            });

            // click on remove filter button
            this.getRemoveButton().addEventListener('click', (e) => {
                // set active
                _this.removeActive();

                // save data
                _this.updateData();

                // load list
                _this.filter();
            });

            // prevent submit 
            this.getForm().addEventListener('submit', (e) => {
                e.preventDefault();
                
                _this.save();

                return false;
            });
        }

        getData() {
            return this.data;
        }

        updateData() {
            this.data.folder_id = this.getSelectBox().value;
        }

        getSaveButton() {
            return this.container.querySelector('[filter-control="save"]');
        }

        getRemoveButton() {
            return this.container.querySelector('[filter-control="remove"]');
        }
    }

    // category filter
    var CategoryFilter = class extends ListFilter {
        constructor(list, container) {
            super(list, container);

            // default data
            this.data = {
                category_id: null,
            };

            // set active if list has filter
            if (this.list.getFilter('category')) {
                this.setActive();
                this.data = this.list.getFilter('category');
            } else {
                this.getRemoveButton().style.display = 'none';
            }

            // render data value
            this.applyData();

            // events
            this.addEvents();
        }

        getSelectBox() {
            return this.container.querySelector('[name=category_id]');
        }

        applyData() {
            var _this = this;

            // current value
            this.getSelectBox().value = this.getData().category_id;
        }

        filter() {
            if (this.isActive()) {
                this.list.setFilter('category', this.getData());
            } else {
                this.list.setFilter('category', null);
            }

            this.list.load();
        }

        save() {
            // set active
            this.setActive();

            // save data
            this.updateData();

            // load list
            this.filter();
        }

        addEvents() {
            var _this = this;

            // click on save filter button
            this.getSaveButton().addEventListener('click', (e) => {
                _this.save();
            });

            // click on remove filter button
            this.getRemoveButton().addEventListener('click', (e) => {
                // set active
                _this.removeActive();

                // save data
                _this.updateData();

                // load list
                _this.filter();
            });

            // prevent submit 
            this.getForm().addEventListener('submit', (e) => {
                e.preventDefault();
                
                _this.save();

                return false;
            });
        }

        getData() {
            return this.data;
        }

        updateData() {
            this.data.category_id = this.getSelectBox().value;
        }

        getSaveButton() {
            return this.container.querySelector('[filter-control="save"]');
        }

        getRemoveButton() {
            return this.container.querySelector('[filter-control="remove"]');
        }
    }

    // payment filter
    var PaymentFilter = class extends ListFilter {
        constructor(list, container) {
            super(list, container);

            // default data
            this.data = {
                payment_mode_id: null,
            };

            // set active if list has filter
            if (this.list.getFilter('payment')) {
                this.setActive();
                this.data = this.list.getFilter('payment');
            } else {
                this.getRemoveButton().style.display = 'none';
            }

            // render data value
            this.applyData();

            // events
            this.addEvents();
        }

        getSelectBox() {
            return this.container.querySelector('[name=payment_mode_id]');
        }

        applyData() {
            var _this = this;

            // current value
            this.getSelectBox().value = this.getData().payment_mode_id;
        }

        filter() {
            if (this.isActive()) {
                this.list.setFilter('payment', this.getData());
            } else {
                this.list.setFilter('payment', null);
            }

            this.list.load();
        }

        save() {
            // set active
            this.setActive();

            // save data
            this.updateData();

            // load list
            this.filter();
        }

        addEvents() {
            var _this = this;

            // click on save filter button
            this.getSaveButton().addEventListener('click', (e) => {
                _this.save();
            });

            // click on remove filter button
            this.getRemoveButton().addEventListener('click', (e) => {
                // set active
                _this.removeActive();

                // save data
                _this.updateData();

                // load list
                _this.filter();
            });

            // prevent submit 
            this.getForm().addEventListener('submit', (e) => {
                e.preventDefault();
                
                _this.save();

                return false;
            });
        }

        getData() {
            return this.data;
        }

        updateData() {
            this.data.payment_mode_id = this.getSelectBox().value;
        }

        getSaveButton() {
            return this.container.querySelector('[filter-control="save"]');
        }

        getRemoveButton() {
            return this.container.querySelector('[filter-control="remove"]');
        }
    }

    // expiry filter
    var ExpiryFilter = class extends ListFilter {
        constructor(list, container) {
            super(list, container);

            // default data
            this.data = {
                expiry_date: null,
            };

            // set active if list has filter
            if (this.list.getFilter('expiry')) {
                this.setActive();
                this.data = this.list.getFilter('expiry');
            } else {
                this.getRemoveButton().style.display = 'none';
            }

            // render data value
            this.applyData();

            // events
            this.addEvents();

            // 
            $(this.getInput()).datepicker({
                format: 'yyyy-mm-dd',
            });
        }

        getInput() {
            return this.container.querySelector('[name=expiry_date]');
        }

        applyData() {
            var _this = this;

            // current value
            this.getInput().value = this.getData().expiry_date;
        }

        filter() {
            if (this.isActive()) {
                this.list.setFilter('expiry', this.getData());
            } else {
                this.list.setFilter('expiry', null);
            }

            this.list.load();
        }

        updateData() {
            this.data.expiry_date = this.getInput().value;
        }

        save() {
            // set active
            this.setActive();

            // save data
            this.updateData();

            // load list
            this.filter();
        }

        addEvents() {
            var _this = this;

            // click on save filter button
            this.getSaveButton().addEventListener('click', (e) => {
                _this.save();
            });

            // click on remove filter button
            this.getRemoveButton().addEventListener('click', (e) => {
                // set active
                _this.removeActive();

                // save data
                _this.updateData();

                // load list
                _this.filter();
            });

            // prevent submit 
            this.getForm().addEventListener('submit', (e) => {
                e.preventDefault();
                
                _this.save();

                return false;
            });
        }

        getData() {
            return this.data;
        }

        getSaveButton() {
            return this.container.querySelector('[filter-control="save"]');
        }

        getRemoveButton() {
            return this.container.querySelector('[filter-control="remove"]');
        }
    }

    // refund filter
    var RefundFilter = class extends ListFilter {
        constructor(list, container) {
            super(list, container);

            // default data
            this.data = {
                refund_date: null,
            };

            // set active if list has filter
            if (this.list.getFilter('refund')) {
                this.setActive();
                this.data = this.list.getFilter('refund');
            } else {
                this.getRemoveButton().style.display = 'none';
            }

            // render data value
            this.applyData();

            // events
            this.addEvents();

            // 
            $(this.getInput()).datepicker({
                format: 'yyyy-mm-dd',
            });
        }

        getInput() {
            return this.container.querySelector('[name=refund_date]');
        }

        applyData() {
            var _this = this;

            // current value
            this.getInput().value = this.getData().refund_date;
        }

        filter() {
            if (this.isActive()) {
                this.list.setFilter('refund', this.getData());
            } else {
                this.list.setFilter('refund', null);
            }

            this.list.load();
        }

        updateData() {
            this.data.refund_date = this.getInput().value;
        }

        save() {
            // set active
            this.setActive();

            // save data
            this.updateData();

            // load list
            this.filter();
        }

        addEvents() {
            var _this = this;

            // click on save filter button
            this.getSaveButton().addEventListener('click', (e) => {
                _this.save();
            });

            // click on remove filter button
            this.getRemoveButton().addEventListener('click', (e) => {
                // set active
                _this.removeActive();

                // save data
                _this.updateData();

                // load list
                _this.filter();
            });

            // prevent submit 
            this.getForm().addEventListener('submit', (e) => {
                e.preventDefault();
                
                _this.save();

                return false;
            });
        }

        getData() {
            return this.data;
        }

        getSaveButton() {
            return this.container.querySelector('[filter-control="save"]');
        }

        getRemoveButton() {
            return this.container.querySelector('[filter-control="remove"]');
        }
    }

    // due filter
    var DueFilter = class extends ListFilter {
        constructor(list, container) {
            super(list, container);

            // default data
            this.data = {
                due_date: null,
            };

            // set active if list has filter
            if (this.list.getFilter('due')) {
                this.setActive();
                this.data = this.list.getFilter('due');
            } else {
                this.getRemoveButton().style.display = 'none';
            }

            // render data value
            this.applyData();

            // events
            this.addEvents();

            // 
            $(this.getInput()).datepicker({
                format: 'yyyy-mm-dd',
            });
        }

        getInput() {
            return this.container.querySelector('[name=due_date]');
        }

        applyData() {
            var _this = this;

            // current value
            this.getInput().value = this.getData().due_date;
        }

        filter() {
            if (this.isActive()) {
                this.list.setFilter('due', this.getData());
            } else {
                this.list.setFilter('due', null);
            }

            this.list.load();
        }

        updateData() {
            this.data.due_date = this.getInput().value;
        }

        save() {
            // set active
            this.setActive();

            // save data
            this.updateData();

            // load list
            this.filter();
        }

        addEvents() {
            var _this = this;

            // click on save filter button
            this.getSaveButton().addEventListener('click', (e) => {
                _this.save();
            });

            // click on remove filter button
            this.getRemoveButton().addEventListener('click', (e) => {
                // set active
                _this.removeActive();

                // save data
                _this.updateData();

                // load list
                _this.filter();
            });

            // prevent submit 
            this.getForm().addEventListener('submit', (e) => {
                e.preventDefault();
                
                _this.save();

                return false;
            });
        }

        getData() {
            return this.data;
        }

        getSaveButton() {
            return this.container.querySelector('[filter-control="save"]');
        }

        getRemoveButton() {
            return this.container.querySelector('[filter-control="remove"]');
        }
    }

    // tag filter
    var TagFilter = class extends ListFilter {
        constructor(list, container) {
            super(list, container);

            // default data
            this.data = {
                tag_id: null,
            };

            // set active if list has filter
            if (this.list.getFilter('tag')) {
                this.setActive();
                this.data = this.list.getFilter('tag');
            } else {
                this.getRemoveButton().style.display = 'none';
            }

            // render data value
            this.applyData();

            // events
            this.addEvents();
        }

        getInput() {
            return this.container.querySelector('[name=tag_id]');
        }

        applyData() {
            var _this = this;

            // current value
            this.getInput().value = this.getData().tag_id;
        }

        filter() {
            if (this.isActive()) {
                this.list.setFilter('tag', this.getData());
            } else {
                this.list.setFilter('tag', null);
            }

            this.list.load();
        }

        updateData() {
            this.data.tag_id = this.getInput().value;
        }

        save() {
            // set active
            this.setActive();

            // save data
            this.updateData();

            // load list
            this.filter();
        }

        addEvents() {
            var _this = this;

            // click on save filter button
            this.getSaveButton().addEventListener('click', (e) => {
                _this.save();
            });

            // click on remove filter button
            this.getRemoveButton().addEventListener('click', (e) => {
                // set active
                _this.removeActive();

                // save data
                _this.updateData();

                // load list
                _this.filter();
            });

            // prevent submit 
            this.getForm().addEventListener('submit', (e) => {
                e.preventDefault();
                
                _this.save();

                return false;
            });
        }

        getData() {
            return this.data;
        }

        getSaveButton() {
            return this.container.querySelector('[filter-control="save"]');
        }

        getRemoveButton() {
            return this.container.querySelector('[filter-control="remove"]');
        }
    }

    // product name filter
    var ProductNameFilter = class extends ListFilter {
        constructor(list, container) {
            super(list, container);

            // default data
            this.data = {
                product_name: null,
            };

            // set active if list has filter
            if (this.list.getFilter('product_name')) {
                this.setActive();
                this.data = this.list.getFilter('product_name');
            } else {
                this.getRemoveButton().style.display = 'none';
            }

            // render data value
            this.applyData();

            // events
            this.addEvents();
        }

        getProductNameInput() {
            return this.container.querySelector('[name=product_name]')
        }

        applyData() {
            var _this = this;

            // current value
            this.getProductNameInput().value = this.getData().product_name;
        }

        filter() {
            if (this.isActive()) {
                this.list.setFilter('product_name', this.getData());
            } else {
                this.list.setFilter('product_name', null);
            }

            this.list.load();
        }

        save() {
            // set active
            this.setActive();

            // save data
            this.updateData();

            // load list
            this.filter();
        }

        addEvents() {
            var _this = this;

            // click on save filter button
            this.getSaveButton().addEventListener('click', (e) => {
                _this.save();
            });

            // click on remove filter button
            this.getRemoveButton().addEventListener('click', (e) => {
                // set active
                _this.removeActive();

                // save data
                _this.updateData();

                // load list
                _this.filter();
            });

            // prevent submit 
            this.getForm().addEventListener('submit', (e) => {
                e.preventDefault();
                
                _this.save();

                return false;
            });
        }

        getData() {
            return this.data;
        }

        updateData() {
            this.data.product_name = this.getProductNameInput().value;
        }

        getSaveButton() {
            return this.container.querySelector('[filter-control="save"]');
        }

        getRemoveButton() {
            return this.container.querySelector('[filter-control="remove"]');
        }
    }

    // rating filter
    var RatingFilter = class extends ListFilter {
        constructor(list, container) {
            super(list, container);

            // default data
            this.data = {
                check_all: true,
                stars: [
                    '0','1','2','3','4','5','6','7','8','9','10'
                ],
            };

            // set active if list has filter
            if (this.list.getFilter('rating')) {
                this.setActive();
                this.data = this.list.getFilter('rating');
            } else {
                this.getRemoveButton().style.display = 'none';
            }

            // render data value
            this.applyData();

            // events
            this.addEvents();
        }

        filter() {
            if (this.isActive()) {
                this.list.setFilter('rating', this.getData());
            } else {
                this.list.setFilter('rating', null);
            }

            this.list.load();
        }

        addEvents() {
            var _this = this;

            // click on save filter button
            this.getSaveButton().addEventListener('click', (e) => {
                // set active
                _this.setActive();

                // save data
                _this.updateData();

                // load list
                _this.filter();
            });

            // click on remove filter button
            this.getRemoveButton().addEventListener('click', (e) => {
                // set active
                _this.removeActive();

                // save data
                _this.updateData();

                // load list
                _this.filter();
            });

            // click on check all button
            this.getRatingAllCheckbox().addEventListener('click', (e) => {
                var checked = _this.getRatingAllCheckbox().checked;

                if (checked) {
                    _this.getRatingCheckboxes().forEach((checkbox) => {
                        checkbox.checked = true;
                    });
                } else {
                    _this.getRatingCheckboxes().forEach((checkbox) => {
                        checkbox.checked = false;
                    });
                }
            });

            // click on checkbox button
            this.getRatingCheckboxes().forEach((checkbox) => {
                checkbox.addEventListener('click', (e) => {
                    if (_this.checkedCount() == _this.getRatingCheckboxes().length) {
                        _this.getRatingAllCheckbox().checked = true;
                    } else {
                        _this.getRatingAllCheckbox().checked = false;
                    }
                });
            });
        }

        checkedCount() {
            var count = 0;
            this.getRatingCheckboxes().forEach((checkbox) => {
                if (checkbox.checked) {
                    count += 1;
                }
            });
            return count;
        }

        getData() {
            return this.data;
        }

        updateData() {
            this.data.check_all = this.getRatingAllCheckbox().checked;
            this.data.stars = $(this.container).find('[name="stars[]"]:checked')
                .map(function(checkbox) {
                    return $(this).val();
                }).toArray();
        }

        applyData() {
            var _this = this;

            // check all
            if (this.getData().check_all) {
                this.getRatingAllCheckbox().checked = true;
            }

            // check star
            this.getRatingCheckboxes().forEach((checkbox) => {
                var value = checkbox.getAttribute('data-value');
                if (_this.getData().stars.includes(value)) {
                    checkbox.checked = true;
                }
            });
        }

        getRatingAllCheckbox() {
            return this.container.querySelector('[rating-control="all"]');
        }

        getRatingCheckboxes() {
            return this.container.querySelectorAll('[rating-control="star"]');
        }

        getSaveButton() {
            return this.container.querySelector('[filter-control="save"]');
        }

        getRemoveButton() {
            return this.container.querySelector('[filter-control="remove"]');
        }
    }

    // Expiry date
    var ExpiryDateInlineControl = class {
        constructor(container, currentDate) {
            this.inlineControl = new InlineControl(container);
            this.currentValue = this.getInputValue();

            // events for name inline control
            this.events();

            // 
            $(this.getInput()).datepicker({
                format: 'yyyy-mm-dd',
            });
        }

        getCurrentValue() {
            return this.currentValue;
        }

        setCurrentValue(value) {
            this.currentValue = value;
        }

        resetCurrentValue() {
            this.getInput().value = this.getCurrentValue();
        }

        getInput() {
            return this.inlineControl.container.querySelector('[name=expiry_date]');
        }

        getInputValue() {
            return this.getInput().value.trim();
        }

        events() {
            var _this = this;

            // submit form
            this.inlineControl.getInlineForm().addEventListener('submit', (e) => {
                e.preventDefault();

                // save
                _this.validateThenSave();
            });

            // click on inline control
            this.inlineControl.getInlineValueBox().addEventListener('click', (e) => {
                e.preventDefault();

                // save
                _this.inlineControl.enableEditMode(function() {
                    // _this.getInput().focus();
                });
            });

            // click save button
            this.inlineControl.getSaveButon().addEventListener('click', (e) => {
                e.preventDefault();

                // save
                _this.validateThenSave();
            });

            // click cancel button
            this.inlineControl.getCancelButon().addEventListener('click', (e) => {
                e.preventDefault();

                // reset input value
                _this.resetCurrentValue();

                // disable edit mode
                _this.inlineControl.disableEditMode();
            });
        }
        
        validate() {
            // input is empty
            if (!this.getInputValue()) {
                alert('Due date can not be empty!');
                return false;
            }

            return true;
        }

        validateThenSave() {
            var _this = this;
            
            if (!this.validate()) {
                return;
            }

            this.inlineControl.save(function(response) {
                const expiry_date = response.expiry_date;
                // apply value to value container
                _this.getValueContainer().innerHTML = expiry_date;

                // update current value
                _this.setCurrentValue(expiry_date);

                // hide
                _this.inlineControl.disableEditMode();
            });
        }

        getValueContainer() {
            return this.inlineControl.container.querySelector('[inline-control=value-container]');
        }
    }

    // Refund date
    var RefundDateInlineControl = class {
        constructor(container, currentDate) {
            this.inlineControl = new InlineControl(container);
            this.currentValue = this.getInputValue();

            // events for name inline control
            this.events();

            // 
            $(this.getInput()).datepicker({
                format: 'yyyy-mm-dd',
            });
        }

        getCurrentValue() {
            return this.currentValue;
        }

        setCurrentValue(value) {
            this.currentValue = value;
        }

        resetCurrentValue() {
            this.getInput().value = this.getCurrentValue();
        }

        getInput() {
            return this.inlineControl.container.querySelector('[name=refund_date]');
        }

        getInputValue() {
            return this.getInput().value.trim();
        }

        events() {
            var _this = this;

            // submit form
            this.inlineControl.getInlineForm().addEventListener('submit', (e) => {
                e.preventDefault();

                // save
                _this.validateThenSave();
            });

            // click on inline control
            this.inlineControl.getInlineValueBox().addEventListener('click', (e) => {
                e.preventDefault();

                // save
                _this.inlineControl.enableEditMode(function() {
                    // _this.getInput().focus();
                });
            });

            // click save button
            this.inlineControl.getSaveButon().addEventListener('click', (e) => {
                e.preventDefault();

                // save
                _this.validateThenSave();
            });

            // click cancel button
            this.inlineControl.getCancelButon().addEventListener('click', (e) => {
                e.preventDefault();

                // reset input value
                _this.resetCurrentValue();

                // disable edit mode
                _this.inlineControl.disableEditMode();
            });
        }
        
        validate() {
            // input is empty
            if (!this.getInputValue()) {
                alert('Due date can not be empty!');
                return false;
            }

            return true;
        }

        validateThenSave() {
            var _this = this;
            
            if (!this.validate()) {
                return;
            }

            this.inlineControl.save(function(response) {
                const refund_date = response.refund_date;
                // apply value to value container
                _this.getValueContainer().innerHTML = refund_date;

                // update current value
                _this.setCurrentValue(refund_date);

                // hide
                _this.inlineControl.disableEditMode();
            });
        }

        getValueContainer() {
            return this.inlineControl.container.querySelector('[inline-control=value-container]');
        }
    }

    // Next payment date
    var NextPaymentDateInlineControl = class {
        constructor(container, currentDate) {
            this.inlineControl = new InlineControl(container);
            this.currentValue = this.getInputValue();

            // events for name inline control
            this.events();

            // 
            $(this.getInput()).datepicker({
                format: 'yyyy-mm-dd',
            });
        }

        getCurrentValue() {
            return this.currentValue;
        }

        setCurrentValue(value) {
            this.currentValue = value;
        }

        resetCurrentValue() {
            this.getInput().value = this.getCurrentValue();
        }

        getInput() {
            return this.inlineControl.container.querySelector('[name=next_payment_date]');
        }

        getInputValue() {
            return this.getInput().value.trim();
        }

        events() {
            var _this = this;

            // submit form
            this.inlineControl.getInlineForm().addEventListener('submit', (e) => {
                e.preventDefault();

                // save
                _this.validateThenSave();
            });

            // click on inline control
            this.inlineControl.getInlineValueBox().addEventListener('click', (e) => {
                e.preventDefault();

                // save
                _this.inlineControl.enableEditMode(function() {
                    // _this.getInput().focus();
                });
            });

            // click save button
            this.inlineControl.getSaveButon().addEventListener('click', (e) => {
                e.preventDefault();

                // save
                _this.validateThenSave();
            });

            // click cancel button
            this.inlineControl.getCancelButon().addEventListener('click', (e) => {
                e.preventDefault();

                // reset input value
                _this.resetCurrentValue();

                // disable edit mode
                _this.inlineControl.disableEditMode();
            });
        }
        
        validate() {
            // input is empty
            if (!this.getInputValue()) {
                alert('Due date can not be empty!');
                return false;
            }

            return true;
        }

        validateThenSave() {
            var _this = this;
            
            if (!this.validate()) {
                return;
            }

            this.inlineControl.save(function(response) {
                const next_payment_date = response.next_payment_date;
                // apply value to value container
                _this.getValueContainer().innerHTML = next_payment_date;

                // update current value
                _this.setCurrentValue(next_payment_date);

                // hide
                _this.inlineControl.disableEditMode();
            });
        }

        getValueContainer() {
            return this.inlineControl.container.querySelector('[inline-control=value-container]');
        }
    }

    var RatingInlineControl = class {
        constructor(container, currentRating) {
            this.inlineControl = new InlineControl(container);
            this.currentValue = this.getInputValue();

            // events for name inline control
            this.events();
        }

        getCurrentValue() {
            return this.currentValue;
        }

        setCurrentValue(value) {
            this.currentValue = value;
        }

        resetCurrentValue() {
            this.getInput().value = this.getCurrentValue();
        }

        getInput() {
            return this.inlineControl.container.querySelector('[name=rating]');
        }

        getInputValue() {
            return this.getInput().value.trim();
        }

        events() {
            var _this = this;

            // submit form
            this.inlineControl.getInlineForm().addEventListener('submit', (e) => {
                e.preventDefault();

                // save
                _this.validateThenSave();
            });

            // click on inline control
            this.inlineControl.getInlineValueBox().addEventListener('click', (e) => {
                e.preventDefault();

                // save
                _this.inlineControl.enableEditMode(function() {
                    _this.getInput().focus();
                });
            });

            // click save button
            this.inlineControl.getSaveButon().addEventListener('click', (e) => {
                e.preventDefault();

                // save
                _this.validateThenSave();
            });

            // click cancel button
            this.inlineControl.getCancelButon().addEventListener('click', (e) => {
                e.preventDefault();

                // reset input value
                _this.resetCurrentValue();

                // disable edit mode
                _this.inlineControl.disableEditMode();
            });
        }
        
        validate() {
            // input is empty
            if (!this.getInputValue()) {
                alert('Rating can not be empty!');
                return false;
            }

            return true;
        }

        validateThenSave() {
            var _this = this;
            
            if (!this.validate()) {
                return;
            }

            this.inlineControl.save(function(response) {
                const rating = response.rating;
                // apply value to value container
                _this.getValueContainer().innerHTML = rating;

                // update current value
                _this.setCurrentValue(rating);

                // hide
                _this.inlineControl.disableEditMode();
            });
        }

        getValueContainer() {
            return this.inlineControl.container.querySelector('[inline-control=value-container]');
        }
    }

    var PaymentModeInlineControl = class {
        constructor(container, currentPaymentMode) {
            this.inlineControl = new InlineControl(container);
            this.currentValue = this.getInputValue();

            // events for name inline control
            this.events();
        }

        getCurrentValue() {
            return this.currentValue;
        }

        setCurrentValue(value) {
            this.currentValue = value;
        }

        resetCurrentValue() {
            this.getInput().value = this.getCurrentValue();
        }

        getInput() {
            return this.inlineControl.container.querySelector('[name=payment_mode_id]');
        }

        getInputValue() {
            return this.getInput().value.trim();
        }

        events() {
            var _this = this;

            // submit form
            this.inlineControl.getInlineForm().addEventListener('submit', (e) => {
                e.preventDefault();

                // save
                _this.validateThenSave();
            });

            // click on inline control
            this.inlineControl.getInlineValueBox().addEventListener('click', (e) => {
                e.preventDefault();

                // save
                _this.inlineControl.enableEditMode(function() {
                    _this.getInput().focus();
                });
            });

            // click save button
            this.inlineControl.getSaveButon().addEventListener('click', (e) => {
                e.preventDefault();

                // save
                _this.validateThenSave();
            });

            // click cancel button
            this.inlineControl.getCancelButon().addEventListener('click', (e) => {
                e.preventDefault();

                // reset input value
                _this.resetCurrentValue();

                // disable edit mode
                _this.inlineControl.disableEditMode();
            });
        }
        
        validate() {
            // input is empty
            if (!this.getInputValue()) {
                alert('Payment mode can not be empty!');
                return false;
            }

            return true;
        }

        validateThenSave() {
            var _this = this;
            
            if (!this.validate()) {
                return;
            }

            this.inlineControl.save(function(response) {
                const payment_mode = response.payment_mode;
                // apply value to value container
                _this.getValueContainer().innerHTML = payment_mode;

                // update current value
                _this.setCurrentValue(payment_mode);

                // hide
                _this.inlineControl.disableEditMode();
            });
        }

        getValueContainer() {
            return this.inlineControl.container.querySelector('[inline-control=value-container]');
        }
    }

    var FolderInlineControl = class {
        constructor(container, currentFolder) {
            this.inlineControl = new InlineControl(container);
            this.currentValue = this.getInputValue();

            // events for name inline control
            this.events();
        }

        getCurrentValue() {
            return this.currentValue;
        }

        setCurrentValue(value) {
            this.currentValue = value;
        }

        resetCurrentValue() {
            this.getInput().value = this.getCurrentValue();
        }

        getInput() {
            return this.inlineControl.container.querySelector('[name=folder_id]');
        }

        getInputValue() {
            return this.getInput().value.trim();
        }

        events() {
            var _this = this;

            // submit form
            this.inlineControl.getInlineForm().addEventListener('submit', (e) => {
                e.preventDefault();

                // save
                _this.validateThenSave();
            });

            // click on inline control
            this.inlineControl.getInlineValueBox().addEventListener('click', (e) => {
                e.preventDefault();

                // save
                _this.inlineControl.enableEditMode(function() {
                    _this.getInput().focus();
                });
            });

            // click save button
            this.inlineControl.getSaveButon().addEventListener('click', (e) => {
                e.preventDefault();

                // save
                _this.validateThenSave();
            });

            // click cancel button
            this.inlineControl.getCancelButon().addEventListener('click', (e) => {
                e.preventDefault();

                // reset input value
                _this.resetCurrentValue();

                // disable edit mode
                _this.inlineControl.disableEditMode();
            });
        }
        
        validate() {
            // input is empty
            if (!this.getInputValue()) {
                alert('Select folder!');
                return false;
            }

            return true;
        }

        validateThenSave() {
            var _this = this;
            
            if (!this.validate()) {
                return;
            }

            this.inlineControl.save(function(response) {
                const folder = response.folder;
                // apply value to value container
                _this.getValueContainer().innerHTML = folder;

                // update current value
                _this.setCurrentValue(folder);

                // hide
                _this.inlineControl.disableEditMode();
            });
        }

        getValueContainer() {
            return this.inlineControl.container.querySelector('[inline-control=value-container]');
        }
    }

    var CategoryInlineControl = class {
        constructor(container, currentCategory) {
            this.inlineControl = new InlineControl(container);
            this.currentValue = this.getInputValue();

            // events for name inline control
            this.events();
        }

        getCurrentValue() {
            return this.currentValue;
        }

        setCurrentValue(value) {
            this.currentValue = value;
        }

        resetCurrentValue() {
            this.getInput().value = this.getCurrentValue();
        }

        getInput() {
            return this.inlineControl.container.querySelector('[name=category_id]');
        }

        getInputValue() {
            return this.getInput().value.trim();
        }

        events() {
            var _this = this;

            // submit form
            this.inlineControl.getInlineForm().addEventListener('submit', (e) => {
                e.preventDefault();

                // save
                _this.validateThenSave();
            });

            // click on inline control
            this.inlineControl.getInlineValueBox().addEventListener('click', (e) => {
                e.preventDefault();

                // save
                _this.inlineControl.enableEditMode(function() {
                    _this.getInput().focus();
                });
            });

            // click save button
            this.inlineControl.getSaveButon().addEventListener('click', (e) => {
                e.preventDefault();

                // save
                _this.validateThenSave();
            });

            // click cancel button
            this.inlineControl.getCancelButon().addEventListener('click', (e) => {
                e.preventDefault();

                // reset input value
                _this.resetCurrentValue();

                // disable edit mode
                _this.inlineControl.disableEditMode();
            });
        }
        
        validate() {
            // input is empty
            if (!this.getInputValue()) {
                alert('Select cateogry!');
                return false;
            }

            return true;
        }

        validateThenSave() {
            var _this = this;
            
            if (!this.validate()) {
                return;
            }

            this.inlineControl.save(function(response) {
                const category = response.category;
                // apply value to value container
                _this.getValueContainer().innerHTML = category;

                // update current value
                _this.setCurrentValue(category);

                // hide
                _this.inlineControl.disableEditMode();
            });
        }

        getValueContainer() {
            return this.inlineControl.container.querySelector('[inline-control=value-container]');
        }
    }

    var DescriptionInlineControl = class {
        constructor(container, currentDescription) {
            this.inlineControl = new InlineControl(container);
            this.currentValue = this.getInputValue();

            // events for name inline control
            this.events();
        }

        getCurrentValue() {
            return this.currentValue;
        }

        setCurrentValue(value) {
            this.currentValue = value;
        }

        resetCurrentValue() {
            this.getInput().value = this.getCurrentValue();
        }

        getInput() {
            return this.inlineControl.container.querySelector('[name=description]');
        }

        getInputValue() {
            return this.getInput().value.trim();
        }

        events() {
            var _this = this;

            // submit form
            this.inlineControl.getInlineForm().addEventListener('submit', (e) => {
                e.preventDefault();

                // save
                _this.validateThenSave();
            });

            // click on inline control
            this.inlineControl.getInlineValueBox().addEventListener('click', (e) => {
                e.preventDefault();

                // save
                _this.inlineControl.enableEditMode(function() {
                    _this.getInput().focus();
                });
            });

            // click save button
            this.inlineControl.getSaveButon().addEventListener('click', (e) => {
                e.preventDefault();

                // save
                _this.validateThenSave();
            });

            // click cancel button
            this.inlineControl.getCancelButon().addEventListener('click', (e) => {
                e.preventDefault();

                // reset input value
                _this.resetCurrentValue();

                // disable edit mode
                _this.inlineControl.disableEditMode();
            });
        }
        
        validate() {
            // input is empty
            if (!this.getInputValue()) {
                alert('Description can not be empty!');
                return false;
            }

            return true;
        }

        validateThenSave() {
            var _this = this;
            
            if (!this.validate()) {
                return;
            }

            this.inlineControl.save(function(response) {
                const description = response.description;
                // apply value to value container
                _this.getValueContainer().innerHTML = description;

                // update current value
                _this.setCurrentValue(description);

                // hide
                _this.inlineControl.disableEditMode();
            });
        }

        getValueContainer() {
            return this.inlineControl.container.querySelector('[inline-control=value-container]');
        }
    }

    var TagsInlineControl = class {
        constructor(container, currentTags) {
            this.inlineControl = new InlineControl(container);
            this.currentValue = this.getInputValue();

            // events for name inline control
            this.events();
        }

        getCurrentValue() {
            return this.currentValue;
        }

        setCurrentValue(value) {
            this.currentValue = value;
        }

        resetCurrentValue() {
            this.getInput().value = this.getCurrentValue();
        }

        getInput() {
            return this.inlineControl.container.querySelector('[name="inline_mass_tags[]"]');
        }

        getInputValue() {
            return $(this.getInput()).val();
        }

        events() {
            var _this = this;

            // submit form
            this.inlineControl.getInlineForm().addEventListener('submit', (e) => {
                e.preventDefault();

                // save
                _this.validateThenSave();
            });

            // click on inline control
            this.inlineControl.getInlineValueBox().addEventListener('click', (e) => {
                e.preventDefault();

                // save
                _this.inlineControl.enableEditMode(function() {
                    _this.getInput().focus();
                });
            });

            // click save button
            this.inlineControl.getSaveButon().addEventListener('click', (e) => {
                e.preventDefault();

                // save
                _this.validateThenSave();
            });

            // click cancel button
            this.inlineControl.getCancelButon().addEventListener('click', (e) => {
                e.preventDefault();

                // reset input value
                _this.resetCurrentValue();

                // disable edit mode
                _this.inlineControl.disableEditMode();
            });
        }
        
        validate() {

            return true;
        }

        validateThenSave() {
            var _this = this;
            
            if (!this.validate()) {
                return;
            }

            this.inlineControl.save(function(response) {
                const tags = response.tags;
                // apply value to value container
                _this.getValueContainer().innerHTML = tags;

                // update current value
                _this.setCurrentValue(tags);

                // hide
                _this.inlineControl.disableEditMode();
            });
        }

        getValueContainer() {
            return this.inlineControl.container.querySelector('[inline-control=value-container]');
        }
    }

    var RefundDaysInlineControl = class {
        constructor(container, currentName) {
            this.inlineControl = new InlineControl(container);
            this.currentPrice = this.getRefundDaysInputValue();

            // events for name inline control
            this.events();
        }

        getRefundDaysContainer() {
            return this.inlineControl.container.querySelector('[inline-control=refund_days-container]');
        }

        getCurrentRefundDaysValue() {
            return this.currentRefundDays;
        }

        setCurrentRefundDaysValue(value) {
            this.currentRefundDays = value;
        }

        getRefundDaysInput() {
            return this.inlineControl.container.querySelector('[name=refund_days]');
        }

        getRefundDaysInputValue() {
            return this.getRefundDaysInput().value.trim();
        }

        resetCurrentValue() {
            this.getRefundDaysInput().value = this.getCurrentRefundDaysValue();
        }

        events() {
            var _this = this;

            // click on inline control
            this.inlineControl.getInlineValueBox().addEventListener('click', (e) => {
                e.preventDefault();

                // save
                _this.inlineControl.enableEditMode(function() {
                    _this.getRefundDaysInput().focus();
                });
            });

            // click cancel button
            this.inlineControl.getCancelButon().addEventListener('click', (e) => {
                e.preventDefault();

                // reset input value
                _this.resetCurrentValue();

                // disable edit mode
                _this.inlineControl.disableEditMode();
            });

            // submit form
            this.inlineControl.getInlineForm().addEventListener('submit', (e) => {
                e.preventDefault();

                // save
                _this.validateThenSave();
            });

            // click save button
            this.inlineControl.getSaveButon().addEventListener('click', (e) => {
                e.preventDefault();

                // save
                _this.validateThenSave();
            });

            
        }
        
        validate() {
            // input is empty
            if (!this.getRefundDaysInputValue()) {
                alert('Refund days can not be empty!');
                return false;
            }

            return true;
        }

        validateThenSave() {
            var _this = this;
            
            if (!this.validate()) {
                return;
            }

            this.inlineControl.save(function(response) {
                const refund_days = response.refund_days;

                // apply value to value container
                _this.getRefundDaysContainer().innerHTML = refund_days;

                // update current value
                _this.setCurrentRefundDaysValue(refund_days);

                // hide
                _this.inlineControl.disableEditMode();
            });
        }
    }

    var PriceInlineControl = class {
        constructor(container, currentName) {
            this.inlineControl = new InlineControl(container);
            this.currentPrice = this.getPriceInputValue();

            // events for name inline control
            this.events();
        }

        getPriceContainer() {
            return this.inlineControl.container.querySelector('[inline-control=price-container]');
        }

        getCurrentPriceValue() {
            return this.currentPrice;
        }

        setCurrentPriceValue(value) {
            this.currentPrice = value;
        }

        getPriceInput() {
            return this.inlineControl.container.querySelector('[name=price]');
        }

        getPriceInputValue() {
            return this.getPriceInput().value.trim();
        }

        resetCurrentValue() {
            this.getPriceInput().value = this.getCurrentPriceValue();
        }

        events() {
            var _this = this;

            // click on inline control
            this.inlineControl.getInlineValueBox().addEventListener('click', (e) => {
                e.preventDefault();

                // save
                _this.inlineControl.enableEditMode(function() {
                    _this.getPriceInput().focus();
                });
            });

            // click cancel button
            this.inlineControl.getCancelButon().addEventListener('click', (e) => {
                e.preventDefault();

                // reset input value
                _this.resetCurrentValue();

                // disable edit mode
                _this.inlineControl.disableEditMode();
            });

            // submit form
            this.inlineControl.getInlineForm().addEventListener('submit', (e) => {
                e.preventDefault();

                // save
                _this.validateThenSave();
            });

            // click save button
            this.inlineControl.getSaveButon().addEventListener('click', (e) => {
                e.preventDefault();

                // save
                _this.validateThenSave();
            });

            
        }
        
        validate() {
            // input is empty
            if (!this.getPriceInputValue()) {
                alert('Price can not be empty!');
                return false;
            }

            return true;
        }

        validateThenSave() {
            var _this = this;
            
            if (!this.validate()) {
                return;
            }

            this.inlineControl.save(function(response) {
                const price = response.price;

                // apply value to value container
                _this.getPriceContainer().innerHTML = price;

                // update current value
                _this.setCurrentPriceValue(price);

                // hide
                _this.inlineControl.disableEditMode();
            });
        }
    }

    var NameInlineControl = class {
        constructor(container, currentName) {
            this.inlineControl = new InlineControl(container);
            this.currentValue = this.getInputValue();

            // events for name inline control
            this.events();
        }

        getCurrentValue() {
            return this.currentValue;
        }

        setCurrentValue(value) {
            this.currentValue = value;
        }

        resetCurrentValue() {
            this.getInput().value = this.getCurrentValue();
        }

        getInput() {
            return this.inlineControl.container.querySelector('[name=product_name]');
        }

        getInputValue() {
            return this.getInput().value.trim();
        }

        events() {
            var _this = this;

            // submit form
            this.inlineControl.getInlineForm().addEventListener('submit', (e) => {
                e.preventDefault();

                // save
                _this.validateThenSave();
            });

            // click on inline control
            this.inlineControl.getInlineValueBox().addEventListener('click', (e) => {
                e.preventDefault();

                // save
                _this.inlineControl.enableEditMode(function() {
                    _this.getInput().focus();
                });
            });

            // click save button
            this.inlineControl.getSaveButon().addEventListener('click', (e) => {
                e.preventDefault();

                // save
                _this.validateThenSave();
            });

            // click cancel button
            this.inlineControl.getCancelButon().addEventListener('click', (e) => {
                e.preventDefault();

                // reset input value
                _this.resetCurrentValue();

                // disable edit mode
                _this.inlineControl.disableEditMode();
            });
        }
        
        validate() {
            // input is empty
            if (!this.getInputValue()) {
                alert('Name can not be empty!');
                return false;
            }

            return true;
        }

        validateThenSave() {
            var _this = this;
            
            if (!this.validate()) {
                return;
            }

            this.inlineControl.save(function(response) {
                const product_name = response.product_name;
                // apply value to value container
                _this.getValueContainer().innerHTML = product_name;

                // update current value
                _this.setCurrentValue(product_name);

                // hide
                _this.inlineControl.disableEditMode();
            });
        }

        getValueContainer() {
            return this.inlineControl.container.querySelector('[inline-control=value-container]');
        }
    }
    
    var InlineControl = class {
        constructor(container) {
            this.container = container;

            // show value box
            this.disableEditMode();
        }

        enableEditMode(afterEnable) {
            this.getInlineValueBox().style.display = 'none';
            this.getInlineFormBox().style.display = 'inline-block';

            if (afterEnable) {
                afterEnable();
            }
        }

        disableEditMode() {
            this.getInlineValueBox().style.display = 'inline-block';
            this.getInlineFormBox().style.display = 'none';
        }

        addSavingEffect() {
            this.container.classList.add('inline-saving');
            $(this.container).prepend(`<span class="loader loader-sm"></span>`);
        }

        removeSavingEffect() {
            this.container.classList.remove('inline-saving');
            this.container.querySelector('.loader').remove();
        }

        save(onsave) {
            var _this = this;

            this.addSavingEffect();

            $.ajax({
                url: this.getSaveUrl(),
                method: 'POST',
                data: $(this.getInlineForm()).serialize()
            }).done(function (response) {
                // onsave callback
                if (onsave) {
                    onsave(response);
                }
            }).fail(function (response) {
            }).always(function() {
                _this.removeSavingEffect();
            });
        }

        getSaveUrl() {
            return this.getInlineForm().getAttribute('action');
        }

        getInlineValueBox() {
            return this.container.querySelector('[inline-control=value-box]');
        }

        getInlineFormBox() {
            return this.container.querySelector('[inline-control=form-box]');
        }

        getInlineForm() {
            return this.container.querySelector('[inline-control=form]');
        }

        getSaveButon() {
            return this.container.querySelector('[inline-control=save]');
        }

        getCancelButon() {
            return this.container.querySelector('[inline-control=cancel]');
        }
    }

    $(function() {
        $('.inline_mass_tags.select2_init_multi').select2({
            tags: true,
            theme: 'bootstrap4',
            placeholder: lang('Tags'),
            insertTag: function (data, tag) {
                // Disable selecting numeric value
                if (isNaN(tag.text)) {
                    data.push(tag);
                }
            },
        });
    });
</script>
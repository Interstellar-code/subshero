import '@grapecity/wijmo.styles/wijmo.css';
//
import '@grapecity/wijmo.touch';
import * as wjcCore from '@grapecity/wijmo';
import * as wjcGrid from '@grapecity/wijmo.grid';
import * as wjcGridFilter from '@grapecity/wijmo.grid.filter';
import * as wjcGridSearch from '@grapecity/wijmo.grid.search';
import * as wjcGridGroupPanel from '@grapecity/wijmo.grid.grouppanel';
import * as wjcInput from '@grapecity/wijmo.input';
import { CellMaker, SparklineMarkers } from '@grapecity/wijmo.grid.cellmaker';
import { KeyValue, Country, DataService } from './data.min.js';
import { ExportService } from './export.min.js';
//
class App {
    constructor(dataSvc, exportSvc) {
        // Apply the distribution key to the application.
        wijmo.setLicenseKey("dev.subshero.com|app.subshero.com|localhost,349216816924725#B0JMIdxIDMyIiOiIXZ6JCLlNHbhZmOiI7ckJye0ICbuFkI1pjIEJCLi4TPBpHc5VFSudWaJ34ZQVnQzFmZxhjUyIzZrc5U5J6L9FWYXRXelN6U7IGTmRjZGZDSroWSzwEW6k4Qv3kbKdTd9MTau5mcH5GZhR5NthWWKt6Nvsyb8tmNqlGbo96VD5EStlTRYdjcxc7Rj96LFFGRBZmdP5kZ9pUZyl5UVR5dz9Gbl9mRFdnWt5UbndkYTJEWYl4VuZldvQ7Y5YzL4FGb44mZkZDbDR7NYVlSyZUdB9GMWVjV4gENOxkb5MGOxEkW6RXaytiaqRmRNVEcu9UNpZmbNtyb62yY7MmTENGZNNDSKhlbXRGSap4MwpFOOpEUBh6UHFTOEhVSzQ5Z7RzKQpFWthkcxNWYlhUO9QFN4VmVvRlaVFnUw3EVxZWOyBVavEDdt3mdaZjRulUb4BXVmlVeRBnZIp7TVRUSBVnSLpEZjZ4K4Z5dYFHNYZDMrkVNqNVM4NTZiojITJCLiIzNFREMDRUNiojIIJCL9YjN8AjMzcjM0IicfJye#4Xfd5nIJBjMSJiOiMkIsIibvl6cuVGd8VEIgQXZlh6U8VGbGBybtpWaXJiOi8kI1xSfiUTSOFlI0IyQiwiIu3Waz9WZ4hXRgAicldXZpZFdy3GclJFIv5mapdlI0IiTisHL3JyS7gDSiojIDJCLi86bpNnblRHeFBCI73mUpRHb55EIv5mapdlI0IiTisHL3JCNGZDRiojIDJCLi86bpNnblRHeFBCIQFETPBCIv5mapdlI0IiTisHL3JyMDBjQiojIDJCLiUmcvNEIv5mapdlI0IiTisHL3JSV8cTQiojIDJCLi86bpNnblRHeFBCI4JXYoNEbhl6YuFmbpZEIv5mapdlI0IiTis7W0ICZyBlIsIyNzADMyADI4ATMwIjMwIjI0ICdyNkIsICdz3GasF6YvxGLt36Yu2mclh6ciV7cuAHchxSbvNmLvJXZoNnY5NnL6VGZiojIz5GRiwiIIJUbHByZulGdsV7cu36QgIXYsxWZ4NnclRnbJJiOiEmTDJCLiUjM7QjM9YTM8YTMykDNzIiOiQWSiwSfdtlOicGbcJML");

        this._itemsCount = 100;
        this._lastId = this._itemsCount;
        this._dataSvc = dataSvc;
        this._exportSvc = exportSvc;
        // initializes data maps
        this._paymentMethodsMap = this._buildDataMap(this._dataSvc.getPaymentMethods());
        // initializes cell templates
        this._historyCellTemplate = CellMaker.makeSparkline({
            markers: SparklineMarkers.High | SparklineMarkers.Low,
            maxPoints: 25,
            label: 'price history',
        });
        this._ratingCellTemplate = CellMaker.makeRating({
            range: [1, 5],
            label: 'rating'
        });
        // initializes data size
        // document.getElementById('itemsCount').addEventListener('change', (e) => {
        //     const value = e.target.value;
        //     this._itemsCount = wjcCore.changeType(value, wjcCore.DataType.Number);
        //     this._handleItemsCountChange();
        // });
        // initializes export
        // const btnExportToExcel = document.getElementById('btnExportToExcel');
        // this._excelExportContext = new ExcelExportContext(btnExportToExcel);
        // btnExportToExcel.addEventListener('click', () => {
        //     this._exportToExcel();
        // });
        // document.getElementById('btnExportToPdf').addEventListener('click', () => {
        //     this._exportToPdf();
        // });
        // initializes the grid
        // this._initializeGrid();
        // initializes items source
        // this._itemsSource = this._createItemsSource();
        // this._theGrid.itemsSource = this._itemsSource;
    }
    close() {
        // const ctx = this._excelExportContext;
        // this._exportSvc.cancelExcelExport(ctx);
    }
    _initializeGrid() {
        // creates the grid
        this._theGrid = new wjcGrid.FlexGrid('#subscription_history_table_wijmo_grid', {
            autoGenerateColumns: false,
            allowAddNew: true,
            allowDelete: true,
            allowPinning: wjcGrid.AllowPinning.SingleColumn,
            // newRowAtTop: true,
            // showMarquee: true,
            selectionMode: wjcGrid.SelectionMode.MultiRange,
            headersVisibility: wjcGrid.HeadersVisibility.Column,
            validateEdits: false,
            columns: [
                // { binding: 'id', header: 'ID', width: 70, isReadOnly: true },
                {
                    binding: 'payment_date', header: 'Payment Date', format: 'MMM d yyyy', isRequired: false, width: '*',
                    editor: new wjcInput.InputDate(document.createElement('div'), {
                        format: 'MM/dd/yyyy',
                        isRequired: false,
                        showDropDownButton: false,
                        dropDownCssClass: 'app-datepicker',
                    }),
                    cellTemplate: (ctx) => {
                        const dataItem = ctx.row.dataItem;
                        if (wjcCore.isUndefined(dataItem) || dataItem === null) {
                            return '';
                        }
                        return app.subscription.t.history.table.get_payment_date(ctx.item.payment_date);
                    },
                    cssClass: 'payment_date_column',
                },
                {
                    binding: 'price', header: `Price (${this._dataSvc._real_data.currency})`, format: 'n', isRequired: false, width: '*',
                    align: 'left',
                    cssClass: 'text-start',
                },
                {
                    binding: 'payment_method', header: 'Method', dataMap: this._paymentMethodsMap, width: '*', isRequired: true,
                    cellTemplate: (ctx) => {
                        const dataItem = ctx.row.dataItem;
                        if (wjcCore.isUndefined(dataItem) || dataItem === null) {
                            return '';
                        }
                        return app.subscription.t.history.table.get_payment_method(ctx.text);
                    },
                },
                {
                    binding: 'action', header: ' ', width: 100,
                    cellTemplate: (ctx) => {
                        const dataItem = ctx.row.dataItem;
                        if (wjcCore.isUndefined(dataItem) || dataItem === null) {
                            return '';
                        }
                        return app.subscription.t.history.table.get_action(ctx.item.id);
                    },
                    cssClass: 'text-start',
                    isReadOnly: true,
                }
            ],

            onLoadedRows: (e) => {
                lib.sleep(1000).then(() => {
                    app.load.tooltip();
                });
            },
        });
        // create the grid search box
        // new wjcGridSearch.FlexGridSearch('#theSearch', {
        //     placeholder: 'Search',
        //     grid: this._theGrid,
        //     cssMatch: ''
        // });
        // // adds Excel-like filter
        // new wjcGridFilter.FlexGridFilter(this._theGrid, {
        //     filterColumns: [
        //         'id', 'date', 'time', 'countryId', 'productId',
        //         'colorId', 'price', 'change', 'discount', 'rating', 'active'
        //     ]
        // });
        // adds group panel
        // new wjcGridGroupPanel.GroupPanel('#theGroupPanel', {
        //     placeholder: 'Drag columns here to create groups',
        //     grid: this._theGrid
        // });
    }
    _getChangeCls(value) {
        if (wjcCore.isNumber(value)) {
            if (value > 0) {
                return 'change-up';
            }
            if (value < 0) {
                return 'change-down';
            }
        }
        return '';
    }
    _formatChange(value) {
        if (wjcCore.isNumber(value)) {
            return wjcCore.Globalize.formatNumber(value, 'c');
        }
        if (!wjcCore.isUndefined(value) && value !== null) {
            return wjcCore.changeType(value, wjcCore.DataType.String);
        }
        return '';
    }
    _exportToExcel() {
        const ctx = this._excelExportContext;
        if (!ctx.exporting) {
            this._exportSvc.startExcelExport(this._theGrid, ctx);
        }
        else {
            this._exportSvc.cancelExcelExport(ctx);
        }
    }
    _exportToPdf() {
        this._exportSvc.exportToPdf(this._theGrid, {
            countryMap: this._countryMap,
            colorMap: this._colorMap,
            historyCellTemplate: this._historyCellTemplate
        });
    }
    _createItemsSource() {
        const data = this._dataSvc.getData(this._itemsCount);
        const view = new wjcCore.CollectionView(data, {
            getError: (item, prop) => {
                const displayName = this._theGrid.columns.getColumn(prop).header;
                return this._dataSvc.validate(item, prop, displayName);
            }
        });
        view.collectionChanged.addHandler((s, e) => {
            // initializes new added item with a history data
            if (e.action === wjcCore.NotifyCollectionChangedAction.Add) {
                e.item.history = this._dataSvc.getHistoryData();
                e.item.id = this._lastId;
                this._lastId++;
            }
        });
        return view;
    }
    _disposeItemsSource(itemsSource) {
        if (itemsSource) {
            itemsSource.collectionChanged.removeAllHandlers();
        }
    }
    // build a data map from a string array using the indices as keys
    _buildDataMap(items) {
        const map = [];
        for (let i = 0; i < items.length; i++) {
            map.push({ key: i, value: items[i] });
        }
        return new wjcGrid.DataMap(map, 'key', 'value');
    }
    _handleItemsCountChange() {
        this._disposeItemsSource(this._itemsSource);
        this._lastId = this._itemsCount;
        this._itemsSource = this._createItemsSource();
        this._theGrid.itemsSource = this._itemsSource;
    }
}
//
class ExcelExportContext {
    constructor(btn) {
        this._exporting = false;
        this._progress = 0;
        this._preparing = false;
        this._btn = btn;
    }
    get exporting() {
        return this._exporting;
    }
    set exporting(value) {
        if (value !== this._exporting) {
            this._exporting = value;
            this._onPropertyChanged();
        }
    }
    get progress() {
        return this._progress;
    }
    set progress(value) {
        if (value !== this._progress) {
            this._progress = value;
            this._onPropertyChanged();
        }
    }
    get preparing() {
        return this._preparing;
    }
    set preparing(value) {
        if (value !== this._preparing) {
            this._preparing = value;
            this._onPropertyChanged();
        }
    }
    _onPropertyChanged() {
        wjcCore.enable(this._btn, !this._preparing);
        if (this._exporting) {
            const percent = wjcCore.Globalize.formatNumber(this._progress, 'p0');
            this._btn.textContent = `Cancel (${percent} done)`;
        }
        else {
            this._btn.textContent = 'Export To Excel';
        }
    }
}
//
document.readyState === 'complete' ? init() : window.onload = init;
//
function init() {
    app.subscription.history_load = function () {
        const subscription_id = $('#subscription_history_table_wijmo_grid').data('subscription_id');
        $.ajax({
            url: `${app.url}subscription_history/get/${subscription_id}`,
            type: 'GET',
            dataType: 'json',
            success: function (real_data) {
                const dataSvc = new DataService(real_data);
                const exportSvc = new ExportService();
                const app = new App(dataSvc, exportSvc);

                // initializes the grid
                app._initializeGrid();
                // initializes items source
                app._itemsSource = app._createItemsSource();
                app._theGrid.itemsSource = app._itemsSource;

                document.wijmoApp = app;
                $('#subscription_history_product_image').prop('src', real_data.subscription_image);
                $('#subscription_history_product_name').text(real_data.product_name);

                window.addEventListener('unload', () => {
                    app.close();
                });
            },
        });
    }
}

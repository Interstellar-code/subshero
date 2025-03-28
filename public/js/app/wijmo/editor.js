import * as wjcCore from '@grapecity/wijmo';
import * as wjcGrid from '@grapecity/wijmo.grid';
import * as wjcInput from '@grapecity/wijmo.input';
//
// *** CustomGridEditor class (transpiled from TypeScript) ***
//
export class CustomGridEditor {
    /**
     * Initializes a new instance of a CustomGridEditor.
     */
    constructor(grid, binding, edtClass, options) {
        // save references
        this._grid = grid;
        this._col = grid.columns.getColumn(binding);
        // create error tooltip
        this._errorTip = new wjcCore.Tooltip({
            isContentHtml: false,
            showDelay: 0,
            cssClass: 'wj-error-tip'
        });
        // create editor
        this._ctl = new edtClass(document.createElement('div'), options);
        wjcCore.addClass(this._ctl.hostElement, 'custom-editor');
        wjcCore.setCss(this._ctl.hostElement, {
            position: 'relative',
            height: '100%',
            borderRadius: '0px',
            border: 'none'
        });
        // create hosting element
        this._host = document.createElement('div');
        this._host.appendChild(this._ctl.hostElement);
        // connect grid events
        grid.beginningEdit.addHandler(this._beginningEdit, this);
        grid.sortingColumn.addHandler(() => {
            this._commitRowEdits();
        });
        grid.scrollPositionChanged.addHandler(() => {
            if (this._ctl.containsFocus()) {
                grid.focus();
            }
        });
        grid.selectionChanging.addHandler((s, e) => {
            if (e.row != s.selection.row) {
                this._commitRowEdits();
            }
        });
        // connect editor events
        this._ctl.addEventListener(this._ctl.hostElement, 'keydown', (e) => {
            switch (e.keyCode) {
                case wjcCore.Key.Tab:
                case wjcCore.Key.Enter:
                    e.preventDefault(); // TFS 255685
                    this._closeEditor(true);
                    this._grid.focus();
                    // forward event to the grid so it will move the selection
                    var evt = document.createEvent('HTMLEvents');
                    evt.initEvent('keydown', true, true);
                    'altKey,metaKey,ctrlKey,shiftKey,keyCode'.split(',').forEach((prop) => {
                        evt[prop] = e[prop];
                    });
                    this._grid.hostElement.dispatchEvent(evt);
                    break;
                case wjcCore.Key.Escape:
                    this._closeEditor(false);
                    this._grid.focus();
                    break;
            }
        });
        // close the editor when it loses focus
        this._ctl.lostFocus.addHandler(() => {
            setTimeout(() => {
                if (!this._ctl.containsFocus()) {
                    this._closeEditor(true); // apply edits and close editor
                    this._grid.onLostFocus(); // commit item edits if the grid lost focus
                }
            });
        });
        // commit edits when grid loses focus
        this._grid.lostFocus.addHandler(() => {
            setTimeout(() => {
                if (!this._grid.containsFocus() && !CustomGridEditor._isEditing) {
                    this._commitRowEdits();
                }
            });
        });
        // open drop-down on f4/alt-down
        this._grid.addEventListener(this._grid.hostElement, 'keydown', (e) => {
            // open drop-down on f4/alt-down
            this._openDropDown = false;
            if (e.keyCode == wjcCore.Key.F4 ||
                (e.altKey && (e.keyCode == wjcCore.Key.Down || e.keyCode == wjcCore.Key.Up))) {
                var colIndex = this._grid.selection.col;
                if (colIndex > -1 && this._grid.columns[colIndex] == this._col) {
                    this._openDropDown = true;
                    this._grid.startEditing(true);
                    e.preventDefault();
                }
            }
            // commit edits on Enter (in case we're at the last row, TFS 268944)
            if (e.keyCode == wjcCore.Key.Enter) {
                this._commitRowEdits();
            }
        }, true);
        // close editor when user resizes the window
        // REVIEW: hides editor when soft keyboard pops up (TFS 326875)
        window.addEventListener('resize', () => {
            if (this._ctl.containsFocus()) {
                this._closeEditor(true);
                this._grid.focus();
            }
        });
    }
    // gets an instance of the control being hosted by this grid editor
    get control() {
        return this._ctl;
    }
    // handle the grid's beginningEdit event by canceling the built-in editor,
    // initializing the custom editor and giving it the focus.
    _beginningEdit(grid, args) {
        // check that this is our column
        if (grid.columns[args.col] != this._col) {
            return;
        }
        // check that this is not the Delete key
        // (which is used to clear cells and should not be messed with)
        var evt = args.data;
        if (evt && evt.keyCode == wjcCore.Key.Delete) {
            return;
        }
        // cancel built-in editor
        args.cancel = true;
        // save cell being edited
        this._rng = args.range;
        CustomGridEditor._isEditing = true;
        // update cell before editing
        grid.refreshRange(this._rng);
        // show error
        if (grid._getShowErrors()) {
            var error = grid._getError(args.panel, args.row, args.col);
            this._showError(error);
        }
        else {
            this._clearError();
        }
        // initialize editor host
        var rcCell = grid.getCellBoundingRect(args.row, args.col), rcBody = document.body.getBoundingClientRect(), ptOffset = new wjcCore.Point(-rcBody.left, -rcBody.top), zIndex = (args.row < grid.frozenRows || args.col < grid.frozenColumns) ? '3' : '';
        wjcCore.setCss(this._host, {
            position: 'absolute',
            overflow: 'hidden',
            left: rcCell.left + ptOffset.x - 1,
            top: rcCell.top + ptOffset.y - 1,
            width: rcCell.width + 2,
            height: grid.rows[args.row].renderHeight + 2,
            backgroundColor: 'transparent',
            padding: '2px',
            zIndex: zIndex,
        });
        // initialize editor content
        this._ctl['value'] = grid.getCellData(this._rng.row, this._rng.col, false);
        // start editing item
        var ecv = grid.editableCollectionView, item = grid.rows[args.row].dataItem;
        if (ecv && item && item != ecv.currentEditItem) {
            setTimeout(function () {
                grid.onRowEditStarting(args);
                ecv.editItem(item);
                grid.onRowEditStarted(args);
            }, 50); // wait for the grid to commit edits after losing focus
        }
        // activate editor
        document.body.appendChild(this._host);
        this._ctl.focus();
        setTimeout(() => {
            // get the key that triggered the editor
            var key = (evt && evt.charCode > 32)
                ? String.fromCharCode(evt.charCode)
                : null;
            // get input element in the control
            var input = this._ctl.hostElement.querySelector('input');
            // send key to editor
            if (input) {
                if (key) {
                    input.value = key;
                    wjcCore.setSelectionRange(input, key.length, key.length);
                    var evtInput = document.createEvent('HTMLEvents');
                    evtInput.initEvent('input', true, false);
                    input.dispatchEvent(evtInput);
                }
                else {
                    input.select();
                }
            }
            // give the control focus
            if (!input && !this._openDropDown) {
                this._ctl.focus();
            }
            // open drop-down on F4/alt-down
            if (this._openDropDown && this._ctl instanceof wjcInput.DropDown) {
                this._ctl.isDroppedDown = true;
                this._ctl.dropDown.focus();
            }
        }, 50);
    }
    // close the custom editor, optionally saving the edits back to the grid
    _closeEditor(saveEdits) {
        if (this._rng) {
            var flexGrid = this._grid, ctl = this._ctl;
            // raise grid's cellEditEnding event
            var e = new wjcGrid.CellEditEndingEventArgs(flexGrid.cells, this._rng);
            flexGrid.onCellEditEnding(e);
            // save editor value into grid
            if (saveEdits) {
                if (!wjcCore.isUndefined(ctl['value'])) {
                    this._grid.setCellData(this._rng.row, this._rng.col, ctl['value']);
                }
                else if (!wjcCore.isUndefined(ctl['text'])) {
                    this._grid.setCellData(this._rng.row, this._rng.col, ctl['text']);
                }
                else {
                    throw 'Can\'t get editor value/text...';
                }
                this._grid.invalidate();
            }
            // close editor and remove it from the DOM
            if (ctl instanceof wjcInput.DropDown) {
                ctl.isDroppedDown = false;
            }
            this._host.parentElement.removeChild(this._host);
            this._rng = null;
            CustomGridEditor._isEditing = false;
            // raise grid's cellEditEnded event
            flexGrid.onCellEditEnded(e);
        }
    }
    // commit row edits, fire row edit end events (TFS 339615)
    _commitRowEdits() {
        var flexGrid = this._grid, ecv = flexGrid.editableCollectionView;
        this._closeEditor(true);
        if (ecv && ecv.currentEditItem) {
            var e = new wjcGrid.CellEditEndingEventArgs(flexGrid.cells, flexGrid.selection);
            ecv.commitEdit();
            setTimeout(() => {
                flexGrid.onRowEditEnding(e);
                flexGrid.onRowEditEnded(e);
                flexGrid.invalidate();
            });
        }
    }
    _clearError() {
        this._showError(null);
    }
    _showError(error) {
        var hasError = !!error;
        wjcCore.toggleClass(this._ctl.hostElement, 'custom-editor-invalid', hasError);
        this._errorTip.setTooltip(this._ctl.hostElement, null);
        setTimeout(() => {
            this._errorTip.setTooltip(this._ctl.hostElement, error);
        });
    }
}
